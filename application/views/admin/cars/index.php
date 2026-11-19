<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$filtering = ($keyword !== '' OR $status !== '');
?>
<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
	<form action="<?= site_url('admin/cars') ?>" method="get" class="flex flex-1 flex-col gap-3 sm:max-w-xl sm:flex-row" role="search">
		<div class="relative flex-1">
			<svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.500-3.500"/></svg>
			<input type="search" name="q" value="<?= e($keyword) ?>" placeholder="Cari nama atau kategori" class="form-input pl-10" aria-label="Cari mobil">
		</div>
		<select name="status" class="form-input sm:w-44" aria-label="Filter status" onchange="this.form.submit()">
			<option value="">Semua status</option>
			<option value="ready" <?= $status === 'ready' ? 'selected' : '' ?>>Ready</option>
			<option value="rented" <?= $status === 'rented' ? 'selected' : '' ?>>Sedang disewa</option>
		</select>
		<button type="submit" class="abtn-outline">Cari</button>
	</form>

	<a href="<?= site_url('admin/cars/create') ?>" class="abtn-accent shrink-0">
		<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.500" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
		Tambah mobil
	</a>
</div>

<p class="mt-5 text-sm text-slate-500">
	Menampilkan <strong class="font-semibold text-slate-900"><?= count($cars) ?></strong> dari <?= (int) $total ?> mobil.
	<?php if ($filtering): ?>
		<a href="<?= site_url('admin/cars') ?>" class="rounded font-semibold text-brand-600 hover:text-brand-700">Hapus filter</a>
	<?php endif; ?>
</p>

<div class="card mt-3 overflow-hidden">
	<?php if (empty($cars)): ?>
		<div class="px-6 py-16 text-center">
			<p class="font-bold"><?= $filtering ? 'Tidak ada mobil yang cocok' : 'Belum ada mobil' ?></p>
			<p class="mt-1 text-sm text-slate-500"><?= $filtering ? 'Coba kata kunci atau filter lain.' : 'Tambahkan mobil pertama Anda.' ?></p>
		</div>
	<?php else: ?>
		<div class="overflow-x-auto">
			<table class="w-full min-w-[720px] text-left text-sm">
				<thead class="border-b border-slate-100 bg-slate-50/70 text-xs font-bold uppercase tracking-wider text-slate-500">
					<tr>
						<th scope="col" class="px-6 py-3.5">Mobil</th>
						<th scope="col" class="px-4 py-3.5">Spesifikasi</th>
						<th scope="col" class="px-4 py-3.5">Harga / hari</th>
						<th scope="col" class="px-4 py-3.5">Status</th>
						<th scope="col" class="px-6 py-3.5 text-right">Aksi</th>
					</tr>
				</thead>
				<tbody class="divide-y divide-slate-100">
					<?php foreach ($cars as $car): ?>
						<tr class="transition hover:bg-slate-50/60">
							<td class="px-6 py-3.5">
								<div class="flex items-center gap-4">
									<img src="<?= asset_url($car->image) ?>" alt="" loading="lazy" class="h-12 w-[4.5rem] shrink-0 rounded-lg bg-slate-100 object-cover">
									<div class="min-w-0">
										<a href="<?= site_url('admin/cars/edit/'.$car->id) ?>" class="block truncate rounded font-semibold text-slate-900 hover:text-brand-600"><?= e($car->name) ?></a>
										<span class="text-xs text-slate-500"><?= e($car->category) ?></span>
									</div>
								</div>
							</td>
							<td class="whitespace-nowrap px-4 py-3.5 text-slate-600"><?= (int) $car->seats ?> kursi · <?= e($car->transmission) ?> · <?= e($car->fuel) ?></td>
							<td class="whitespace-nowrap px-4 py-3.5 font-semibold"><?= rupiah($car->price_per_day) ?></td>
							<td class="px-4 py-3.5">
								<?= form_open('admin/cars/toggle/'.$car->id) ?>
									<button type="submit" title="Klik untuk mengubah status"
									        class="badge relative transition <?= $car->status === 'ready' ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-200 text-slate-600 hover:bg-slate-300' ?>">
										<span class="h-1.5 w-1.5 rounded-full <?= $car->status === 'ready' ? 'bg-emerald-500' : 'bg-slate-400' ?>"></span>
										<?= $car->status === 'ready' ? 'Ready' : 'Disewa' ?>
										<svg class="h-3 w-3 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.500" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 8h14l-3-3M20 16H6l3 3"/></svg>
										<span class="sr-only">, ubah status <?= e($car->name) ?></span>
									</button>
								<?= form_close() ?>
							</td>
							<td class="whitespace-nowrap px-6 py-3.5 text-right">
								<a href="<?= site_url('admin/cars/edit/'.$car->id) ?>" class="rounded-lg px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-100">Ubah</a>
								<button type="button" class="rounded-lg px-3 py-1.5 text-sm font-semibold text-rose-600 hover:bg-rose-50"
								        @click="$dispatch('confirm', <?= e(json_encode(array(
								        	'action' => site_url('admin/cars/delete/'.$car->id),
								        	'title'  => 'Hapus '.$car->name.'?',
								        	'text'   => 'Mobil ini akan hilang dari situs dan tidak bisa dikembalikan.',
								        ))) ?>)">Hapus</button>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>
