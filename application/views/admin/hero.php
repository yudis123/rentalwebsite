<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$s = $settings;
$has_custom_image = $s['hero_image'] !== '';
$current_image    = $has_custom_image ? asset_url($s['hero_image']) : base_url('assets/img/hero-car.svg');
?>
<?= form_open_multipart('admin/hero', array('class' => 'grid gap-6 lg:grid-cols-3')) ?>

	<div class="space-y-6 lg:col-span-2">
		<section class="card p-6 sm:p-8">
			<h2 class="font-bold tracking-tight">Tulisan utama</h2>
			<p class="mt-1 text-sm text-slate-500">Judul terdiri dari tiga bagian. Bagian kedua tampil dengan warna aksen.</p>

			<div class="mt-6 space-y-5">
				<?= admin_input('hero_badge', 'Label kecil di atas judul', $s['hero_badge'], array('hint' => 'Kosongkan untuk menyembunyikan.')) ?>

				<div class="grid gap-5 sm:grid-cols-3">
					<?= admin_input('hero_title_1', 'Judul bagian 1', $s['hero_title_1'], array('required' => TRUE)) ?>
					<?= admin_input('hero_title_accent', 'Bagian berwarna', $s['hero_title_accent']) ?>
					<?= admin_input('hero_title_2', 'Judul bagian 3', $s['hero_title_2']) ?>
				</div>

				<?= admin_textarea('hero_description', 'Deskripsi', $s['hero_description'], array('rows' => 3, 'hint' => 'Maksimal 300 karakter.')) ?>
			</div>
		</section>

		<section class="card p-6 sm:p-8">
			<h2 class="font-bold tracking-tight">Tombol</h2>
			<div class="mt-6 grid gap-5 sm:grid-cols-2">
				<?= admin_input('hero_btn_primary', 'Teks tombol utama', $s['hero_btn_primary'], array('required' => TRUE, 'hint' => 'Mengarah ke daftar mobil.')) ?>
				<?= admin_input('hero_btn_secondary', 'Teks tombol WhatsApp', $s['hero_btn_secondary'], array('required' => TRUE, 'hint' => 'Nomornya diatur di Pengaturan Umum.')) ?>
			</div>
		</section>

		<section class="card p-6 sm:p-8">
			<h2 class="font-bold tracking-tight">Angka statistik</h2>
			<p class="mt-1 text-sm text-slate-500">Jumlah armada dihitung otomatis. Dua angka berikut bisa Anda isi sendiri; kosongkan angkanya untuk menyembunyikan.</p>
			<div class="mt-6 grid gap-5 sm:grid-cols-2">
				<?= admin_input('hero_stat1_value', 'Angka 1', $s['hero_stat1_value'], array('placeholder' => '1.000+')) ?>
				<?= admin_input('hero_stat1_label', 'Keterangan 1', $s['hero_stat1_label'], array('placeholder' => 'Pelanggan puas')) ?>
				<?= admin_input('hero_stat2_value', 'Angka 2', $s['hero_stat2_value'], array('placeholder' => '24 Jam')) ?>
				<?= admin_input('hero_stat2_label', 'Keterangan 2', $s['hero_stat2_label'], array('placeholder' => 'Layanan pelanggan')) ?>
			</div>
		</section>
	</div>

	<div class="space-y-6">
		<section class="card p-6">
			<?php $this->load->view('admin/partials/image_field', array(
				'label'         => 'Gambar hero',
				'current_url'   => $current_image,
				'error'         => $image_error,
				'hint'          => 'JPG, PNG, atau WebP, maksimal 2 MB. Paling bagus foto mobil berlatar transparan (PNG) atau foto lanskap 16:10.',
				'preview_class' => 'bg-slate-900',
			)); ?>

			<?php if ($has_custom_image): ?>
				<button type="button" class="mt-4 w-full rounded-lg text-center text-sm font-semibold text-rose-600 hover:text-rose-700"
				        @click="$dispatch('confirm', <?= e(json_encode(array(
				        	'action' => site_url('admin/hero/reset_image'),
				        	'title'  => 'Kembalikan gambar bawaan?',
				        	'text'   => 'Gambar hero yang Anda unggah akan dihapus dan diganti ilustrasi bawaan.',
				        	'button' => 'Ya, kembalikan',
				        ))) ?>)">
					Kembalikan ke ilustrasi bawaan
				</button>
			<?php endif; ?>
		</section>

		<div class="card sticky top-24 flex flex-col gap-3 p-6">
			<button type="submit" class="abtn-accent w-full">Simpan perubahan</button>
			<a href="<?= base_url() ?>" target="_blank" rel="noopener" class="abtn-outline w-full">Lihat di situs</a>
		</div>
	</div>

<?= form_close() ?>
