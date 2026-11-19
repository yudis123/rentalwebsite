<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Pengaturan khusus server produksi. File ini dimuat SETELAH
| application/config/config.php dan menimpa nilai yang sama.
| Hanya berlaku bila ENVIRONMENT = 'production'.
*/

// Alamat tetap situs di hosting (wajib diakhiri garis miring).
$config['base_url'] = 'https://dafandralocaltech.co.id/balerental.id/';

// Cookie sesi hanya dikirim lewat HTTPS.
$config['cookie_secure'] = TRUE;

// Catat error ke application/logs/ (tidak ditampilkan ke pengunjung).
$config['log_threshold'] = 1;
