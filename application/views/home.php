<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->view('partials/hero');
?>

<section id="armada" class="scroll-mt-20 fleet-section py-16 lg:py-24" x-data="{ filter: 'all' }">
	<div class="container-page">

		<div class="reveal flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
			<div class="max-w-2xl">
				<p class="text-sm font-bold uppercase tracking-widest text-brand-600">Armada kami</p>
				<h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
					Mobil untuk setiap perjalanan
				</h2>
				<p class="mt-4 text-base leading-relaxed text-slate-600">
					Harga sudah per hari dan transparan. Klik <strong class="font-semibold text-slate-900">Sewa</strong> untuk langsung terhubung dengan kami lewat WhatsApp.
				</p>
			</div>

			<?php if (count($categories) > 1): ?>
				<div class="-mx-5 flex gap-2 overflow-x-auto px-5 pb-1 sm:mx-0 sm:flex-wrap sm:px-0" role="group" aria-label="Filter kategori mobil">
					<?php foreach (array_merge(array('all'), $categories) as $cat): ?>
						<button type="button"
						        @click="filter = <?= e(json_encode($cat)) ?>"
						        :aria-pressed="(filter === <?= e(json_encode($cat)) ?>).toString()"
						        :class="filter === <?= e(json_encode($cat)) ?> ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/15' : 'bg-white text-slate-600 ring-1 ring-inset ring-slate-200 hover:text-slate-900 hover:ring-slate-300'"
						        class="shrink-0 rounded-full px-5 py-2.5 text-sm font-semibold transition">
							<?= $cat === 'all' ? 'Semua' : e($cat) ?>
						</button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if (empty($cars)): ?>
			<div class="mt-12 rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center">
				<p class="text-lg font-bold text-slate-900">Belum ada mobil yang ditampilkan</p>
				<p class="mt-2 text-sm text-slate-600">Hubungi kami melalui WhatsApp untuk informasi armada.</p>
			</div>
		<?php else: ?>
			<div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8">
				<?php foreach ($cars as $car): ?>
					<?php $this->load->view('partials/car_card', array('car' => $car)); ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php $this->load->view('partials/journey'); ?>
