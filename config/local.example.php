<?php
// Copy to config/local.php only when environment variables aren't available.
// local.php is ignored by Git. Never commit real credentials.
return [
    'DB_PASS' => '',
    'SMTP_PASS' => '',
];
