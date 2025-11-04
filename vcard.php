<?php
require_once __DIR__ . '/config/config.php';

header('Content-Type: text/vcard; charset=utf-8');
header('Content-Disposition: attachment; filename="key-soft-italia.vcf"');

$tel   = preg_replace('/\D+/', '', COMPANY_PHONE);
$wa    = preg_replace('/\D+/', '', WHATSAPP_NUMBER);
$addr  = COMPANY_ADDRESS;
$city  = COMPANY_CITY;
$zip   = '74013';
$maps  = GOOGLE_MAPS_LINK;

echo "BEGIN:VCARD\r\n";
echo "VERSION:3.0\r\n";
echo "N:;".COMPANY_NAME.";;;\r\n";
echo "FN:".COMPANY_NAME."\r\n";
echo "ORG:".COMPANY_NAME."\r\n";
echo "TEL;TYPE=WORK,VOICE:+".$tel."\r\n";
echo "TEL;TYPE=CELL,VOICE:+".$wa."\r\n";
echo "EMAIL;TYPE=INTERNET:".COMPANY_EMAIL."\r\n";
echo "ADR;TYPE=WORK:;;".$addr.";".$city.";;".$zip.";IT\r\n";
echo "URL:".rtrim(BASE_URL,'/')."/\r\n";
echo "NOTE:Indicazioni: ".$maps."\r\n";
echo "END:VCARD\r\n";