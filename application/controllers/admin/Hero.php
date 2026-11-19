<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hero extends Admin_Controller {

	/** Kolom teks hero: kunci => array(label, aturan). */
	protected $fields = array(
		'hero_badge'         => array('Label kecil', 'trim|max_length[60]'),
		'hero_title_1'       => array('Judul bagian 1', 'trim|required|max_length[60]'),
		'hero_title_accent'  => array('Judul bagian berwarna', 'trim|max_length[60]'),
		'hero_title_2'       => array('Judul bagian 3', 'trim|max_length[60]'),
		'hero_description'   => array('Deskripsi', 'trim|max_length[300]'),
		'hero_btn_primary'   => array('Teks tombol utama', 'trim|required|max_length[30]'),
		'hero_btn_secondary' => array('Teks tombol WhatsApp', 'trim|required|max_length[30]'),
		'hero_stat1_value'   => array('Angka statistik 1', 'trim|max_length[12]'),
		'hero_stat1_label'   => array('Keterangan statistik 1', 'trim|max_length[30]'),
		'hero_stat2_value'   => array('Angka statistik 2', 'trim|max_length[12]'),
		'hero_stat2_label'   => array('Keterangan statistik 2', 'trim|max_length[30]'),
	);

	public function index()
	{
		$this->load->library('image_uploader');
		$image_error = '';

		if ($this->input->method() === 'post')
		{
			foreach ($this->fields as $name => $field)
			{
				$this->form_validation->set_rules($name, $field[0], $field[1]);
			}

			if ($this->form_validation->run())
			{
				$values    = array();
				$old_image = $this->setting_model->get('hero_image');

				foreach (array_keys($this->fields) as $name)
				{
					$values[$name] = (string) $this->input->post($name);
				}

				if ($this->image_uploader->has_file('image'))
				{
					$path = $this->image_uploader->store('image', 'hero', 1600);

					if ($path === FALSE)
					{
						$image_error = $this->image_uploader->error;
					}
					else
					{
						$values['hero_image'] = $path;
					}
				}

				if ($image_error === '')
				{
					$this->setting_model->set_many($values);

					if (isset($values['hero_image']))
					{
						$this->image_uploader->remove($old_image);
					}

					$this->flash('success', 'Hero banner berhasil diperbarui.');
					redirect('admin/hero');
				}
			}
		}

		$this->render('hero', array(
			'title'       => 'Hero Banner',
			'image_error' => $image_error,
		));
	}

	/** Kembali ke ilustrasi bawaan. */
	public function reset_image()
	{
		$this->require_post();
		$this->load->library('image_uploader');

		$this->image_uploader->remove($this->setting_model->get('hero_image'));
		$this->setting_model->set_many(array('hero_image' => ''));

		$this->flash('success', 'Gambar hero dikembalikan ke ilustrasi bawaan.');
		redirect('admin/hero');
	}
}
