<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$s = $settings;
$show_name = $this->input->method() === 'post' ? (bool) $this->input->post('show_site_name') : $s['show_site_name'] === '1';

$remove_button = function ($key, $title, $text) {
	return '<button type="button" class="mt-4 w-full rounded-lg text-center text-sm font-semibold text-rose-600 hover:text-rose-700" '
		.'@click="$dispatch(\'confirm\', '.e(json_encode(array(
			'action' => site_url('admin/settings/remove_logo/'.$key),
			'title'  => $title,
			'text'   => $text,
			'button' => 'Ya, hapus',
		))).')">Hapus logo ini</button>';
};
?>
<?= form_open_multipart('admin/settings', array('class' => 'grid gap-6 lg:grid-cols-3')) ?>

	<div class="space-y-6 lg:col-span-2">
		<section class="card p-6 sm:p-8">
			<h2 class="font-bold tracking-tight">Identitas</h2>
			<p class="mt-1 text-sm text-slate-500">Tampil di menu atas situs, footer, judul halaman, dan panel admin ini.</p>
			<div class="mt-6 space-y-5">
				<?= admin_input('site_name', 'Nama perusahaan', $s['site_name'], array('required' => TRUE)) ?>

				<label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 hover:bg-slate-50">
					<input type="checkbox" name="show_site_name" value="1" class="mt-0.5 h-4 w-4 rounded border-slate-300 accent-amber-500" <?= $show_name ? 'checked' : '' ?>>
					<span>
						<span class="block text-sm font-semibold">Tampilkan nama perusahaan di samping logo</span>
						<span class="block text-xs text-slate-500">Matikan bila logo Anda sudah memuat tulisan nama. Selama logo belum diunggah, nama selalu tampil.</span>
					</span>
				</label>
			</div>
		</section>

		<section class="card p-6 sm:p-8">
			<h2 class="font-bold tracking-tight">Kontak</h2>
			<div class="mt-6 space-y-5">
				<div class="grid gap-5 sm:grid-cols-2">
					<?= admin_input('whatsapp', 'Nomor WhatsApp', $s['whatsapp'], array('required' => TRUE, 'type' => 'tel', 'hint' => 'Dipakai semua tombol Sewa dan Chat WhatsApp. Contoh: 081234567890.')) ?>
					<?= admin_input('phone', 'Telepon (tampilan)', $s['phone'], array('hint' => 'Tulisan nomor yang terlihat di footer.')) ?>
					<?= admin_input('email', 'Email perusahaan', $s['email'], array('type' => 'email')) ?>
					<?= admin_input('hours', 'Jam layanan', $s['hours'], array('placeholder' => 'Setiap hari, 24 jam')) ?>
				</div>
				<?= admin_textarea('address', 'Alamat perusahaan', $s['address'], array('rows' => 2)) ?>
			</div>
		</section>

		<section class="card p-6 sm:p-8">
			<h2 class="font-bold tracking-tight">Tampilan saat ini</h2>
			<p class="mt-1 text-sm text-slate-500">Logo dan nama yang sudah tersimpan, di latar gelap (hero, footer) dan latar terang (menu saat di-scroll).</p>
			<div class="mt-6 grid gap-4 sm:grid-cols-2">
				<?php foreach (array('dark' => 'bg-slate-950 text-white', 'light' => 'border border-slate-200 bg-white text-slate-900') as $mode => $classes): ?>
					<div class="flex h-24 items-center justify-center gap-2.5 rounded-xl px-4 text-lg font-extrabold tracking-tight <?= $classes ?>">
						<?= brand_mark($s, $mode) ?>
						<?php if (brand_shows_name($s)): ?><span class="truncate"><?= e($s['site_name']) ?></span><?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</section>
	</div>

	<div class="space-y-6">
		<section class="card p-6">
			<?php $this->load->view('admin/partials/image_field', array(
				'field_name'    => 'logo',
				'label'         => 'Logo perusahaan',
				'current_url'   => $s['logo'] !== '' ? asset_url($s['logo']) : '',
				'error'         => $image_errors['logo'],
				'hint'          => 'JPG, PNG, atau WebP, maksimal 2 MB. Paling bagus PNG berlatar transparan. Diperkecil otomatis ke tinggi 160px.',
				'preview_class' => 'bg-white',
				'aspect_class'  => 'aspect-[5/2] p-5',
				'empty_text'    => 'Belum ada logo (memakai ikon bawaan)',
			)); ?>
			<?php if ($s['logo'] !== ''): ?>
				<?= $remove_button('logo', 'Hapus logo perusahaan?', 'Situs akan kembali memakai ikon bawaan di samping nama perusahaan.') ?>
			<?php endif; ?>
		</section>

		<section class="card p-6">
			<?php $this->load->view('admin/partials/image_field', array(
				'field_name'    => 'logo_light',
				'label'         => 'Logo versi terang (opsional)',
				'current_url'   => $s['logo_light'] !== '' ? asset_url($s['logo_light']) : '',
				'error'         => $image_errors['logo_light'],
				'hint'          => 'Dipakai di latar gelap: bagian atas situs, footer, dan panel admin. Bila kosong, logo utama dipakai di semua tempat.',
				'preview_class' => 'bg-slate-950',
				'aspect_class'  => 'aspect-[5/2] p-5',
				'empty_text'    => 'Belum ada logo versi terang',
			)); ?>
			<?php if ($s['logo_light'] !== ''): ?>
				<?= $remove_button('logo_light', 'Hapus logo versi terang?', 'Logo utama akan dipakai juga di latar gelap.') ?>
			<?php endif; ?>
		</section>

		<div class="card sticky top-24 flex flex-col gap-3 p-6">
			<button type="submit" class="abtn-accent w-full">Simpan perubahan</button>
			<a href="<?= base_url() ?>" target="_blank" rel="noopener" class="abtn-outline w-full">Lihat di situs</a>
		</div>
	</div>

<?= form_close() ?>
