<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?= form_open('admin/profile', array('class' => 'max-w-2xl space-y-6')) ?>

	<section class="card p-6 sm:p-8">
		<div class="flex items-center gap-4">
			<span class="grid h-14 w-14 shrink-0 place-items-center rounded-full bg-slate-900 text-xl font-bold text-white"><?= e(strtoupper(substr($me->name, 0, 1))) ?></span>
			<div>
				<p class="font-bold tracking-tight">@<?= e($me->username) ?></p>
				<p class="text-sm text-slate-500"><?= e(isset($roles[$me->role]) ? $roles[$me->role] : $me->role) ?> · login terakhir <?= admin_date($me->last_login_at) ?></p>
			</div>
		</div>
		<div class="mt-6">
			<?= admin_input('name', 'Nama', $me->name, array('required' => TRUE)) ?>
		</div>
	</section>

	<section class="card p-6 sm:p-8">
		<h2 class="font-bold tracking-tight">Ganti password</h2>
		<p class="mt-1 text-sm text-slate-500">Kosongkan bila tidak ingin mengganti. Perangkat lain yang sedang masuk dengan akun ini akan keluar otomatis.</p>
		<div class="mt-6 space-y-5">
			<?= admin_input('current_password', 'Password saat ini', '', array('type' => 'password', 'attrs' => 'autocomplete="current-password"')) ?>
			<div class="grid gap-5 sm:grid-cols-2">
				<?= admin_input('password', 'Password baru', '', array('type' => 'password', 'attrs' => 'autocomplete="new-password" minlength="8"', 'hint' => 'Minimal 8 karakter.')) ?>
				<?= admin_input('password_confirm', 'Konfirmasi password', '', array('type' => 'password', 'attrs' => 'autocomplete="new-password"')) ?>
			</div>
		</div>
	</section>

	<div class="flex justify-end">
		<button type="submit" class="abtn-accent">Simpan perubahan</button>
	</div>

<?= form_close() ?>
