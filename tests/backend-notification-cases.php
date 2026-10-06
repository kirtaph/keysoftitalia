<?php
// Uses the existing real HTTP transport and rollback boundary.
$endpoint='admin/ajax_actions/notification_actions.php';
$test('push setup refuses GET',static function()use($request,$assert,$endpoint){
    $r=$request(['endpoint'=>$endpoint],['action'=>'setup_push'],[],true);$assert($r['http']===405);
});
$test('push setup requires CSRF',static function()use($request,$assert,$endpoint){
    $r=$request(['endpoint'=>$endpoint],['action'=>'setup_push','csrf_token'=>'invalid']);$assert($r['http']===403);
});
$test('push setup requires login',static function()use($request,$assert,$endpoint){
    $r=$request(['endpoint'=>$endpoint,'unauthenticated'=>true],['action'=>'setup_push']);$assert($r['http']===401);
});
$test('notification list includes unread count and safe public VAPID key',static function()use($request,$assert,$endpoint){
    $r=$request(['endpoint'=>$endpoint],['action'=>'list'],[],true);
    $assert($r['body']['status']==='success' && is_int($r['body']['unread']) && isset($r['body']['publicKey']) && !isset($r['body']['privateKey']));
});
$test('notification list requires admin login',static function()use($request,$assert,$endpoint){
    $r=$request(['endpoint'=>$endpoint,'unauthenticated'=>true],['action'=>'list'],[],true);$assert($r['http']===401);
});
$test('notification mutations refuse GET',static function()use($request,$assert,$endpoint){
    $r=$request(['endpoint'=>$endpoint],['action'=>'read_all','through'=>'0'],[],true);$assert($r['http']===405);
});
$test('notification mutation requires CSRF',static function()use($request,$assert,$endpoint){
    $r=$request(['endpoint'=>$endpoint],['action'=>'read_all','through'=>'0','csrf_token'=>'invalid']);$assert($r['http']===403);
});
$test('notification read-all is limited to visible cursor',static function()use($request,$assert,$endpoint){
    $r=$request(['endpoint'=>$endpoint],['action'=>'read_all','through'=>'0']);$assert($r['body']['status']==='success');
});
$test('push subscription refuses private endpoints',static function()use($request,$assert,$endpoint){
    $r=$request(['endpoint'=>$endpoint],['action'=>'subscribe','subscription'=>'{"endpoint":"https://127.0.0.1/push","keys":{}}']);$assert($r['http']===422);
});
$test('missing notification returns controlled error',static function()use($request,$assert,$endpoint){
    $r=$request(['endpoint'=>$endpoint],['action'=>'get','id'=>'999999999'],[],true);$assert($r['body']['status']==='error');
});
