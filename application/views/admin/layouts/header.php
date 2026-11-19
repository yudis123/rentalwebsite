<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$menu = array(
	'dashboard' => array('admin', 'Dashboard', '<rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/>'),
	'hero'      => array('admin/hero', 'Hero Banner', '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9.5" r="1.5"/><path d="M21 15l-5-5L5 20"/>'),
	'cars'      => array('admin/cars', 'Mobil', '<path d="M5 13l1.5-4.5A2 2 0 0 1 8.4 7h7.2a2 2 0 0 1 1.9 1.5L19 13"/><path d="M3 13h18v4a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-1H6v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-4z"/><path d="M7 15.5h.01M17 15.5h.01"/>'),
	'footer'    => array('admin/footer', 'Footer', '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 15h18M7 18h4"/>'),
	'settings'  => array('admin/settings', 'Pengaturan Umum', '<path d="M4 6h10M18 6h2M4 12h4M12 12h8M4 18h12M20 18h0"/><circle cx="16" cy="6" r="2"/><circle cx="10" cy="12" r="2"/><circle cx="18" cy="18" r="2"/>'),
	'users'     => array('admin/users', 'User', '<circle cx="9" cy="8" r="3.5"/><path d="M2.5 20c0-3.600 2.900-6.500 6.500-6.500s6.500 2.900 6.500 6.500"/><path d="M16 4.600a3.500 3.500 0 0 1 0 6.800M21.500 20c0-2.800-1.700-5.100-4-6"/>'),
);
$csrf_name = $this->security->get_csrf_token_name();
$csrf_hash = $this->security->get_csrf_hash();
$initial   = strtoupper(substr($me->name, 0, 1));
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex, nofollow">
	<link rel="icon" type="image/svg+xml" href="<?= base_url('assets/img/favicon.svg') ?>">
	<title><?= e($title) ?> — Admin <?= e($settings['site_name']) ?></title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>?v=<?= @filemtime(FCPATH.'assets/css/app.css') ?>">
	<script defer src="<?= base_url('assets/js/alpine.min.js') ?>"></script>
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-900 antialiased" x-data="{ nav: false }" @keydown.escape.window="nav = false">

	<!-- Lapisan gelap saat sidebar terbuka di ponsel -->
	<div class="fixed inset-0 z-40 bg-slate-950/50 backdrop-blur-sm lg:hidden" x-show="nav" x-cloak x-transition.opacity @click="nav = false"></div>

	<aside class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col bg-slate-950 text-slate-300 transition-transform duration-300 lg:translate-x-0"
	       :class="nav && '!translate-x-0'">
		<a href="<?= site_url('admin') ?>" class="flex h-20 shrink-0 items-center gap-3 px-6 text-white">
			<?= brand_mark($settings, 'dark') ?>
			<span class="min-w-0">
				<?php if (brand_shows_name($settings)): ?>
					<span class="block truncate text-base font-extrabold leading-tight tracking-tight"><?= e($settings['site_name']) ?></span>
				<?php endif; ?>
				<span class="block text-xs font-medium text-slate-400">Panel Admin</span>
			</span>
		</a>

		<nav class="flex-1 space-y-1 overflow-y-auto px-4 py-4" aria-label="Menu admin">
			<p class="px-3 pb-2 text-[11px] font-bold uppercase tracking-widest text-slate-500">Menu</p>
			<?php foreach ($menu as $key => $item): ?>
				<?php $is_active = ($active === $key); ?>
				<a href="<?= site_url($item[0]) ?>" <?= $is_active ? 'aria-current="page"' : '' ?>
				   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition <?= $is_active ? 'bg-brand-500 text-slate-950 shadow-lg shadow-brand-500/20' : 'text-slate-300 hover:bg-white/5 hover:text-white' ?>">
					<svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.800" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?= $item[2] ?></svg>
					<?= $item[1] ?>
				</a>
			<?php endforeach; ?>
		</nav>

		<div class="shrink-0 border-t border-white/10 p-4">
			<a href="<?= base_url() ?>" target="_blank" rel="noopener" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-white/5 hover:text-white">
				<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.800" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 4h6v6M20 4l-9 9M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/></svg>
				Lihat situs
			</a>
		</div>
	</aside>

	<div class="lg:pl-72">
		<header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-slate-200/70 bg-white/80 px-5 backdrop-blur-xl sm:px-8 lg:h-20">
			<button type="button" class="-ml-2 grid h-10 w-10 place-items-center rounded-xl text-slate-600 hover:bg-slate-100 lg:hidden" @click="nav = true" aria-label="Buka menu">
				<svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
			</button>

			<h1 class="min-w-0 flex-1 truncate text-lg font-extrabold tracking-tight sm:text-xl"><?= e($title) ?></h1>

			<div class="relative" x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false">
				<button type="button" class="flex items-center gap-3 rounded-full py-1 pl-1 pr-1 transition hover:bg-slate-100 sm:pr-3" @click="open = !open" :aria-expanded="open.toString()" aria-haspopup="true">
					<span class="grid h-9 w-9 place-items-center rounded-full bg-slate-900 text-sm font-bold text-white"><?= e($initial) ?></span>
					<span class="hidden text-left sm:block">
						<span class="block text-sm font-semibold leading-tight"><?= e($me->name) ?></span>
						<span class="block text-xs text-slate-500"><?= e(isset($roles[$me->role]) ? $roles[$me->role] : $me->role) ?></span>
					</span>
					<svg class="hidden h-4 w-4 text-slate-400 sm:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
				</button>

				<div class="absolute right-0 mt-2 w-52 overflow-hidden rounded-2xl border border-slate-200 bg-white p-1.5 shadow-card-hover" x-show="open" x-cloak x-transition.origin.top.right>
					<a href="<?= site_url('admin/profile') ?>" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Profil saya</a>
					<form action="<?= site_url('admin/logout') ?>" method="post">
						<input type="hidden" name="<?= e($csrf_name) ?>" value="<?= e($csrf_hash) ?>">
						<button type="submit" class="block w-full rounded-xl px-3 py-2 text-left text-sm font-medium text-rose-600 hover:bg-rose-50">Keluar</button>
					</form>
				</div>
			</div>
		</header>

		<main class="mx-auto w-full max-w-6xl px-5 py-6 sm:px-8 sm:py-8">

			<?php foreach (array('success' => 'border-emerald-200 bg-emerald-50 text-emerald-800', 'error' => 'border-rose-200 bg-rose-50 text-rose-800') as $type => $classes): ?>
				<?php if ($message = $this->session->flashdata($type)): ?>
					<div class="mb-6 flex items-start gap-3 rounded-2xl border px-4 py-3 text-sm font-medium <?= $classes ?>" role="<?= $type === 'error' ? 'alert' : 'status' ?>"
					     x-data="{ show: true }" x-show="show" x-transition.opacity>
						<svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<?= $type === 'success' ? '<circle cx="12" cy="12" r="9"/><path d="M8.500 12.500l2.500 2.500 4.500-5"/>' : '<circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/>' ?>
						</svg>
						<p class="flex-1"><?= e($message) ?></p>
						<button type="button" class="opacity-60 hover:opacity-100" @click="show = false" aria-label="Tutup pesan">
							<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.500" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
						</button>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
