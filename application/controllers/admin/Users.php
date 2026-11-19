<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Admin_Controller {

	// Manajemen user hanya untuk superadmin, apa pun role yang ditambahkan nanti.
	protected $allowed_roles = array('superadmin');

	private $editing_id = 0;

	public function index()
	{
		$this->render('users/index', array(
			'title' => 'User',
			'users' => $this->user_model->all(),
		));
	}

	public function create()
	{
		$this->_form(NULL);
	}

	public function edit($id = 0)
	{
		$user = $this->user_model->find($id);
		$user OR show_404();

		$this->_form($user);
	}

	/** Aktifkan / nonaktifkan user. */
	public function toggle($id = 0)
	{
		$this->require_post();
		$user = $this->user_model->find($id);
		$user OR show_404();

		if ($user->is_active && ($reason = $this->_cannot_remove($user)))
		{
			$this->flash('error', $reason);
		}
		else
		{
			$this->user_model->update($user->id, array('is_active' => $user->is_active ? 0 : 1));
			$this->flash('success', $user->name.' berhasil '.($user->is_active ? 'dinonaktifkan' : 'diaktifkan').'.');
		}

		redirect('admin/users');
	}

	public function delete($id = 0)
	{
		$this->require_post();
		$user = $this->user_model->find($id);
		$user OR show_404();

		if ($reason = $this->_cannot_remove($user))
		{
			$this->flash('error', $reason);
		}
		else
		{
			$this->user_model->delete($user->id);
			$this->flash('success', $user->name.' berhasil dihapus.');
		}

		redirect('admin/users');
	}

	/** Alasan user tidak boleh dihapus/dinonaktifkan, atau '' bila boleh. */
	private function _cannot_remove($user)
	{
		if ((int) $user->id === (int) $this->user->id)
		{
			return 'Anda tidak bisa menghapus atau menonaktifkan akun Anda sendiri.';
		}

		if ($user->role === 'superadmin' && $user->is_active && $this->user_model->count_other_active_superadmins($user->id) === 0)
		{
			return 'Harus ada minimal satu superadmin aktif.';
		}

		return '';
	}

	private function _form($user)
	{
		$this->editing_id = $user ? (int) $user->id : 0;
		$is_self = $user && (int) $user->id === (int) $this->user->id;
		$roles   = $this->config->item('roles');

		if ($this->input->method() === 'post')
		{
			$password_rules = $user ? 'min_length[8]|max_length[72]' : 'required|min_length[8]|max_length[72]';

			$this->form_validation
				->set_rules('name', 'Nama', 'trim|required|max_length[100]')
				->set_rules('username', 'Username', 'trim|strtolower|required|min_length[3]|max_length[30]|alpha_dash|callback__unique_username')
				->set_rules('role', 'Role', 'required|in_list['.implode(',', array_keys($roles)).']')
				->set_rules('password', 'Password', $password_rules)
				->set_rules('password_confirm', 'Konfirmasi password', 'matches[password]');

			if ($this->form_validation->run())
			{
				$data = array(
					'name'      => $this->input->post('name'),
					'username'  => $this->input->post('username'),
					'role'      => $this->input->post('role'),
					'is_active' => $this->input->post('is_active') ? 1 : 0,
				);

				// Akun sendiri tidak bisa dinonaktifkan atau diturunkan dari form ini.
				if ($is_self)
				{
					$data['is_active'] = 1;
					$data['role']      = $user->role;
				}

				if ((string) $this->input->post('password') !== '')
				{
					$data['password_hash'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
				}

				if ($user && ! $is_self && ! $data['is_active'] && ($reason = $this->_cannot_remove($user)))
				{
					$this->flash('error', $reason);
					redirect('admin/users/edit/'.$user->id);
				}

				if ($user)
				{
					$this->user_model->update($user->id, $data);

					if ($is_self && isset($data['password_hash']))
					{
						$this->session->set_userdata('admin_sig', md5($data['password_hash']));
					}

					$this->flash('success', 'User '.$data['name'].' berhasil diperbarui.');
				}
				else
				{
					$this->user_model->insert($data);
					$this->flash('success', 'User '.$data['name'].' berhasil ditambahkan.');
				}

				redirect('admin/users');
			}
		}

		$this->render('users/form', array(
			'title'     => $user ? 'Ubah User' : 'Tambah User',
			'user'      => $user,
			'is_self'   => $is_self,
		));
	}

	public function _unique_username($username)
	{
		if ($this->user_model->username_taken($username, $this->editing_id))
		{
			$this->form_validation->set_message('_unique_username', 'Username sudah dipakai user lain.');

			return FALSE;
		}

		return TRUE;
	}
}
