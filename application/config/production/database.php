<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Koneksi database di hosting. Hanya dipakai bila ENVIRONMENT = 'production'.
| ISI TIGA BARIS BERTANDA di bawah sesuai database yang dibuat di cPanel.
*/
$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'ISI_USER_DATABASE',      // <-- ganti
	'password' => 'ISI_PASSWORD_DATABASE',  // <-- ganti
	'database' => 'ISI_NAMA_DATABASE',      // <-- ganti
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => FALSE,
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8mb4',
	'dbcollat' => 'utf8mb4_unicode_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => FALSE
);
