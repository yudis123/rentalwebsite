<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Kolom unggah gambar dengan pratinjau.
| Wajib : $label, $current_url (boleh ''), $error, $hint
| Opsional (selalu diberi nilai di sini, karena CI menyimpan variabel view
| antar-pemanggilan): $field_name, $preview_class, $aspect_class, $empty_text
*/
$_field   = isset($field_name) ? $field_name : 'image';
$_preview = isset($preview_class) ? $preview_class : 'bg-slate-100';
$_aspect  = isset($aspect_class) ? $aspect_class : 'aspect-[16/10]';
$_empty   = isset($empty_text) ? $empty_text : 'Belum ada gambar';
?>
<div x-data="{ preview: <?= e(json_encode((string) $current_url)) ?>, name: '', tooBig: false }">
	<span class="form-label"><?= e($label) ?></span>

	<div class="grid place-items-center overflow-hidden rounded-xl border border-slate-200 <?= e($_aspect) ?> <?= e($_preview) ?>">
		<img x-show="preview" :src="preview" alt="Pratinjau <?= e($label) ?>" class="h-full w-full object-contain">
		<p x-show="!preview" x-cloak class="px-3 text-center text-sm text-slate-400"><?= e($_empty) ?></p>
	</div>

	<label class="abtn-outline mt-3 w-full cursor-pointer focus-within:ring-4 focus-within:ring-brand-500/15">
		<svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 16V4M7 9l5-5 5 5M4 20h16"/></svg>
		<span class="truncate" x-text="name || 'Pilih gambar baru'">Pilih gambar baru</span>
		<input type="file" name="<?= e($_field) ?>" accept="image/jpeg,image/png,image/webp" class="sr-only"
		       @change="const f = $event.target.files[0]; if (!f) return; tooBig = f.size > 2097152; if (tooBig) { $event.target.value = ''; name = ''; return; } preview = URL.createObjectURL(f); name = f.name">
	</label>

	<p class="form-error" x-show="tooBig" x-cloak>Ukuran gambar maksimal 2 MB.</p>
	<?php if ( ! empty($error)): ?>
		<p class="form-error"><?= e($error) ?></p>
	<?php endif; ?>
	<p class="form-hint"><?= e($hint) ?></p>
</div>
