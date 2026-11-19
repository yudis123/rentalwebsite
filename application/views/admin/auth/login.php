<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex, nofollow">
	<link rel="icon" type="image/svg+xml" href="<?= base_url('assets/img/favicon.svg') ?>">
	<title>Masuk — Admin <?= e($settings['site_name']) ?></title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>?v=<?= @filemtime(FCPATH.'assets/css/app.css') ?>">
	<script defer src="<?= base_url('assets/js/alpine.min.js') ?>"></script>
</head>
<body class="relative isolate grid min-h-screen place-items-center overflow-hidden bg-slate-950 px-5 py-10 font-sans text-slate-900 antialiased">

	<div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
		<div class="absolute -right-40 -top-40 h-[34rem] w-[34rem] rounded-full bg-brand-500/25 blur-[120px]"></div>
		<div class="absolute -bottom-52 -left-40 h-[30rem] w-[30rem] rounded-full bg-sky-500/10 blur-[120px]"></div>
		<div class="absolute inset-0 bg-[linear-gradient(to_right,rgb(255_255_255/0.04)_1px,transparent_1px),linear-gradient(to_bottom,rgb(255_255_255/0.04)_1px,transparent_1px)] bg-[size:56px_56px] [mask-image:radial-gradient(ellipse_at_center,black_30%,transparent_75%)]"></div>
	</div>

	<main class="w-full max-w-md">
		<div class="mb-8 flex items-center justify-center gap-3 text-white">
			<?= brand_mark($settings, 'dark', 'lg') ?>
			<?php if (brand_shows_name($settings)): ?>
				<span class="text-xl font-extrabold tracking-tight"><?= e($settings['site_name']) ?></span>
			<?php endif; ?>
		</div>

		<div class="rounded-3xl bg-white p-7 shadow-2xl shadow-black/40 sm:p-9">
			<h1 class="text-2xl font-extrabold tracking-tight">Masuk ke panel admin</h1>
			<p class="mt-1.5 text-sm text-slate-500">Kelola armada dan konten situs Anda.</p>

			<?php if ($error !== ''): ?>
				<div class="mt-6 flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800" role="alert">
					<svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>
					<p><?= e($error) ?></p>
				</div>
			<?php endif; ?>

			<?= form_open('admin/login', array('class' => 'mt-6 space-y-5')) ?>
				<div>
					<label for="username" class="form-label">Username</label>
					<input type="text" id="username" name="username" value="<?= e($username) ?>" class="form-input" autocomplete="username" autocapitalize="none" spellcheck="false" required <?= $username === '' ? 'autofocus' : '' ?>>
				</div>

				<div x-data="{ show: false }">
					<label for="password" class="form-label">Password</label>
					<div class="relative">
						<input :type="show ? 'text' : 'password'" type="password" id="password" name="password" class="form-input pr-12" autocomplete="current-password" required <?= $username !== '' ? 'autofocus' : '' ?>>
						<button type="button" class="absolute inset-y-0 right-0 grid w-12 place-items-center text-slate-400 hover:text-slate-700" @click="show = !show" :aria-label="show ? 'Sembunyikan password' : 'Tampilkan password'" aria-label="Tampilkan password">
							<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.800" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="M2 12s3.600-7 10-7 10 7 10 7-3.600 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>
								<path x-show="show" x-cloak d="M4 4l16 16"/>
							</svg>
						</button>
					</div>
				</div>

				<button type="submit" class="abtn-accent w-full !py-3 !text-base">Masuk</button>
			<?= form_close() ?>
		</div>

		<p class="mt-6 text-center text-sm text-slate-400">
			<a href="<?= base_url() ?>" class="rounded font-medium transition hover:text-white">&larr; Kembali ke situs</a>
		</p>
	</main>
</body>
</html>
