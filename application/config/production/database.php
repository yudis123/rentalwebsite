<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Koneksi database di hosting. Hanya dipakai bila ENVIRONMENT = 'production'.
|
| JANGAN tulis password di file ini (file ini ikut ke GitHub).
| Buat file database.local.php di folder yang sama, berisi:
|
|   <?php
|   $db['default']['username'] = 'user_database';
|   $db['default']['password'] = 'password_database';
|   $db['default']['database'] = 'nama_database';
|
| Contohnya ada di database.local.example.php. File database.local.php
| tidak ikut ke git (lihat .gitignore), jadi aman dan tidak bentrok saat pull.
*/
$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => '',
	'password' => '',
	'database' => '',
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

if (is_file(__DIR__.'/database.local.php'))
{
	include __DIR__.'/database.local.php';
}

if ($db['default']['username'] === '' OR $db['default']['database'] === '')
{
	if ( ! headers_sent())
	{
		header('HTTP/1.1 503 Service Unavailable', TRUE, 503);
		header('Content-Type: text/html; charset=UTF-8');
		header('Retry-After: 3600');
	}
	echo '<!doctype html><html lang="id"><head><meta charset="utf-8">'
		.'<meta name="viewport" content="width=device-width,initial-scale=1">'
		.'<meta name="robots" content="noindex"><title>Situs sedang disiapkan</title></head>'
		.'<body style="font-family:system-ui,sans-serif;max-width:34rem;margin:15vh auto;padding:0 1rem;color:#142536">'
		.'<h1 style="font-size:1.4rem">Situs sedang disiapkan</h1>'
		.'<p>Konfigurasi database belum diisi. Pengelola situs: buat file '
		.'<code>application/config/production/database.local.php</code> '
		.'(contoh: <code>database.local.example.php</code>).</p></body></html>';
	exit(3);
}
