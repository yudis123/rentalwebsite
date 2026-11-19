<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$s = $settings;
?>
<div class="mb-6 flex items-start gap-3 rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-900">
	<svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/></svg>
	<p>
		Nama perusahaan, logo, nomor WhatsApp, email, jam layanan, dan alamat yang tampil di footer diatur di
		<a href="<?= site_url('admin/settings') ?>" class="rounded font-semibold underline underline-offset-2 hover:text-sky-700">Pengaturan Umum</a>.
	</p>
</div>

<?= form_open('admin/footer', array('class' => 'grid gap-6 lg:grid-cols-3')) ?>

	<div class="space-y-6 lg:col-span-2">
		<section class="card p-6 sm:p-8">
			<h2 class="font-bold tracking-tight">Tentang perusahaan</h2>
			<div class="mt-6 space-y-5">
				<?= admin_input('tagline', 'Tagline', $s['tagline'], array('hint' => 'Tampil di baris paling bawah footer dan judul halaman.')) ?>
				<?= admin_textarea('description', 'Deskripsi singkat', $s['description'], array('rows' => 3, 'hint' => 'Tampil di footer dan dipakai mesin pencari. Maksimal 300 karakter.')) ?>
			</div>
		</section>

		<section class="card p-6 sm:p-8">
			<h2 class="font-bold tracking-tight">Media sosial</h2>
			<p class="mt-1 text-sm text-slate-500">Isi dengan tautan lengkap. Yang dikosongkan tidak ditampilkan.</p>
			<div class="mt-6 space-y-5">
				<?= admin_input('instagram', 'Instagram', $s['instagram'], array('placeholder' => 'https://instagram.com/namaakun')) ?>
				<?= admin_input('facebook', 'Facebook', $s['facebook'], array('placeholder' => 'https://facebook.com/namahalaman')) ?>
				<?= admin_input('tiktok', 'TikTok', $s['tiktok'], array('placeholder' => 'https://tiktok.com/@namaakun')) ?>
			</div>
		</section>

		<section class="card p-6 sm:p-8">
			<h2 class="font-bold tracking-tight">Ajakan di atas footer</h2>
			<p class="mt-1 text-sm text-slate-500">Kotak dengan tombol "Chat WhatsApp". Kosongkan judulnya untuk menyembunyikan kotak ini.</p>
			<div class="mt-6 space-y-5">
				<?= admin_input('cta_title', 'Judul ajakan', $s['cta_title']) ?>
				<?= admin_input('cta_text', 'Teks ajakan', $s['cta_text']) ?>
			</div>
		</section>
	</div>

	<div>
		<div class="card sticky top-24 flex flex-col gap-3 p-6">
			<button type="submit" class="abtn-accent w-full">Simpan perubahan</button>
			<a href="<?= base_url() ?>#kontak" target="_blank" rel="noopener" class="abtn-outline w-full">Lihat di situs</a>
		</div>
	</div>

<?= form_close() ?>
