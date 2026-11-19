<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
		</main>
	</div>

	<!--
		Dialog konfirmasi bersama. Dipanggil dari tombol mana pun dengan:
		$dispatch('confirm', { action, title, text, button })
	-->
	<div class="fixed inset-0 z-[60] grid place-items-center p-4" role="dialog" aria-modal="true" aria-labelledby="confirm-title"
	     x-data="{ open: false, action: '', title: '', text: '', button: 'Hapus' }"
	     x-show="open" x-cloak
	     @confirm.window="action = $event.detail.action; title = $event.detail.title; text = $event.detail.text || ''; button = $event.detail.button || 'Hapus'; open = true; $nextTick(() => $refs.cancel.focus())"
	     @keydown.escape.window="open = false">
		<div class="absolute inset-0 bg-slate-950/50 backdrop-blur-sm" x-show="open" x-transition.opacity @click="open = false"></div>

		<div class="relative w-full max-w-md rounded-3xl bg-white p-6 shadow-card-hover sm:p-8" x-show="open" x-transition.scale.95>
			<span class="grid h-12 w-12 place-items-center rounded-2xl bg-rose-100 text-rose-600">
				<svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 9v4M12 17h.01"/><path d="M10.300 3.900L2.400 17.500A2 2 0 0 0 4.100 20.500h15.800a2 2 0 0 0 1.700-3L13.700 3.900a2 2 0 0 0-3.400 0z"/></svg>
			</span>
			<h2 id="confirm-title" class="mt-5 text-lg font-extrabold tracking-tight" x-text="title"></h2>
			<p class="mt-2 text-sm leading-relaxed text-slate-600" x-text="text"></p>

			<form :action="action" method="post" class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
				<input type="hidden" name="<?= e($this->security->get_csrf_token_name()) ?>" value="<?= e($this->security->get_csrf_hash()) ?>">
				<button type="button" class="abtn-outline" x-ref="cancel" @click="open = false">Batal</button>
				<button type="submit" class="abtn-danger" x-text="button"></button>
			</form>
		</div>
	</div>
</body>
</html>
