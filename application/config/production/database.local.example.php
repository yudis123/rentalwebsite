<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| CONTOH. Salin file ini menjadi database.local.php (di folder yang sama),
| lalu isi dengan data database dari cPanel > MySQL Databases.
| database.local.php tidak ikut ke git, jadi password tidak terunggah ke GitHub.
*/
$db['default']['username'] = 'dafandra_xxxx';   // user database (lengkap dengan awalan akun)
$db['default']['password'] = 'password_user_database';
$db['default']['database'] = 'dafandra_xxxx';   // nama database (lengkap dengan awalan akun)
