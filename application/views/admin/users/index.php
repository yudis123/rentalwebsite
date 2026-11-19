<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="flex items-center justify-between gap-4">
	<p class="text-sm text-slate-500">Akun yang bisa masuk ke panel admin.</p>
	<a href="<?= site_url('admin/users/create') ?>" class="abtn-accent shrink-0">
		<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.500" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
		Tambah user
	</a>
</div>

<div class="card mt-5 overflow-hidden">
	<div class="overflow-x-auto">
		<table class="w-full min-w-[680px] text-left text-sm">
			<thead class="border-b border-slate-100 bg-slate-50/70 text-xs font-bold uppercase tracking-wider text-slate-500">
				<tr>
					<th scope="col" class="px-6 py-3.5">User</th>
					<th scope="col" class="px-4 py-3.5">Role</th>
					<th scope="col" class="px-4 py-3.5">Status</th>
					<th scope="col" class="px-4 py-3.5">Login terakhir</th>
					<th scope="col" class="px-6 py-3.5 text-right">Aksi</th>
				</tr>
			</thead>
			<tbody class="divide-y divide-slate-100">
				<?php foreach ($users as $user): ?>
					<?php $is_me = ((int) $user->id === (int) $me->id); ?>
					<tr class="transition hover:bg-slate-50/60">
						<td class="px-6 py-3.5">
							<div class="flex items-center gap-3">
								<span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-slate-900 text-sm font-bold text-white"><?= e(strtoupper(substr($user->name, 0, 1))) ?></span>
								<div class="min-w-0">
									<p class="truncate font-semibold">
										<?= e($user->name) ?>
										<?php if ($is_me): ?><span class="badge ml-1 bg-brand-100 text-brand-800">Anda</span><?php endif; ?>
									</p>
									<p class="text-xs text-slate-500">@<?= e($user->username) ?></p>
								</div>
							</div>
						</td>
						<td class="px-4 py-3.5 text-slate-600"><?= e(isset($roles[$user->role]) ? $roles[$user->role] : $user->role) ?></td>
						<td class="px-4 py-3.5">
							<?php if ($user->is_active): ?>
								<span class="badge bg-emerald-100 text-emerald-700">Aktif</span>
							<?php else: ?>
								<span class="badge bg-slate-200 text-slate-600">Nonaktif</span>
							<?php endif; ?>
						</td>
						<td class="whitespace-nowrap px-4 py-3.5 text-slate-600"><?= admin_date($user->last_login_at) ?></td>
						<td class="whitespace-nowrap px-6 py-3.5 text-right">
							<div class="flex items-center justify-end gap-1">
								<a href="<?= site_url('admin/users/edit/'.$user->id) ?>" class="rounded-lg px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-100">Ubah</a>
								<?php if ( ! $is_me): ?>
									<?= form_open('admin/users/toggle/'.$user->id) ?>
										<button type="submit" class="rounded-lg px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-100"><?= $user->is_active ? 'Nonaktifkan' : 'Aktifkan' ?></button>
									<?= form_close() ?>
									<button type="button" class="rounded-lg px-3 py-1.5 text-sm font-semibold text-rose-600 hover:bg-rose-50"
									        @click="$dispatch('confirm', <?= e(json_encode(array(
									        	'action' => site_url('admin/users/delete/'.$user->id),
									        	'title'  => 'Hapus user '.$user->name.'?',
									        	'text'   => 'User ini tidak akan bisa masuk lagi dan tidak bisa dikembalikan.',
									        ))) ?>)">Hapus</button>
								<?php endif; ?>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
