<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli' || ($argv[1] ?? '') !== '--local') exit("Run: php tests/admin-list.php --local\n");
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../src/AdminList.php';
use KeySoftItalia\AdminList;
if (!in_array(DB_HOST, ['localhost','127.0.0.1','::1'], true)) throw new RuntimeException('Local database required');
$tables = ['quotes','used_device_quotes','repair_bookings']; $counts = [];
foreach ($tables as $table) {
    $stmt = $pdo->prepare('SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA=? AND TABLE_NAME=?'); $stmt->execute([DB_NAME,$table]);
    if ($stmt->fetchColumn() !== 'InnoDB') throw new RuntimeException('InnoDB required');
    $counts[$table] = (int)$pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
}
$pdo->beginTransaction();
$failures = 0; $passes = 0;
$test = static function (string $name, callable $fn) use (&$failures,&$passes): void {
    try { $fn(); ++$passes; echo "PASS $name\n"; }
    catch (Throwable $e) { ++$failures; echo "FAIL $name: {$e->getMessage()}\n"; }
};
$assert = static function (bool $value): void { if (!$value) throw new RuntimeException('Unexpected result'); };
$insert = static function(string $table,array $values) use ($pdo): void {
    $fields=implode(',',array_keys($values)); $marks=implode(',',array_fill(0,count($values),'?'));
    $pdo->prepare("INSERT INTO $table ($fields) VALUES ($marks)")->execute(array_values($values));
};
try {
    $prefix = 'KSI_LIST_' . bin2hex(random_bytes(8));
    $device = $pdo->query('SELECT id,name FROM devices ORDER BY id LIMIT 1')->fetch();
    foreach (['quotes'=>'quotes','used'=>'used_device_quotes','bookings'=>'repair_bookings'] as $module=>$table) {
        for ($i=0;$i<55;++$i) {
            $status = $i%2===0 ? 'pending' : ($module==='bookings' ? 'confirmed' : 'accepted');
            $common=['created_at'=>'2099-01-01 12:00:00','status'=>$status];
            if($module==='quotes') $values=$common+['device_id'=>$device['id'],'brand_text'=>$i===0?'ACME_20%':'Test','model_text'=>'Test',
                'problems_json'=>'[]','first_name'=>$prefix,'last_name'=>'Cliente '.$i,'email'=>'test@example.invalid','phone'=>'0000000000'];
            else $values=$common+['device_type'=>'smartphone','brand_name'=>$i===0?'ACME_20%':'Test','model_name'=>'Test',
                'customer_first_name'=>$prefix,'customer_last_name'=>'Cliente '.$i,'customer_email'=>'test@example.invalid','customer_phone'=>'0000000000'];
            if($module==='used') $values+=['device_condition'=>'buono'];
            if($module==='bookings') $values+=['preferred_date'=>'2099-01-02','preferred_time_slot'=>'mattina'];
            $insert($table,$values);
        }
        $test("$module paginates all results with stable ordering",static function()use($pdo,$module,$prefix,$assert):void {
            $a=AdminList::load($pdo,$module,['q'=>$prefix]);$b=AdminList::load($pdo,$module,['q'=>$prefix,'page'=>'2']);$c=AdminList::load($pdo,$module,['q'=>$prefix,'page'=>'3']);
            $assert($a['total']===55 && count($a['rows'])===25 && count($b['rows'])===25 && count($c['rows'])===5);
            $assert(!array_intersect(array_column($a['rows'],'id'),array_column($b['rows'],'id')));
            $assert((int)$a['rows'][0]['id']>(int)$b['rows'][0]['id']);
        });
        $test("$module searches records beyond the first page",static function()use($pdo,$module,$prefix,$assert):void {
            $r=AdminList::load($pdo,$module,['q'=>$prefix.' Cliente 0']);$assert($r['total']===1);
        });
        $test("$module combines search and status",static function()use($pdo,$module,$prefix,$assert):void {
            $r=AdminList::load($pdo,$module,['q'=>$prefix,'status'=>'pending']);$assert($r['total']===28 && count($r['rows'])===25);
        });
        $test("$module clamps a deleted or invalid page",static function()use($pdo,$module,$prefix,$assert):void {
            $r=AdminList::load($pdo,$module,['q'=>$prefix,'page'=>'999999']);$assert($r['page']===3 && count($r['rows'])===5);
        });
        $test("$module supports page size and empty results",static function()use($pdo,$module,$prefix,$assert):void {
            $r=AdminList::load($pdo,$module,['q'=>$prefix,'per_page'=>'50']);$assert(count($r['rows'])===50);
            $r=AdminList::load($pdo,$module,['q'=>$prefix.' NONE','page'=>'20']);$assert($r['total']===0 && $r['page']===1 && !$r['rows']);
        });
        $test("$module treats wildcard characters literally",static function()use($pdo,$module,$assert):void {
            $r=AdminList::load($pdo,$module,['q'=>'ACME_20%']);$assert($r['total']===1);
        });
        $test("$module rejects query arrays without type errors",static function()use($pdo,$module,$prefix,$assert):void {
            $r=AdminList::load($pdo,$module,['q'=>$prefix,'status'=>['x'],'page'=>['x'],'per_page'=>['x']]);$assert($r['page']===1 && $r['per_page']===25 && $r['status']==='');
        });
    }
    $test('pagination URL preserves filters and encodes user input',static function()use($pdo,$prefix,$assert):void {
        $r=AdminList::load($pdo,'quotes',['q'=>$prefix.'&x=<script>','status'=>'pending','device'=>'Phone','per_page'=>'50']);
        parse_str(ltrim(AdminList::url($r,2),'?'),$query);$assert($query['q']===$r['q'] && $query['status']==='pending' && $query['page']==='2' && !isset($query['x']));
    });
    $test('quote device filter uses the selected device',static function()use($pdo,$prefix,$device,$assert):void {
        $r=AdminList::load($pdo,'quotes',['q'=>$prefix,'device'=>$device['name']]);$assert($r['total']===55);
        $r=AdminList::load($pdo,'quotes',['q'=>$prefix,'device'=>'Missing Device']);$assert($r['total']===0);
    });
} finally {
    if($pdo->inTransaction())$pdo->rollBack();
    foreach($tables as $table) if((int)$pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn()!==$counts[$table])throw new RuntimeException('Rollback verification failed');
}
echo "$passes passed, $failures failed; fixtures rolled back.\n";
exit($failures?1:0);
