<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= e($site['site_name']) ?><?= $site['tagline'] !== '' ? ' — '.e($site['tagline']) : '' ?></title>
	<meta name="description" content="<?= e($site['description']) ?>">
	<meta name="theme-color" content="#142536">
	<link rel="icon" type="image/svg+xml" href="<?= base_url('assets/img/favicon.svg') ?>">

	<script>document.documentElement.classList.add('js');</script>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>?v=<?= @filemtime(FCPATH.'assets/css/app.css') ?>">
	<script defer src="<?= base_url('assets/js/alpine.min.js') ?>"></script>
</head>
<body class="rental-site bg-white font-sans text-slate-900 antialiased"
      x-data="{ scrolled: window.scrollY > 24, menu: false }"
      @scroll.window.passive="scrolled = window.scrollY > 24"
      @keydown.escape.window="menu = false">

	<a href="#armada" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-full focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-semibold">
		Langsung ke daftar mobil
	</a>

	<?php $this->load->view('partials/navbar'); ?>

	<main>
