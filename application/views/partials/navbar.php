<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$nav_links = array(
	'#cara-sewa' => 'Cara Sewa',
	'#armada'  => 'Armada',
	'#faq' => 'FAQ',
);
$wa_general = wa_link($site['whatsapp'], 'Halo '.$site['site_name'].', saya ingin menyewa mobil.');
?>
<header class="site-nav fixed inset-x-0 top-0 z-50">
	<div class="container-page flex h-16 items-center justify-between lg:h-20">

		<a href="#beranda" class="flex items-center gap-2.5 rounded-full text-lg font-extrabold tracking-tight">
			<?= brand_mark($site, 'light') ?>
			<?php if (brand_shows_name($site)): ?><span><?= e($site['site_name']) ?></span><?php endif; ?>
		</a>

		<nav class="hidden items-center gap-1 md:flex" aria-label="Navigasi utama">
			<?php foreach ($nav_links as $href => $label): ?>
				<a href="<?= $href ?>"
				   class="rounded-full px-4 py-2 text-sm font-medium opacity-80 transition hover:opacity-100"
				   ><?= $label ?></a>
			<?php endforeach; ?>
		</nav>

		<div class="flex items-center gap-2">
			<a href="<?= e($wa_general) ?>" target="_blank" rel="noopener" class="btn-primary hidden !px-5 !py-2.5 sm:inline-flex">
				Chat WhatsApp
			</a>

			<button type="button"
			        class="grid h-10 w-10 place-items-center rounded-full transition md:hidden"
			        
			        @click="menu = !menu"
			        :aria-expanded="menu.toString()"
			        aria-controls="mobile-menu"
			        aria-label="Buka menu">
				<svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
					<path x-show="!menu" d="M4 7h16M4 12h16M4 17h16"/>
					<path x-show="menu" x-cloak d="M6 6l12 12M18 6L6 18"/>
				</svg>
			</button>
		</div>
	</div>

	<div id="mobile-menu" class="border-t border-slate-900/5 md:hidden" x-show="menu" x-cloak x-transition.opacity @click.outside="menu = false">
		<nav class="container-page flex flex-col gap-1 py-4" aria-label="Navigasi mobile">
			<?php foreach ($nav_links as $href => $label): ?>
				<a href="<?= $href ?>" @click="menu = false"
				   class="rounded-xl px-4 py-3 text-base font-semibold text-slate-800 hover:bg-slate-900/5"><?= $label ?></a>
			<?php endforeach; ?>
			<a href="<?= e($wa_general) ?>" target="_blank" rel="noopener" class="btn-primary mt-2 sm:hidden">Chat WhatsApp</a>
		</nav>
	</div>
</header>
