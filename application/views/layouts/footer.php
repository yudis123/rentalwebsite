<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$wa_general = wa_link($site['whatsapp'], 'Halo '.$site['site_name'].', saya ingin menyewa mobil.');
$social_icons = array(
	'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.500 6.500h.01"/>',
	'facebook'  => '<path d="M14 8h3V4h-3a4 4 0 0 0-4 4v3H7v4h3v6h4v-6h3l1-4h-4V8z"/>',
	'tiktok'    => '<path d="M14 3v11.500a3.500 3.500 0 1 1-3.500-3.500"/><path d="M14 3c.3 2.800 2.200 4.700 5 5"/>',
);

$socials = array();
foreach (array_keys($social_icons) as $network)
{
	$url = external_url($site[$network]);

	if ($url !== '')
	{
		$socials[$network] = $url;
	}
}
?>
	</main>

	<footer id="kontak" class="scroll-mt-20 bg-slate-950 text-slate-300">
		<div class="container-page py-16 lg:py-20">

			<?php if ($site['cta_title'] !== ''): ?>
				<!-- Ajakan singkat -->
				<div class="reveal mb-16 flex flex-col items-start justify-between gap-6 rounded-3xl border border-white/10 bg-gradient-to-br from-white/10 to-white/5 p-8 sm:p-10 lg:flex-row lg:items-center">
					<div>
						<h2 class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl"><?= e($site['cta_title']) ?></h2>
						<?php if ($site['cta_text'] !== ''): ?>
							<p class="mt-2 text-sm text-slate-400 sm:text-base"><?= e($site['cta_text']) ?></p>
						<?php endif; ?>
					</div>
					<a href="<?= e($wa_general) ?>" target="_blank" rel="noopener" class="btn-primary shrink-0 !px-7 !py-3.5 !text-base">Chat WhatsApp</a>
				</div>
			<?php endif; ?>

			<div class="grid gap-12 sm:grid-cols-2 lg:grid-cols-12 lg:gap-8">
				<div class="lg:col-span-4">
					<a href="#beranda" class="inline-flex items-center gap-2.5 text-lg font-extrabold tracking-tight text-white">
						<?= brand_mark($site, 'dark') ?>
						<?php if (brand_shows_name($site)): ?><span><?= e($site['site_name']) ?></span><?php endif; ?>
					</a>

					<?php if ($site['description'] !== ''): ?>
						<p class="mt-5 max-w-sm text-sm leading-relaxed text-slate-400"><?= e($site['description']) ?></p>
					<?php endif; ?>

					<?php if ($socials): ?>
						<ul class="mt-6 flex gap-2">
							<?php foreach ($socials as $network => $url): ?>
								<li>
									<a href="<?= e($url) ?>" target="_blank" rel="noopener" aria-label="<?= e(ucfirst($network)) ?>"
									   class="grid h-10 w-10 place-items-center rounded-full border border-white/10 text-slate-300 transition hover:border-brand-500 hover:bg-brand-500 hover:text-slate-950">
										<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.800" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?= $social_icons[$network] ?></svg>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>

				<div class="lg:col-span-2 lg:col-start-6">
					<h3 class="text-sm font-bold uppercase tracking-widest text-white">Navigasi</h3>
					<ul class="mt-5 space-y-3 text-sm">
						<li><a href="#beranda" class="transition hover:text-brand-400">Beranda</a></li>
						<li><a href="#armada" class="transition hover:text-brand-400">Armada</a></li>
						<li><a href="#kontak" class="transition hover:text-brand-400">Kontak</a></li>
					</ul>
				</div>

				<div class="lg:col-span-3">
					<h3 class="text-sm font-bold uppercase tracking-widest text-white">Kontak</h3>
					<ul class="mt-5 space-y-3 text-sm">
						<li>
							<a href="<?= e($wa_general) ?>" target="_blank" rel="noopener" class="transition hover:text-brand-400">
								<?= e($site['phone'] !== '' ? $site['phone'] : '+'.$site['whatsapp']) ?> (WhatsApp)
							</a>
						</li>
						<?php if ($site['email'] !== ''): ?>
							<li><a href="mailto:<?= e($site['email']) ?>" class="transition hover:text-brand-400"><?= e($site['email']) ?></a></li>
						<?php endif; ?>
						<?php if ($site['address'] !== ''): ?>
							<li class="leading-relaxed text-slate-400"><?= nl2br(e($site['address'])) ?></li>
						<?php endif; ?>
					</ul>
				</div>

				<?php if ($site['hours'] !== ''): ?>
					<div class="lg:col-span-2">
						<h3 class="text-sm font-bold uppercase tracking-widest text-white">Jam layanan</h3>
						<p class="mt-5 text-sm leading-relaxed text-slate-400"><?= e($site['hours']) ?></p>
					</div>
				<?php endif; ?>
			</div>

			<div class="mt-16 flex flex-col gap-2 border-t border-white/10 pt-8 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
				<p>&copy; <?= date('Y') ?> <?= e($site['site_name']) ?>. Hak cipta dilindungi.</p>
				<p><?= e($site['tagline']) ?></p>
			</div>
		</div>
	</footer>

	<a class="mobile-booking" href="<?= e($wa_general) ?>" target="_blank" rel="noopener">Sewa via WhatsApp</a>
	<script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>
</html>
