<?php
/**
 * Membuat akun superadmin, atau mereset password-nya bila username sudah ada.
 * Hanya bisa dijalankan dari terminal:
 *
 *   php database/create_admin.php <username> [password]
 *
 * Bila password dikosongkan, password acak dibuat dan ditampilkan sekali.
 */
if (PHP_SAPI !== 'cli')
{
	http_response_code(404);
	exit;
}

define('BASEPATH', __DIR__);
define('ENVIRONMENT', 'development');
require __DIR__.'/../application/config/database.php';

$username = isset($argv[1]) ? strtolower(trim($argv[1])) : '';
$password = isset($argv[2]) ? $argv[2] : '';

if ( ! preg_match('/^[a-z0-9_\-]{3,30}$/', $username))
{
	fwrite(STDERR, "Pakai: php database/create_admin.php <username> [password]\nUsername 3-30 karakter: huruf, angka, _ atau -\n");
	exit(1);
}

$generated = FALSE;

if ($password === '')
{
	$password  = rtrim(strtr(base64_encode(random_bytes(12)), '+/', 'xy'), '=');
	$generated = TRUE;
}
elseif (strlen($password) < 8)
{
	fwrite(STDERR, "Password minimal 8 karakter.\n");
	exit(1);
}

$cfg = $db['default'];
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($cfg['hostname'], $cfg['username'], $cfg['password'], $cfg['database']);
$mysqli->set_charset('utf8mb4');

$hash = password_hash($password, PASSWORD_BCRYPT);
$stmt = $mysqli->prepare(
	"INSERT INTO users (name, username, password_hash, role, is_active) VALUES ('Administrator', ?, ?, 'superadmin', 1)
	 ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash), is_active = 1"
);
$stmt->bind_param('ss', $username, $hash);
$stmt->execute();

echo "Akun superadmin siap.\n  Username: {$username}\n";
echo $generated ? "  Password: {$password}\n  (catat sekarang, tidak akan ditampilkan lagi)\n" : "  Password: sesuai yang Anda masukkan\n";
