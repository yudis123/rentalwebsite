<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->output->set_header('Cache-Control: no-store, max-age=0');
	}

	public function login()
	{
		if ($this->session->userdata('admin_id'))
		{
			redirect('admin');
		}

		$data = array(
			'settings' => $this->setting_model->all(),
			'error'    => '',
			'username' => '',
		);

		if ($this->input->method() === 'post')
		{
			$username = strtolower(trim((string) $this->input->post('username')));
			$password = (string) $this->input->post('password');
			$key      = sha1($this->input->ip_address().'|'.$username);

			$data['username'] = $username;

			if ($this->user_model->too_many_attempts($key))
			{
				$data['error'] = 'Terlalu banyak percobaan gagal. Coba lagi dalam '
					.(int) $this->config->item('login_lock_minutes').' menit.';
			}
			else
			{
				$user = $username !== '' ? $this->user_model->find_by_username($username) : NULL;

				if ($user)
				{
					$valid = password_verify($password, $user->password_hash);
				}
				else
				{
					// Tetap lakukan kerja hash agar lama respons tidak
					// membocorkan username mana yang terdaftar.
					password_hash($password, PASSWORD_BCRYPT);
					$valid = FALSE;
				}

				if ($user && $valid && $user->is_active)
				{
					$this->user_model->clear_attempts($key);
					$this->session->sess_regenerate(TRUE);
					$this->session->set_userdata(array(
						'admin_id'  => (int) $user->id,
						'admin_sig' => md5($user->password_hash),
					));
					$this->user_model->touch_login($user->id);

					redirect('admin');
				}

				$this->user_model->record_attempt($key);
				$data['error'] = 'Username atau password salah.';
			}
		}

		$this->load->view('admin/auth/login', $data);
	}

	public function logout()
	{
		if ($this->input->method() !== 'post')
		{
			redirect('admin');
		}

		$this->session->sess_destroy();
		redirect('admin/login');
	}
}
