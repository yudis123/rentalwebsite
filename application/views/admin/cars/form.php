<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$action = $car ? 'admin/cars/edit/'.$car->id : 'admin/cars/create';
$v = function ($field, $default = '') use ($car) {
	return $car ? $car->$field : $default;
};
?>
<a href="<?= site_url('admin/cars') ?>" class="inline-flex items-center gap-2 rounded text-sm font-semibold text-slate-500 hover:text-slate-900">
	<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
	Kembali ke daftar mobil
</a>

<?= form_open_multipart($action, array('class' => 'mt-5 grid gap-6 lg:grid-cols-3')) ?>

	<div class="space-y-6 lg:col-span-2">
		<section class="card p-6 sm:p-8">
			<h2 class="font-bold tracking-tight">Detail mobil</h2>
			<div class="mt-6 space-y-5">
				<?= admin_input('name', 'Nama mobil', $v('name'), array('required' => TRUE, 'placeholder' => 'Toyota Avanza')) ?>

				<div class="grid gap-5 sm:grid-cols-2">
					<?= admin_input('category', 'Kategori', $v('category'), array('required' => TRUE, 'placeholder' => 'MPV', 'attrs' => 'list="list-category"', 'hint' => 'Pilih yang sudah ada atau ketik kategori baru.')) ?>
					<?= admin_input('seats', 'Jumlah kursi', $v('seats', 5), array('required' => TRUE, 'type' => 'number', 'attrs' => 'min="1" max="60" inputmode="numeric"')) ?>
					<?= admin_select('transmission', 'Transmisi', array('Manual' => 'Manual', 'Matic' => 'Matic'), $v('transmission', 'Manual'), array('required' => TRUE)) ?>
					<?= admin_input('fuel', 'Bahan bakar', $v('fuel', 'Bensin'), array('required' => TRUE, 'attrs' => 'list="list-fuel"')) ?>
				</div>

				<datalist id="list-category">
					<?php foreach ($categories as $option): ?><option value="<?= e($option) ?>"></option><?php endforeach; ?>
				</datalist>
				<datalist id="list-fuel">
					<?php foreach (array_unique(array_merge($fuels, array('Bensin', 'Diesel', 'Hybrid', 'Listrik'))) as $option): ?><option value="<?= e($option) ?>"></option><?php endforeach; ?>
				</datalist>

				<?= admin_textarea('description', 'Deskripsi', $v('description'), array('rows' => 3, 'hint' => 'Opsional, tampil di kartu mobil. Maksimal 500 karakter.')) ?>
			</div>
		</section>

		<section class="card p-6 sm:p-8">
			<h2 class="font-bold tracking-tight">Harga dan status</h2>
			<div class="mt-6 grid gap-5 sm:grid-cols-3">
				<?= admin_input('price_per_day', 'Harga per hari (Rp)', $v('price_per_day'), array('required' => TRUE, 'type' => 'number', 'placeholder' => '350000', 'attrs' => 'min="1" step="1000" inputmode="numeric"', 'hint' => 'Angka saja, tanpa titik.')) ?>
				<?= admin_select('status', 'Status', array('ready' => 'Ready', 'rented' => 'Sedang disewa'), $v('status', 'ready'), array('required' => TRUE)) ?>
				<?= admin_input('sort_order', 'Urutan tampil', $v('sort_order', 0), array('type' => 'number', 'attrs' => 'inputmode="numeric"', 'hint' => 'Angka kecil tampil lebih dulu.')) ?>
			</div>
		</section>
	</div>

	<div class="space-y-6">
		<section class="card p-6">
			<?php $this->load->view('admin/partials/image_field', array(
				'label'         => 'Gambar mobil',
				'current_url'   => $car ? asset_url($car->image) : '',
				'error'         => $image_error,
				'hint'          => 'JPG, PNG, atau WebP, maksimal 2 MB. Disarankan foto lanskap 16:10; gambar lebar diperkecil otomatis.',
				'preview_class' => 'bg-slate-100',
			)); ?>
		</section>

		<div class="card sticky top-24 flex flex-col gap-3 p-6">
			<button type="submit" class="abtn-accent w-full"><?= $car ? 'Simpan perubahan' : 'Tambah mobil' ?></button>
			<a href="<?= site_url('admin/cars') ?>" class="abtn-outline w-full">Batal</a>
		</div>
	</div>

<?= form_close() ?>
