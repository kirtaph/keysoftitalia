<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/DatabaseSettings.php';
use KeySoftItalia\DatabaseSettings;
$cases = [
    'existing defaults remain compatible'=>static fn()=>DatabaseSettings::resolve([],fn($key)=>false)['DB_NAME']==='ks_site_db',
    'private config supports all hosting settings'=>static function(){
        $local=['DB_HOST'=>'mysql.example.test','DB_PORT'=>3307,'DB_NAME'=>'hosting_database','DB_USER'=>'hosting_user','DB_PASS'=>'test-only','DB_CHARSET'=>'utf8mb4'];
        return DatabaseSettings::resolve($local,fn($key)=>false)===$local;
    },
    'environment takes precedence'=>static fn()=>DatabaseSettings::resolve(['DB_USER'=>'local'],fn($key)=>$key==='DB_USER'?'environment':false)['DB_USER']==='environment',
    'explicit empty password remains valid'=>static fn()=>DatabaseSettings::resolve(['DB_PASS'=>'test-only'],fn($key)=>$key==='DB_PASS'?'':false)['DB_PASS']==='',
    'empty host environment falls back to private config'=>static fn()=>DatabaseSettings::resolve(['DB_HOST'=>'private-host'],fn($key)=>'')['DB_HOST']==='private-host',
    'invalid private values rejected'=>static function(){try{DatabaseSettings::resolve(['DB_HOST'=>[]],fn($key)=>false);return false;}catch(InvalidArgumentException){return true;}},
];
foreach($cases as $name=>$case){if(!$case())throw new RuntimeException($name);echo "PASS $name\n";}
echo count($cases)." passed\n";
