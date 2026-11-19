<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Nilai BAWAAN konten situs. Nilai yang berlaku disimpan di tabel `settings`
| dan diubah lewat halaman admin (menu Hero Banner dan Footer). Isi di sini
| hanya dipakai selama sebuah kunci belum pernah disimpan dari admin.
*/
$config['site_defaults'] = array(
	// Pengaturan umum (menu Pengaturan Umum): nama, logo, dan kontak
	'logo'           => '',  // kosong = ikon bawaan
	'logo_light'     => '',  // opsional, dipakai di latar gelap; kosong = pakai 'logo'
	'show_site_name' => '1', // tampilkan nama di samping logo
	'site_name'   => 'RentCar',
	'tagline'     => 'Sewa mobil mudah, aman, dan nyaman',
	'description' => 'Rental mobil harian dengan armada terawat, harga transparan, dan layanan 24 jam. Lepas kunci atau dengan sopir.',
	'whatsapp'    => '6281234567890', // format internasional tanpa +
	'phone'       => '0812-3456-7890',
	'email'       => 'halo@rentcar.test',
	'address'     => 'Jl. Contoh Raya No. 123, Kota Anda, Jawa Timur',
	'hours'       => 'Setiap hari, 24 jam',
	'instagram'   => '',
	'facebook'    => '',
	'tiktok'      => '',
	'cta_title'   => 'Sudah menemukan mobil yang cocok?',
	'cta_text'    => 'Hubungi kami sekarang, kami bantu cek ketersediaan dan jadwalnya.',

	// Hero banner
	'hero_image'         => '', // kosong = ilustrasi bawaan
	'hero_badge'         => 'Armada siap jalan hari ini',
	'hero_title_1'       => 'Sewa mobil mudah.',
	'hero_title_accent'  => 'Perjalanan',
	'hero_title_2'       => 'lebih nyaman.',
	'hero_description'   => 'Rental mobil harian dengan armada terawat, harga transparan, dan layanan 24 jam. Lepas kunci atau dengan sopir.',
	'hero_btn_primary'   => 'Lihat Armada',
	'hero_btn_secondary' => 'Chat WhatsApp',
	'hero_stat1_value'   => '1.000+',
	'hero_stat1_label'   => 'Pelanggan puas',
	'hero_stat2_value'   => '24 Jam',
	'hero_stat2_label'   => 'Layanan pelanggan',
);

// Daftar role. Saat ini hanya satu; tambahkan di sini bila perlu role lain.
$config['roles'] = array(
	'superadmin' => 'Superadmin',
);

// Batas percobaan login
$config['login_max_attempts'] = 5;
$config['login_lock_minutes'] = 15;
