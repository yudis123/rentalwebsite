<?php
/**
 * Mengambil data situs (pengaturan + mobil) dari database dan menyiapkannya
 * untuk video: data/site.json (Remotion), hf-screen/data.js (HyperFrames),
 * serta salinan gambar mobil dan maskot.
 *
 *   php video/scripts/export-data.php
 */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

define('BASEPATH', __DIR__);
define('ENVIRONMENT', 'development');

$root  = dirname(__DIR__, 2);
$video = dirname(__DIR__);

require $root.'/application/config/database.php';
require $root.'/application/config/site.php';

$cfg = $db['default'];
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($cfg['hostname'], $cfg['username'], $cfg['password'], $cfg['database']);
$mysqli->set_charset('utf8mb4');

// Pengaturan: nilai database menimpa nilai bawaan, sama seperti di situs.
$site = $config['site_defaults'];
foreach ($mysqli->query('SELECT name, value FROM settings') as $row) {
	$site[$row['name']] = $row['value'];
}

$rupiah = function ($n) { return 'Rp '.number_format((int) $n, 0, ',', '.'); };
$copy = function ($from, $to) {
	is_dir(dirname($to)) || mkdir(dirname($to), 0755, true);
	if (!is_file($from) || !copy($from, $to)) { fwrite(STDERR, "Gagal menyalin: $from\n"); return false; }
	return true;
};

// Mobil, urutan sama dengan homepage: ready dulu, lalu urutan tampil.
$cars = [];
$result = $mysqli->query("SELECT * FROM cars ORDER BY FIELD(status,'ready','rented'), sort_order, price_per_day");
foreach ($result as $row) {
	$file = 'cars/'.$row['id'].'-'.basename($row['image']);
	$copy($root.'/'.$row['image'], $video.'/hf-screen/assets/'.$file);
	$cars[] = [
		'name'         => $row['name'],
		'category'     => $row['category'],
		'seats'        => (int) $row['seats'],
		'transmission' => $row['transmission'],
		'fuel'         => $row['fuel'],
		'price'        => (int) $row['price_per_day'],
		'priceText'    => $rupiah($row['price_per_day']),
		'status'       => $row['status'],
		'image'        => 'assets/'.$file,
	];
}

$ready      = array_values(array_filter($cars, function ($c) { return $c['status'] === 'ready'; }));
$categories = array_values(array_unique(array_column($cars, 'category')));
$minPrice   = $ready ? min(array_column($ready, 'price')) : 0;

// Mobil yang "disewa" di video: Toyota Avanza bila ready, kalau tidak mobil ready pertama.
$featured = null;
foreach ($ready as $c) { if (stripos($c['name'], 'avanza') !== false) { $featured = $c; break; } }
$featured = $featured ?: ($ready ? $ready[0] : null);
if (!$featured) { fwrite(STDERR, "Tidak ada mobil berstatus ready; video tidak bisa dibuat.\n"); exit(1); }

$digits = preg_replace('/\D+/', '', $site['whatsapp']);
$pretty = $site['phone'] !== '' ? $site['phone'] : '+'.$digits;

$data = [
	'site' => [
		'name'     => $site['site_name'],
		'tagline'  => $site['tagline'],
		'whatsapp' => $digits,
		'phone'    => $pretty,
		'hasLogo'  => false,
	],
	'cars'         => $cars,
	'categories'   => $categories,
	'carCount'     => count($cars),
	'minPriceText' => $rupiah($minPrice),
	'featured'     => $featured,
	'waMessage'    => 'Halo '.$site['site_name'].', saya ingin menyewa '.$featured['name'].' ('.$featured['priceText'].'/hari). Apakah masih tersedia?',
];

// Logo perusahaan (bila sudah diunggah lewat Pengaturan Umum)
if ($site['logo'] !== '' && is_file($root.'/'.$site['logo'])) {
	$ext = pathinfo($site['logo'], PATHINFO_EXTENSION);
	$copy($root.'/'.$site['logo'], $video.'/public/logo.'.$ext);
	$copy($root.'/'.$site['logo'], $video.'/hf-screen/assets/logo.'.$ext);
	$data['site']['hasLogo'] = true;
	$data['site']['logo']    = 'logo.'.$ext;
}

// Maskot dan latar rute untuk Remotion
foreach (['reno-wave.png', 'reno-point.png', 'reno-key.png', 'route.svg'] as $f) {
	$copy($root.'/assets/img/brand/'.$f, $video.'/public/brand/'.$f);
}

$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
is_dir($video.'/data') || mkdir($video.'/data', 0755, true);
file_put_contents($video.'/data/site.json', $json."\n");
file_put_contents($video.'/hf-screen/data.js', "// Dibuat otomatis oleh scripts/export-data.php. Jangan diubah manual.\nwindow.SITE_DATA = ".$json.";\n");

echo 'Data diekspor: '.count($cars).' mobil, '.count($categories).' kategori, mulai '.$data['minPriceText']."\n";
echo 'Mobil yang disewa di video: '.$featured['name'].' ('.$featured['priceText'].")\n";
echo 'Kontak di penutup: '.$data['site']['name'].' / '.$pretty."\n";
