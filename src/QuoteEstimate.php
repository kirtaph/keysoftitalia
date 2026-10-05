<?php
declare(strict_types=1);

function norm(string $s): string { return trim(preg_replace('/\s+/u',' ', $s)); }

function get_device_id_by_slug(PDO $pdo, string $slug): ?int {
  $st = $pdo->prepare("SELECT id FROM devices WHERE slug = ? LIMIT 1");
  $st->execute([$slug]);
  $id = $st->fetchColumn();
  return $id ? (int)$id : null;
}

function get_brand_id(PDO $pdo, int $device_id, ?int $brand_id, ?string $brand_text): ?int {
  if ($brand_id) {
    $st = $pdo->prepare('SELECT id FROM brands WHERE id = ? AND device_id = ? LIMIT 1');
    $st->execute([$brand_id, $device_id]);
    $id = $st->fetchColumn();
    return $id ? (int)$id : null;
  }
  $name = norm((string)$brand_text);
  if ($name === '' || mb_strtolower($name,'UTF-8') === 'altro') return null;
  // cerco per name (case-insensitive) entro device_id
  $st = $pdo->prepare("SELECT id FROM brands WHERE device_id = ? AND (name = ? OR LOWER(name) = LOWER(?)) LIMIT 1");
  $st->execute([$device_id, $name, $name]);
  $id = $st->fetchColumn();
  return $id ? (int)$id : null;
}

function get_model_id(PDO $pdo, ?int $brand_id, ?string $model_text): ?int {
  if (!$brand_id) return null;
  $name = norm((string)$model_text);
  if ($name === '') return null;
  $st = $pdo->prepare("SELECT id FROM models WHERE brand_id = ? AND (name = ? OR LOWER(name) = LOWER(?)) LIMIT 1");
  $st->execute([$brand_id, $name, $name]);
  $id = $st->fetchColumn();
  return $id ? (int)$id : null;
}

function get_issue_id(PDO $pdo, int $device_id, string $label): ?int {
  $name = norm($label);
  $st = $pdo->prepare("SELECT id FROM issues WHERE device_id = ? AND (label = ? OR LOWER(label)=LOWER(?)) LIMIT 1");
  $st->execute([$device_id, $name, $name]);
  $id = $st->fetchColumn();
  return $id ? (int)$id : null;
}

/**
 * Ritorna la regola più specifica disponibile.
 * matched: device+brand+model+issue | device+brand+issue | device+issue
 */
function find_rule(PDO $pdo, int $device_id, ?int $brand_id, ?int $model_id, int $issue_id): ?array {
  // 1) device+brand+model+issue
  if ($brand_id && $model_id){
    $st = $pdo->prepare("SELECT min_price,max_price,notes FROM price_rules WHERE is_active=1 AND device_id=? AND brand_id=? AND model_id=? AND issue_id=? LIMIT 1");
    $st->execute([$device_id,$brand_id,$model_id,$issue_id]);
    if ($r = $st->fetch()) return $r + ['matched'=>'device+brand+model+issue'];
  }
  // 2) device+brand+issue
  if ($brand_id){
    $st = $pdo->prepare("SELECT min_price,max_price,notes FROM price_rules WHERE is_active=1 AND device_id=? AND brand_id=? AND model_id IS NULL AND issue_id=? LIMIT 1");
    $st->execute([$device_id,$brand_id,$issue_id]);
    if ($r = $st->fetch()) return $r + ['matched'=>'device+brand+issue'];
  }
  // 3) device+issue
  $st = $pdo->prepare("SELECT min_price,max_price,notes FROM price_rules WHERE is_active=1 AND device_id=? AND brand_id IS NULL AND model_id IS NULL AND issue_id=? LIMIT 1");
  $st->execute([$device_id,$issue_id]);
  if ($r = $st->fetch()) return $r + ['matched'=>'device+issue'];

  return null;
}

/**
 * Classifica la riga (fisso/range/da) in base a valori + notes
 */
