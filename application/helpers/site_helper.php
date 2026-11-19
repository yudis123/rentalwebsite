<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('rupiah'))
{
	/** 350000 -> "Rp 350.000" */
	function rupiah($amount)
	{
		return 'Rp '.number_format((int) $amount, 0, ',', '.');
	}
}

if ( ! function_exists('wa_link'))
{
	/** Tautan WhatsApp dengan pesan yang sudah terisi. */
	function wa_link($number, $message = '')
	{
		$url = 'https://wa.me/'.preg_replace('/\D+/', '', $number);

		return $message === '' ? $url : $url.'?text='.rawurlencode($message);
	}
}

if ( ! function_exists('e'))
{
	/** Escape output HTML. */
	function e($value)
	{
		return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
	}
}

if ( ! function_exists('asset_url'))
{
	/** URL file di dalam proyek dari path relatif, mis. assets/uploads/cars/a.jpg. */
	function asset_url($relative)
	{
		$parts = array_map('rawurlencode', explode('/', ltrim((string) $relative, '/')));

		return base_url(implode('/', $parts));
	}
}

if ( ! function_exists('brand_shows_name'))
{
	/** Nama perusahaan selalu tampil bila belum ada logo. */
	function brand_shows_name($site)
	{
		return $site['logo'] === '' OR $site['show_site_name'] === '1';
	}
}

if ( ! function_exists('brand_mark'))
{
	/**
	 * HTML logo perusahaan, atau ikon bawaan bila logo belum diunggah.
	 *
	 * $mode: 'dark'     = selalu di latar gelap (pakai logo versi terang bila ada)
	 *        'light'    = selalu di latar terang
	 *        'adaptive' = navbar publik: gelap di atas hero, terang saat di-scroll
	 *                     (bergantung pada variabel Alpine `scrolled` dan `menu`)
	 * $size: 'md' atau 'lg'
	 */
	function brand_mark($site, $mode = 'dark', $size = 'md')
	{
		$sizes = array(
			'md' => array('h-9 w-9', 'h-5 w-5', 'h-9 max-w-[10rem]'),
			'lg' => array('h-11 w-11', 'h-6 w-6', 'h-11 max-w-[12rem]'),
		);
		list($box, $icon, $image) = isset($sizes[$size]) ? $sizes[$size] : $sizes['md'];

		if ($site['logo'] === '')
		{
			return '<span class="grid '.$box.' shrink-0 place-items-center rounded-xl bg-brand-500 text-slate-950">'
				.'<svg class="'.$icon.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'
				.'<path d="M5 13l1.5-4.5A2 2 0 0 1 8.4 7h7.2a2 2 0 0 1 1.9 1.5L19 13"/>'
				.'<path d="M3 13h18v4a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-1H6v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-4z"/>'
				.'<path d="M7 15.5h.01M17 15.5h.01"/>'
				.'</svg></span>';
		}

		// Bila nama tidak ditampilkan, logo-lah yang menyebutkan nama untuk pembaca layar.
		$alt   = brand_shows_name($site) ? '' : e($site['site_name']);
		$class = $image.' w-auto shrink-0 object-contain';
		$main  = asset_url($site['logo']);
		$light = $site['logo_light'] !== '' ? asset_url($site['logo_light']) : $main;

		if ($mode === 'light' OR $light === $main)
		{
			return '<img src="'.$main.'" alt="'.$alt.'" class="'.$class.'">';
		}

		if ($mode === 'dark')
		{
			return '<img src="'.$light.'" alt="'.$alt.'" class="'.$class.'">';
		}

		return '<img src="'.$light.'" alt="'.$alt.'" class="'.$class.'" x-show="!(scrolled || menu)">'
			.'<img src="'.$main.'" alt="'.$alt.'" class="'.$class.'" x-show="scrolled || menu" x-cloak>';
	}
}

if ( ! function_exists('external_url'))
{
	/** Pastikan tautan luar memakai http(s); kembalikan '' bila tidak layak. */
	function external_url($url)
	{
		$url = trim((string) $url);

		if ($url === '' OR $url === '#')
		{
			return '';
		}

		if ( ! preg_match('#^https?://#i', $url))
		{
			$url = 'https://'.ltrim($url, '/');
		}

		return filter_var($url, FILTER_VALIDATE_URL) ? $url : '';
	}
}
