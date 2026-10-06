<?php
declare(strict_types=1);
// Included by the HTTP/MySQL integration runner; not a standalone endpoint.
if (!isset($test, $request, $assert)) exit;

foreach (['telephony','utility','flyer','video','team'] as $module) {
    $test("$module list works with actual schema", static function () use ($request, $assert, $module): void {
        $r = $request(['endpoint' => "admin/ajax_actions/{$module}_actions.php"], ['action' => 'list'], [], true);
        $assert($r['http'] === 200 && $r['body']['status'] === 'success');
    });
}
foreach (['telephony','utility'] as $module) {
    $path = "admin/ajax_actions/{$module}_actions.php";
    foreach (['add','edit','delete','get'] as $action) {
        $test("$module promotion $action", static function () use ($request, $assert, $module, $path, $action): void {
            $scenario = ['endpoint' => $path, 'fixture' => "{$module}_partners", 'promotion' => $action !== 'add', 'inspect' => "{$module}_promotions"];
            $fields = ['action' => $action, 'id' => '@fixture', 'plan_name' => 'Test', 'price' => '29,90', 'utility_type' => 'gas', 'status' => '1'];
            $r = $request($scenario, $fields, [], $action === 'get');
            $assert($r['http'] === 200 && $r['body']['status'] === 'success');
            if (in_array($action, ['add','edit'], true)) $assert($r['rows'][0]['price'] === '29.90');
        });
    }
    foreach (['add_partner','edit_partner','delete_partner','get_partner'] as $action) {
        $test("$module partner $action", static function () use ($request, $assert, $module, $path, $action): void {
            $r = $request(['endpoint' => $path, 'fixture' => "{$module}_partners", 'inspect' => "{$module}_partners"], ['action' => $action, 'id' => '@fixture', 'name' => '@sku'], [], $action === 'get_partner');
            $assert($r['http'] === 200 && $r['body']['status'] === 'success');
        });
    }
    $test("$module rejects negative promotion price", static function () use ($request, $assert, $module, $path): void {
        $r = $request(['endpoint' => $path, 'fixture' => "{$module}_partners"], ['action' => 'add', 'plan_name' => 'Test', 'price' => '-1']);
        $assert($r['http'] === 422);
    });
    $test("$module rejects unknown request status", static function () use ($request, $assert, $path): void {
        $r = $request(['endpoint' => $path], ['action' => 'update_request_status', 'id' => '1', 'status' => 'Typo']);
        $assert($r['http'] === 422);
    });
    foreach (['In attesa','Contattato','Completato','Annullato'] as $status) {
        $test("$module request saves $status", static function () use ($request, $assert, $module, $path, $status): void {
            $r = $request(['endpoint' => $path, 'fixture' => "{$module}_requests", 'inspect' => "{$module}_requests"],
                ['action' => 'update_request_status', 'id' => '@fixture', 'status' => $status]);
            $assert($r['http'] === 200 && $r['rows'][0]['status'] === $status);
        });
    }
    $test("$module deletes test request", static function () use ($request, $assert, $module, $path): void {
        $r = $request(['endpoint' => $path, 'fixture' => "{$module}_requests", 'inspect' => "{$module}_requests"], ['action' => 'delete_request', 'id' => '@fixture']);
        $assert($r['http'] === 200 && $r['rows'] === []);
    });
}
$png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+a7X8AAAAASUVORK5CYII=');
foreach (['flyer' => ['table' => 'flyers', 'fields' => ['title' => '@sku', 'slug' => '@sku', 'start_date' => '2099-01-01', 'end_date' => '2099-02-01'], 'file' => 'cover_image'],
    'video' => ['table' => 'videos', 'fields' => ['title' => '@sku', 'fb_video_url' => 'https://www.facebook.com/watch/?v=1'], 'file' => 'cover_image'],
    'team' => ['table' => 'team_members', 'fields' => ['name' => '@sku', 'role' => 'Test'], 'file' => 'photo_file'],
    'telephony' => ['table' => 'telephony_partners', 'fields' => ['name' => '@sku'], 'file' => 'logo_file'],
    'utility' => ['table' => 'utility_partners', 'fields' => ['name' => '@sku'], 'file' => 'logo_file']] as $module => $data) {
    foreach (['add','edit','delete','get'] as $verb) {
        $action = in_array($module, ['telephony','utility'], true) ? $verb . '_partner' : $verb;
        if (in_array($module, ['telephony','utility'], true) && in_array($verb, ['get','delete'], true)) continue;
        $test("$module $verb with media", static function () use ($request, $assert, $module, $data, $verb, $action, $png): void {
            $scenario = ['endpoint' => "admin/ajax_actions/{$module}_actions.php", 'inspect' => $data['table']];
            if ($verb !== 'add') $scenario['fixture'] = $data['table'];
            $files = in_array($verb, ['add','edit'], true) ? [['field' => $data['file'], 'name' => 'test.png', 'type' => 'image/png', 'data' => $png]] : [];
            $r = $request($scenario, ['action' => $action, 'id' => '@fixture'] + $data['fields'], $files, $verb === 'get');
            $assert($r['http'] === 200 && $r['body']['status'] === 'success');
        });
    }
    $test("$module rejects fake uploaded image", static function () use ($request, $assert, $module, $data): void {
        $action = in_array($module, ['telephony','utility'], true) ? 'add_partner' : 'add';
        $r = $request(['endpoint' => "admin/ajax_actions/{$module}_actions.php"], ['action' => $action] + $data['fields'],
            [['field' => $data['file'], 'name' => 'fake.png', 'type' => 'image/png', 'data' => 'bad image']]);
        $assert($r['http'] === 422);
    });
}
$test('video refuses deceptive Facebook hostname', static function () use ($request, $assert): void {
    $r = $request(['endpoint' => 'admin/ajax_actions/video_actions.php'], ['action' => 'add', 'title' => '@sku', 'fb_video_url' => 'https://facebook.com.example.invalid/video']);
    $assert($r['http'] === 422);
});
$test('failed featured video preserves existing selection', static function () use ($request, $assert): void {
    $r = $request(['endpoint' => 'admin/ajax_actions/video_actions.php', 'fixture' => 'videos', 'inspect' => 'videos'],
        ['action' => 'add', 'title' => 'Test', 'fb_video_url' => 'https://www.facebook.com/watch/?v=1', 'is_featured' => '1'],
        [['field' => 'cover_image', 'name' => 'fake.png', 'type' => 'image/png', 'data' => 'bad image']]);
    $assert($r['http'] === 422 && (int)$r['rows'][0]['is_featured'] === 1);
});
$test('flyer rejects reversed date range', static function () use ($request, $assert): void {
    $r = $request(['endpoint' => 'admin/ajax_actions/flyer_actions.php'], ['action' => 'add', 'title' => '@sku', 'slug' => '@sku', 'start_date' => '2099-02-01', 'end_date' => '2099-01-01']);
    $assert($r['http'] === 422);
});
$test('failed flyer PDF replacement preserves old cover file', static function () use ($request, $assert, $png): void {
    $r = $request(['endpoint' => 'admin/ajax_actions/flyer_actions.php', 'fixture' => 'flyers', 'old_media' => true, 'inspect' => 'flyers'],
        ['action' => 'edit', 'id' => '@fixture', 'title' => '@sku', 'slug' => '@sku', 'start_date' => '2099-01-01', 'end_date' => '2099-02-01'],
        [['field' => 'cover_image', 'name' => 'cover.png', 'type' => 'image/png', 'data' => $png], ['field' => 'pdf_file', 'name' => 'bad.pdf', 'type' => 'application/pdf', 'data' => 'not a PDF']]);
    $assert($r['http'] === 422 && $r['old_media_exists'] === true && str_contains($r['rows'][0]['cover_image'], 'KSI_TEST_'));
});
foreach (['weekly_hours' => ['fixture' => 'ks_store_hours_weekly', 'fields' => ['dow' => '7', 'seg' => '2', 'open_time' => '10:00', 'close_time' => '14:00']],
    'exceptions' => ['fixture' => 'ks_store_hours_exceptions', 'fields' => ['date' => '2099-02-02', 'seg' => '1', 'is_closed' => '1', 'open_time' => '', 'close_time' => '']],
    'holidays' => ['fixture' => 'ks_store_holidays', 'fields' => ['name' => '@sku', 'rule_type' => 'easter', 'offset_days' => '1', 'month' => '', 'day' => '']]] as $module => $data) {
    foreach (['get','edit','delete'] as $action) {
        $test("$module $action", static function () use ($request, $assert, $module, $data, $action): void {
            $r = $request(['endpoint' => "admin/ajax_actions/{$module}_actions.php", 'fixture' => $data['fixture']], ['action' => $action, 'id' => '@fixture'] + $data['fields'], [], $action === 'get');
            $assert($r['http'] === 200 && $r['body']['status'] === 'success');
        });
    }
}
$test('weekly hours refuse close before open', static function () use ($request, $assert): void {
    $r = $request(['endpoint' => 'admin/ajax_actions/weekly_hours_actions.php', 'fixture' => 'ks_store_hours_weekly'],
        ['action' => 'edit', 'id' => '@fixture', 'dow' => '7', 'seg' => '2', 'open_time' => '14:00', 'close_time' => '10:00']);
    $assert($r['http'] === 422);
});
$test('holiday refuses impossible fixed date', static function () use ($request, $assert): void {
    $r = $request(['endpoint' => 'admin/ajax_actions/holidays_actions.php'], ['action' => 'add', 'name' => '@sku', 'rule_type' => 'fixed', 'month' => '2', 'day' => '31']);
    $assert($r['http'] === 422);
});
$test('calendar refuses malformed nested hours before writes', static function () use ($request, $assert): void {
    $r = $request(['endpoint' => 'admin/ajax_actions/hours_actions.php'], ['action' => 'save_weekly', 'hours[0][dow]' => '7', 'hours[0][seg]' => '2', 'hours[0][active]' => '1', 'hours[0][open_time]' => '16:00', 'hours[0][close_time]' => '09:00']);
    $assert($r['http'] === 422);
});
$test('calendar creates missing weekly segment without ID', static function () use ($request, $assert): void {
    $r = $request(['endpoint' => 'admin/ajax_actions/hours_actions.php', 'fixture' => 'ks_store_hours_weekly', 'missing_weekly' => true, 'inspect' => 'ks_store_hours_weekly'],
        ['action' => 'save_weekly', 'hours[0][id]' => '', 'hours[0][dow]' => '7', 'hours[0][seg]' => '2', 'hours[0][active]' => '1', 'hours[0][open_time]' => '09:00', 'hours[0][close_time]' => '13:00']);
    $assert($r['http'] === 200 && $r['body']['status'] === 'success' && $r['rows'][0]['open_time'] === '09:00:00');
});
$test('calendar saves closed segment with blank times', static function () use ($request, $assert): void {
    $r = $request(['endpoint' => 'admin/ajax_actions/hours_actions.php', 'fixture' => 'ks_store_hours_weekly', 'inspect' => 'ks_store_hours_weekly'],
        ['action' => 'save_weekly', 'hours[0][dow]' => '7', 'hours[0][seg]' => '2', 'hours[0][active]' => '0', 'hours[0][open_time]' => '', 'hours[0][close_time]' => '']);
    $assert($r['http'] === 200 && (int)$r['rows'][0]['active'] === 0 && $r['rows'][0]['open_time'] === '00:00:00');
});
$test('calendar saves open and closed exception segments', static function () use ($request, $assert): void {
    $r = $request(['endpoint' => 'admin/ajax_actions/hours_actions.php', 'fixture' => 'ks_store_hours_exceptions', 'inspect' => 'ks_store_hours_exceptions', 'inspect_date' => true],
        ['action' => 'save_exception', 'date' => '2099-02-02', 'segments[1][active]' => '1', 'segments[1][open_time]' => '09:00', 'segments[1][close_time]' => '13:00', 'segments[2][active]' => '0']);
    $assert($r['http'] === 200 && count($r['rows']) === 2 && (int)$r['rows'][1]['is_closed'] === 1);
});
$test('calendar handles Easter after 2038', static function () use ($request, $assert): void {
    $r = $request(['endpoint' => 'admin/ajax_actions/hours_actions.php', 'fixture' => 'ks_store_holidays'], ['action' => 'get_exceptions', 'start' => '2099-01-01', 'end' => '2099-12-31'], [], true);
    $assert($r['http'] === 200 && is_array($r['body']));
});
$test('flyer accepts actual PDF upload', static function () use ($request, $assert): void {
    $r = $request(['endpoint' => 'admin/ajax_actions/flyer_actions.php', 'inspect' => 'flyers'],
        ['action' => 'add', 'title' => '@sku', 'slug' => '@sku', 'start_date' => '2099-01-01', 'end_date' => '2099-02-01'],
        [['field' => 'pdf_file', 'name' => 'test.pdf', 'type' => 'application/pdf', 'data' => "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\ntrailer\n<< /Root 1 0 R >>\n%%EOF"]]);
    $assert($r['http'] === 200 && str_ends_with($r['rows'][0]['pdf_file'], '.pdf'));
});
foreach (['simple SVG' => '<svg xmlns="http://www.w3.org/2000/svg"><rect width="1" height="1"/></svg>',
    'active SVG' => '<svg xmlns="http://www.w3.org/2000/svg" onload="alert(1)"><script>alert(1)</script></svg>'] as $name => $svg) {
    $test("partner $name", static function () use ($request, $assert, $svg, $name): void {
        $r = $request(['endpoint' => 'admin/ajax_actions/utility_actions.php'], ['action' => 'add_partner', 'name' => '@sku'],
            [['field' => 'logo_file', 'name' => 'test.svg', 'type' => 'image/svg+xml', 'data' => $svg]]);
        $assert($r['http'] === ($name === 'simple SVG' ? 200 : 422));
    });
}
$test('calendar saves Easter holiday with empty month and day', static function () use ($request, $assert): void {
    $r = $request(['endpoint' => 'admin/ajax_actions/hours_actions.php', 'inspect' => 'ks_store_holidays'], ['action' => 'save_holiday', 'name' => '@sku', 'rule_type' => 'easter', 'offset_days' => '1', 'month' => '', 'day' => '']);
    $assert($r['http'] === 200 && $r['body']['status'] === 'success' && $r['rows'][0]['month'] === null);
});
$test('weekly hours creates new segment', static function () use ($request, $assert): void {
    $r = $request(['endpoint' => 'admin/ajax_actions/weekly_hours_actions.php', 'fixture' => 'ks_store_hours_weekly', 'missing_weekly' => true, 'inspect' => 'ks_store_hours_weekly'],
        ['action' => 'add', 'dow' => '7', 'seg' => '2', 'open_time' => '09:00', 'close_time' => '13:00', 'active' => '1']);
    $assert($r['http'] === 200 && count($r['rows']) === 1);
});
$test('exceptions creates second closed segment', static function () use ($request, $assert): void {
    $r = $request(['endpoint' => 'admin/ajax_actions/exceptions_actions.php', 'fixture' => 'ks_store_hours_exceptions', 'inspect' => 'ks_store_hours_exceptions', 'inspect_date' => true],
        ['action' => 'add', 'date' => '2099-02-02', 'seg' => '2', 'is_closed' => '1', 'open_time' => '', 'close_time' => '']);
    $assert($r['http'] === 200 && count($r['rows']) === 2);
});
$test('holidays creates fixed rule', static function () use ($request, $assert): void {
    $r = $request(['endpoint' => 'admin/ajax_actions/holidays_actions.php', 'inspect' => 'ks_store_holidays'],
        ['action' => 'add', 'name' => '@sku', 'rule_type' => 'fixed', 'month' => '12', 'day' => '25', 'offset_days' => '']);
    $assert($r['http'] === 200 && (int)$r['rows'][0]['month'] === 12);
});
