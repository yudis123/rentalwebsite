<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Dasar semua controller: akses ke konten situs dari tabel settings. */
class MY_Controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('setting_model');
	}
}

/**
 * Dasar semua halaman admin. Menolak pengunjung yang belum login,
 * akun nonaktif, dan role yang tidak diizinkan.
 */
class Admin_Controller extends MY_Controller {

	/** @var object User yang sedang login */
	protected $user;

	/** Role yang boleh membuka controller ini. */
	protected $allowed_roles = array('superadmin');

	public function __construct()
	{
		parent::__construct();

		$this->load->model(array('user_model', 'car_model'));
		$this->load->library('form_validation');
		$this->load->helper('admin');
		$this->output->set_header('Cache-Control: no-store, max-age=0');

		$id = (int) $this->session->userdata('admin_id');
		$this->user = $id ? $this->user_model->find($id) : NULL;

		// Tanda password di session: begitu password diganti/direset,
		// semua session lama akun itu otomatis tidak berlaku.
		$signature_ok = $this->user
			&& hash_equals(md5($this->user->password_hash), (string) $this->session->userdata('admin_sig'));

		if ( ! $this->user OR ! $this->user->is_active OR ! $signature_ok)
		{
			$this->session->unset_userdata(array('admin_id', 'admin_sig'));
			redirect('admin/login');
		}

		if ( ! in_array($this->user->role, $this->allowed_roles, TRUE))
		{
			show_error('Anda tidak memiliki akses ke halaman ini.', 403, 'Akses ditolak');
		}

		$this->_set_validation_messages();
	}

	/** Tampilkan view admin di dalam kerangka (sidebar + topbar). */
	protected function render($view, $data = array())
	{
		$data['me']       = $this->user;
		$data['settings'] = $this->setting_model->all();
		$data['active']   = strtolower($this->router->class);
		$data['roles']    = $this->config->item('roles');
		isset($data['title']) OR $data['title'] = 'Admin';

		$this->load->view('admin/layouts/header', $data);
		$this->load->view('admin/'.$view, $data);
		$this->load->view('admin/layouts/footer', $data);
	}

	/** Aksi yang mengubah data hanya boleh lewat POST (dan lolos CSRF). */
	protected function require_post()
	{
		if ($this->input->method() !== 'post')
		{
			show_404();
		}
	}

	protected function flash($type, $message)
	{
		$this->session->set_flashdata($type, $message);
	}

	private function _set_validation_messages()
	{
		$messages = array(
			'required'           => '{field} wajib diisi.',
			'min_length'         => '{field} minimal {param} karakter.',
			'max_length'         => '{field} maksimal {param} karakter.',
			'integer'            => '{field} harus berupa angka bulat.',
			'is_natural_no_zero' => '{field} harus berupa angka lebih dari 0.',
			'greater_than'       => '{field} harus lebih dari {param}.',
			'less_than'          => '{field} harus kurang dari {param}.',
			'in_list'            => 'Pilihan {field} tidak valid.',
			'valid_email'        => '{field} bukan alamat email yang valid.',
			'valid_url'          => '{field} bukan tautan yang valid.',
			'matches'            => '{field} tidak sama dengan {param}.',
			'alpha_dash'         => '{field} hanya boleh huruf, angka, garis bawah, dan strip.',
		);

		foreach ($messages as $rule => $message)
		{
			$this->form_validation->set_message($rule, $message);
		}
	}
}
