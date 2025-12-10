<?php

define('DEVELOPER_MODE', 1);
define('DB_HOST', DEVELOPER_MODE ? 'localhost' : '');
define('DB_USER', 'root');
define('DB_PASSWORD', ''); // contraseña vacía
define('DB_DATABASE', 'crunchyeats');
