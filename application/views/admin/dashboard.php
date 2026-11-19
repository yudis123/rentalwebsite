<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$stats = array(
	array('Total mobil', $car_total, 'bg-slate-900 text-white', 'admin/cars'),
	array('Ready', $car_ready, 'bg-emerald-100 text-emerald-700', 'admin/cars?status=ready'),
	array('Sedang disewa', $car_rented, 'bg-amber-100 text-amber-700', 'admin/cars?status=rented'),
	array('User admin', $user_total, 'bg-sky-100 text-sky-700', 'admin/users'),
);
$shortcuts = array(
	array('admin/cars/create', 'Tambah mobil', 'Masukkan armada baru beserta foto dan harganya.'),
	array('admin/hero', 'Ubah hero banner', 'Ganti gambar dan tulisan di bagian atas situs.'),
	array('admin/settings', 'Pengaturan umum', 'Ganti logo, nama, nomor WhatsApp, dan kontak perusahaan.'),
);
?>
<p class="text-sm text-slate-600">Selamat datang, <strong class="font-semibold text-slate-900"><?= e($me->name) ?></strong>. Ini ringkasan situs Anda saat ini.</p>

<div class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
	<?php foreach ($stats as $stat): ?>
		<a href="<?= site_url($stat[3]) ?>" class="card group p-5 transition hover:-translate-y-0.5 hover:shadow-card-hover">
			<span class="inline-grid h-10 min-w-[2.5rem] place-items-center rounded-xl px-2 text-sm font-bold <?= $stat[2] ?>"><?= (int) $stat[1] ?></span>
			<p class="mt-4 text-3xl font-extrabold tracking-tight"><?= (int) $stat[1] ?></p>
			<p class="mt-1 text-sm font-medium text-slate-500"><?= $stat[0] ?></p>
		</a>
	<?php endforeach; ?>
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-3">
	<section class="card lg:col-span-2">
		<div class="flex items-center justify-between gap-4 border-b border-slate-100 px-6 py-4">
			<h2 class="font-bold tracking-tight">Mobil terakhir diubah</h2>
			<a href="<?= site_url('admin/cars') ?>" class="rounded text-sm font-semibold text-brand-600 hover:text-brand-700">Lihat semua</a>
		</div>

		<?php if (empty($recent_cars)): ?>
			<p class="px-6 py-10 text-center text-sm text-slate-500">Belum ada mobil.</p>
		<?php else: ?>
			<ul class="divide-y divide-slate-100">
				<?php foreach ($recent_cars as $car): ?>
					<li>
						<a href="<?= site_url('admin/cars/edit/'.$car->id) ?>" class="flex items-center gap-4 px-6 py-3.5 transition hover:bg-slate-50">
							<img src="<?= asset_url($car->image) ?>" alt="" class="h-11 w-16 shrink-0 rounded-lg bg-slate-100 object-cover">
							<span class="min-w-0 flex-1">
								<span class="block truncate text-sm font-semibold"><?= e($car->name) ?></span>
								<span class="block text-xs text-slate-500"><?= rupiah($car->price_per_day) ?>/hari · diubah <?= admin_date($car->updated_at) ?></span>
							</span>
							<?php if ($car->status === 'ready'): ?>
								<span class="badge bg-emerald-100 text-emerald-700">Ready</span>
							<?php else: ?>
								<span class="badge bg-slate-200 text-slate-600">Disewa</span>
							<?php endif; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</section>

	<section class="space-y-3">
		<h2 class="px-1 font-bold tracking-tight">Pintasan</h2>
		<?php foreach ($shortcuts as $link): ?>
			<a href="<?= site_url($link[0]) ?>" class="card group flex items-center gap-4 p-4 transition hover:-translate-y-0.5 hover:shadow-card-hover">
				<span class="min-w-0 flex-1">
					<span class="block text-sm font-bold"><?= $link[1] ?></span>
					<span class="mt-0.5 block text-xs leading-relaxed text-slate-500"><?= $link[2] ?></span>
				</span>
				<svg class="h-5 w-5 shrink-0 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-brand-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
			</a>
		<?php endforeach; ?>
	</section>
</div>
