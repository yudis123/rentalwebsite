<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$is_ready = ($car->status === 'ready');
$wa_car   = wa_link($site['whatsapp'], 'Halo '.$site['site_name'].', saya ingin menyewa '.$car->name.' ('.rupiah($car->price_per_day).'/hari). Apakah masih tersedia?');
?>
<div class="reveal" x-show="filter === 'all' || filter === <?= e(json_encode($car->category)) ?>">
<article class="fleet-card group flex h-full flex-col overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-card transition duration-300 <?= $is_ready ? 'hover:-translate-y-1.5 hover:shadow-card-hover' : '' ?>">

	<div class="relative aspect-[16/10] overflow-hidden bg-gradient-to-br from-slate-100 to-slate-50">
		<img src="<?= asset_url($car->image) ?>"
		     alt="<?= e($car->name) ?>" width="640" height="400" loading="lazy" decoding="async"
		     class="h-full w-full object-contain transition duration-500 <?= $is_ready ? 'group-hover:scale-105' : 'opacity-60 grayscale' ?>">

		<?php if ($is_ready): ?>
			<span class="absolute left-4 top-4 inline-flex items-center gap-1.5 rounded-full bg-white/90 px-3 py-1 text-xs font-bold text-emerald-700 shadow-sm backdrop-blur">
				<span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Tersedia
			</span>
		<?php else: ?>
			<span class="absolute left-4 top-4 inline-flex items-center gap-1.5 rounded-full bg-slate-900/80 px-3 py-1 text-xs font-bold text-white backdrop-blur">
				<span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Sedang disewa
			</span>
		<?php endif; ?>

		<span class="absolute right-4 top-4 rounded-full bg-slate-900/5 px-3 py-1 text-xs font-semibold text-slate-600 backdrop-blur">
			<?= e($car->category) ?>
		</span>
	</div>

	<div class="flex flex-1 flex-col p-6">
		<h3 class="text-lg font-bold tracking-tight text-slate-900"><?= e($car->name) ?></h3>
		<?php if ($car->description !== ''): ?>
			<p class="mt-2 line-clamp-2 text-sm leading-relaxed text-slate-600"><?= e($car->description) ?></p>
		<?php endif; ?>

		<ul class="mt-4 flex flex-wrap gap-2 text-sm font-medium text-slate-600">
			<li class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5">
				<svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="8" r="3.2"/><path d="M3 20c0-3.300 2.700-6 6-6s6 2.700 6 6"/><path d="M16 5.200a3.200 3.200 0 0 1 0 5.600M21 20c0-2.600-1.600-4.800-4-5.600"/></svg>
				<?= (int) $car->seats ?> kursi
			</li>
			<li class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5">
				<svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="6" cy="6" r="2"/><circle cx="12" cy="6" r="2"/><circle cx="18" cy="6" r="2"/><circle cx="6" cy="18" r="2"/><circle cx="12" cy="18" r="2"/><path d="M6 8v8M12 8v8M18 8v2a2 2 0 0 1-2 2H6"/></svg>
				<?= e($car->transmission) ?>
			</li>
			<li class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5">
				<svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 21V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v16M3 21h12M6 8h6"/><path d="M14 11h2a2 2 0 0 1 2 2v4a1.500 1.500 0 0 0 3 0V9l-3-3"/></svg>
				<?= e($car->fuel) ?>
			</li>
		</ul>

		<div class="car-booking mt-auto flex flex-wrap items-end justify-between gap-4 pt-6">
			<div>
				<p class="text-xs font-medium text-slate-500">Harga sewa</p>
				<p class="text-xl font-extrabold tracking-tight text-slate-900">
					<?= rupiah($car->price_per_day) ?><span class="text-sm font-medium text-slate-500">/hari</span>
				</p>
			</div>

			<?php if ($is_ready): ?>
				<a href="<?= e($wa_car) ?>" target="_blank" rel="noopener"
				   class="btn shrink-0 bg-slate-900 !px-5 !py-2.5 text-white hover:bg-brand-500 hover:text-slate-950"
				   aria-label="Sewa <?= e($car->name) ?> lewat WhatsApp">
					Sewa
					
				</a>
			<?php else: ?>
				<span class="btn shrink-0 cursor-not-allowed bg-slate-100 !px-5 !py-2.5 text-slate-400" aria-disabled="true">Tidak tersedia</span>
			<?php endif; ?>
		</div>
	</div>
</article>
</div>