function classify_rule(?string $min, ?string $max, ?string $notes): string {
  $minv = is_null($min) ? null : (float)$min;
  $maxv = is_null($max) ? null : (float)$max;
  $n = mb_strtolower((string)$notes, 'UTF-8');

  if ($maxv === null || $maxv == 0 || strpos($n, 'a partire') !== false || preg_match('/(^|\W)da($|\W)/u', $n)) {
    return 'from';
  }
  if ($minv !== null && $maxv !== null) {
    if (abs($minv - $maxv) < 0.005 || strpos($n,'fisso') !== false) return 'fixed';
    return 'range';
  }
  return 'unknown';
}

/**
 * Calcola la stima totale sulle issues selezionate con somma smart:
 * - se c'è almeno un "da": totale = "da € X" (niente max)
 * - altrimenti range/fisso con somma min/max
 */
function compute_estimate(PDO $pdo, string $device_slug, ?int $brand_id_in, string $brand_text, string $model_text, array $issues): array {
  $device_id = get_device_id_by_slug($pdo, $device_slug);
  if (!$device_id) {
    return ['type'=>'unknown','min'=>null,'max'=>null,'badge'=>'Da valutare','breakdown'=>[],'currency'=>'EUR'];
  }

  $brand_id = get_brand_id($pdo, $device_id, $brand_id_in, $brand_text);
  $model_id = get_model_id($pdo, $brand_id, $model_text);

  $sum_fixed = 0.0;
  $sum_range_min = 0.0;
  $sum_range_max = 0.0;
  $sum_from_min  = 0.0;
  $has_from = false;
  $has_unknown = false;
  $breakdown = [];

  foreach ($issues as $label) {
    $label = norm((string)$label);
    if ($label === '') continue;

    $issue_id = get_issue_id($pdo, $device_id, $label);
    if (!$issue_id){
      $has_unknown = true;
      $breakdown[] = ['issue'=>$label,'kind'=>'unknown','min'=>null,'max'=>null,'matched'=>null,'notes'=>null];
      continue;
    }

    $rule = find_rule($pdo, $device_id, $brand_id, $model_id, $issue_id);
    if (!$rule){
      $has_unknown = true;
      $breakdown[] = ['issue'=>$label,'kind'=>'unknown','min'=>null,'max'=>null,'matched'=>null,'notes'=>null];
      continue;
    }

    $min = isset($rule['min_price']) ? (float)$rule['min_price'] : null;
    $max = isset($rule['max_price']) ? (float)$rule['max_price'] : null;
    $kind = classify_rule($rule['min_price'] ?? null, $rule['max_price'] ?? null, $rule['notes'] ?? null);

    if ($kind === 'fixed') {
      $sum_fixed += $min;
    } elseif ($kind === 'range') {
      $sum_range_min += $min;
      $sum_range_max += ($max ?? $min);
    } elseif ($kind === 'from') {
      $sum_from_min += ($min ?? 0);
      $has_from = true;
    }

    $breakdown[] = [
      'issue'   => $label,
      'kind'    => $kind,
      'min'     => $min,
      'max'     => $max,
      'matched' => $rule['matched'],
      'notes'   => $rule['notes'] ?? null
    ];
  }

  $total_min = round($sum_fixed + $sum_range_min + $sum_from_min, 2);
  $total_max = ($has_from || $has_unknown) ? null : round($sum_fixed + $sum_range_max, 2);

  $known = array_filter($breakdown, static fn($row) => $row['kind'] !== 'unknown');
  if (!$known) {
    $type = 'unknown';
    $total_min = null;
    $total_max = null;
    $badge = 'Da valutare';
  } elseif ($has_from || $has_unknown) {
    $type  = 'from';
    $badge = 'da € '.$total_min;
  } else {
    if ($total_max === null || $total_min === $total_max) {
      $type = 'fixed';
      $badge = '€ '.$total_min;
      $total_max = $total_min;
    } else {
      $type = 'range';
      $badge = '€ '.$total_min.'–'.$total_max;
    }
  }

  return [
    'type'      => $type,
    'min'       => $total_min,
    'max'       => $total_max,
    'badge'     => $badge,
    'currency'  => 'EUR',
    'breakdown' => $breakdown
  ];
}

