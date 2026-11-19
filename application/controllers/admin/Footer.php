<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Isi footer. Nama, logo, dan kontak perusahaan diatur di Pengaturan Umum
 * (controller Settings).
 */
class Footer extends Admin_Controller {

	/** Kolom footer: kunci => array(label, aturan). */
	protected $fields = array(
		'tagline'     => array('Tagline', 'trim|max_length[100]'),
		'description' => array('Deskripsi singkat', 'trim|max_length[300]'),
		'instagram'   => array('Instagram', 'trim|max_length[200]|callback__valid_link'),
		'facebook'    => array('Facebook', 'trim|max_length[200]|callback__valid_link'),
		'tiktok'      => array('TikTok', 'trim|max_length[200]|callback__valid_link'),
		'cta_title'   => array('Judul ajakan', 'trim|max_length[80]'),
		'cta_text'    => array('Teks ajakan', 'trim|max_length[200]'),
	);

	public function index()
	{
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

				$this->setting_model->set_many($values);
				$this->flash('success', 'Footer berhasil diperbarui.');
				redirect('admin/footer');
			}
		}

		$this->render('footer', array('title' => 'Footer'));
	}

	public function _valid_link($value)
	{
		if ($value === '' OR external_url($value) !== '')
		{
			return TRUE;
		}

		$this->form_validation->set_message('_valid_link', 'Tautan {field} tidak valid.');

		return FALSE;
	}
}
