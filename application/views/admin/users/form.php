<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$action = $user ? 'admin/users/edit/'.$user->id : 'admin/users/create';
?>
<a href="<?= site_url('admin/users') ?>" class="inline-flex items-center gap-2 rounded text-sm font-semibold text-slate-500 hover:text-slate-900">
	<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
	Kembali ke daftar user
</a>

<?= form_open($action, array('class' => 'mt-5 max-w-2xl space-y-6', 'autocomplete' => 'off')) ?>

	<section class="card p-6 sm:p-8">
		<h2 class="font-bold tracking-tight">Data user</h2>
		<div class="mt-6 space-y-5">
			<?= admin_input('name', 'Nama', $user ? $user->name : '', array('required' => TRUE)) ?>

			<div class="grid gap-5 sm:grid-cols-2">
				<?= admin_input('username', 'Username', $user ? $user->username : '', array('required' => TRUE, 'attrs' => 'autocapitalize="none" spellcheck="false" autocomplete="off"', 'hint' => '3-30 karakter: huruf, angka, _ atau -')) ?>
				<?= admin_select('role', 'Role', $roles, $user ? $user->role : 'superadmin', array('required' => TRUE)) ?>
			</div>

			<?php $checked = $this->input->method() === 'post' ? (bool) $this->input->post('is_active') : ( ! $user OR $user->is_active); ?>
			<label class="flex items-start gap-3 rounded-xl border border-slate-200 p-4 <?= $is_self ? 'opacity-60' : 'cursor-pointer hover:bg-slate-50' ?>">
				<input type="checkbox" name="is_active" value="1" class="mt-0.5 h-4 w-4 rounded border-slate-300 accent-amber-500" <?= ($checked OR $is_self) ? 'checked' : '' ?> <?= $is_self ? 'disabled' : '' ?>>
				<span>
					<span class="block text-sm font-semibold">Akun aktif</span>
					<span class="block text-xs text-slate-500"><?= $is_self ? 'Anda tidak bisa menonaktifkan akun sendiri.' : 'User nonaktif tidak bisa masuk ke panel admin.' ?></span>
				</span>
			</label>
		</div>
	</section>

	<section class="card p-6 sm:p-8">
		<h2 class="font-bold tracking-tight"><?= $user ? 'Reset password' : 'Password' ?></h2>
		<?php if ($user): ?>
			<p class="mt-1 text-sm text-slate-500">Kosongkan bila tidak ingin mengganti password. Mengganti password mengeluarkan user ini dari semua perangkat.</p>
		<?php endif; ?>
		<div class="mt-6 grid gap-5 sm:grid-cols-2">
			<?= admin_input('password', $user ? 'Password baru' : 'Password', '', array('type' => 'password', 'required' => ! $user, 'attrs' => 'autocomplete="new-password" minlength="8"', 'hint' => 'Minimal 8 karakter.')) ?>
			<?= admin_input('password_confirm', 'Konfirmasi password', '', array('type' => 'password', 'required' => ! $user, 'attrs' => 'autocomplete="new-password"')) ?>
		</div>
	</section>

	<div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
		<a href="<?= site_url('admin/users') ?>" class="abtn-outline">Batal</a>
		<button type="submit" class="abtn-accent"><?= $user ? 'Simpan perubahan' : 'Tambah user' ?></button>
	</div>

<?= form_close() ?>
