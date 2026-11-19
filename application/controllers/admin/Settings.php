<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Pengaturan Umum: nama, logo, dan kontak perusahaan. */
class Settings extends Admin_Controller {

	protected $allowed_roles = array('superadmin');

	/** Kolom teks: kunci => array(label, aturan). */
	protected $fields = array(
		'site_name' => array('Nama perusahaan', 'trim|required|max_length[60]'),
		'whatsapp'  => array('Nomor WhatsApp', 'trim|required|callback__valid_whatsapp'),
		'phone'     => array('Telepon (tampilan)', 'trim|max_length[30]'),
		'email'     => array('Email', 'trim|valid_email|max_length[100]'),
		'hours'     => array('Jam layanan', 'trim|max_length[100]'),
		'address'   => array('Alamat', 'trim|max_length[200]'),
	);

	/** Kolom unggah logo: nama kolom form sama dengan kunci pengaturan. */
	protected $logos = array('logo', 'logo_light');

	// Batas ukuran logo setelah diproses (piksel).
	const LOGO_MAX_WIDTH  = 640;
	const LOGO_MAX_HEIGHT = 160;

	public function index()
	{
		$this->load->library('image_uploader');
		$image_errors = array('logo' => '', 'logo_light' => '');

		if ($this->input->method() === 'post')
		{
			foreach ($this->fields as $name => $field)
			{
				$this->form_validation->set_rules($name, $field[0], $field[1]);
			}

			if ($this->form_validation->run())
			{
				$values = array();

				foreach (array_keys($this->fields) as $name)
				{
					$values[$name] = (string) $this->input->post($name);
				}

				$values['whatsapp']       = $this->_normalize_whatsapp($values['whatsapp']);
				$values['show_site_name'] = $this->input->post('show_site_name') ? '1' : '0';

				$uploaded = array();

				foreach ($this->logos as $key)
				{
					if ( ! $this->image_uploader->has_file($key))
					{
						continue;
					}

					$path = $this->image_uploader->store($key, 'logo', self::LOGO_MAX_WIDTH, self::LOGO_MAX_HEIGHT);

					if ($path === FALSE)
					{
						$image_errors[$key] = $this->image_uploader->error;
					}
					else
					{
						$uploaded[$key] = $path;
					}
				}

				if (array_filter($image_errors))
				{
					// Salah satu logo gagal: batalkan semuanya agar tidak ada file yatim.
					foreach ($uploaded as $path)
					{
						$this->image_uploader->remove($path);
					}
				}
				else
				{
					$old = $this->setting_model->all();
					$this->setting_model->set_many($values + $uploaded);

					foreach (array_keys($uploaded) as $key)
					{
						$this->image_uploader->remove($old[$key]);
					}

					$this->flash('success', 'Pengaturan umum berhasil diperbarui.');
					redirect('admin/settings');
				}
			}
		}

		$this->render('settings', array(
			'title'        => 'Pengaturan Umum',
			'image_errors' => $image_errors,
		));
	}

	/** Hapus logo utama atau logo versi terang. */
	public function remove_logo($key = '')
	{
		$this->require_post();
		in_array($key, $this->logos, TRUE) OR show_404();

		$this->load->library('image_uploader');
		$this->image_uploader->remove($this->setting_model->get($key));
		$this->setting_model->set_many(array($key => ''));

		$this->flash('success', $key === 'logo' ? 'Logo dihapus. Situs kembali memakai ikon bawaan.' : 'Logo versi terang dihapus.');
		redirect('admin/settings');
	}

	/** "0812-3456-7890" atau "+62 812..." -> "6281234567890" */
	private function _normalize_whatsapp($value)
	{
		$digits = preg_replace('/\D+/', '', (string) $value);

		return strpos($digits, '0') === 0 ? '62'.substr($digits, 1) : $digits;
	}

	public function _valid_whatsapp($value)
	{
		$length = strlen($this->_normalize_whatsapp($value));

		if ($length < 9 OR $length > 15)
		{
			$this->form_validation->set_message('_valid_whatsapp', '{field} tidak valid. Contoh: 081234567890.');

			return FALSE;
		}

		return TRUE;
	}
}
