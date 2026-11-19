<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends Admin_Controller {

	public function index()
	{
		if ($this->input->method() === 'post')
		{
			$changing = (string) $this->input->post('password') !== '';

			$this->form_validation->set_rules('name', 'Nama', 'trim|required|max_length[100]');

			if ($changing)
			{
				$this->form_validation
					->set_rules('current_password', 'Password saat ini', 'required|callback__current_password')
					->set_rules('password', 'Password baru', 'min_length[8]|max_length[72]')
					->set_rules('password_confirm', 'Konfirmasi password', 'matches[password]');
			}

			if ($this->form_validation->run())
			{
				$data = array('name' => $this->input->post('name'));

				if ($changing)
				{
					$data['password_hash'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
				}

				$this->user_model->update($this->user->id, $data);

				if ($changing)
				{
					// Session ini tetap masuk; session lain akun ini keluar otomatis.
					$this->session->sess_regenerate(TRUE);
					$this->session->set_userdata('admin_sig', md5($data['password_hash']));
				}

				$this->flash('success', $changing ? 'Profil dan password berhasil diperbarui.' : 'Profil berhasil diperbarui.');
				redirect('admin/profile');
			}
		}

		$this->render('profile', array('title' => 'Profil Saya'));
	}

	public function _current_password($value)
	{
		if (password_verify((string) $value, $this->user->password_hash))
		{
			return TRUE;
		}

		$this->form_validation->set_message('_current_password', 'Password saat ini salah.');

		return FALSE;
	}
}
