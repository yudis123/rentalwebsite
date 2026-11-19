<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cars extends Admin_Controller {

	public function index()
	{
		$keyword = trim((string) $this->input->get('q'));
		$status  = (string) $this->input->get('status');

		$this->render('cars/index', array(
			'title'   => 'Mobil',
			'cars'    => $this->car_model->search($keyword, $status),
			'keyword' => $keyword,
			'status'  => $status,
			'total'   => $this->car_model->count_all(),
		));
	}

	public function create()
	{
		$this->_form(NULL);
	}

	public function edit($id = 0)
	{
		$car = $this->car_model->find($id);
		$car OR show_404();

		$this->_form($car);
	}

	/** Ganti status ready <-> disewa langsung dari tabel. */
	public function toggle($id = 0)
	{
		$this->require_post();
		$car = $this->car_model->find($id);
		$car OR show_404();

		$new = $car->status === 'ready' ? 'rented' : 'ready';
		$this->car_model->update($car->id, array('status' => $new));

		$this->flash('success', $car->name.' sekarang berstatus '.($new === 'ready' ? 'Ready' : 'Sedang disewa').'.');
		redirect('admin/cars');
	}

	public function delete($id = 0)
	{
		$this->require_post();
		$car = $this->car_model->find($id);
		$car OR show_404();

		$this->load->library('image_uploader');
		$this->car_model->delete($car->id);
		$this->image_uploader->remove($car->image);

		$this->flash('success', $car->name.' berhasil dihapus.');
		redirect('admin/cars');
	}

	/** Form tambah ($car = NULL) atau ubah. */
	private function _form($car)
	{
		$this->load->library('image_uploader');
		$image_error = '';

		if ($this->input->method() === 'post')
		{
			$this->form_validation
				->set_rules('name', 'Nama mobil', 'trim|required|max_length[100]')
				->set_rules('category', 'Kategori', 'trim|required|max_length[30]')
				->set_rules('seats', 'Jumlah kursi', 'trim|required|integer|greater_than[0]|less_than[61]')
				->set_rules('transmission', 'Transmisi', 'required|in_list[Manual,Matic]')
				->set_rules('fuel', 'Bahan bakar', 'trim|required|max_length[20]')
				->set_rules('price_per_day', 'Harga per hari', 'trim|required|is_natural_no_zero|less_than[1000000000]')
				->set_rules('description', 'Deskripsi', 'trim|max_length[500]')
				->set_rules('sort_order', 'Urutan tampil', 'trim|integer|greater_than[-100000]|less_than[100000]')
				->set_rules('status', 'Status', 'required|in_list[ready,rented]');

			if ($this->form_validation->run())
			{
				$data = array(
					'name'          => $this->input->post('name'),
					'category'      => $this->input->post('category'),
					'seats'         => (int) $this->input->post('seats'),
					'transmission'  => $this->input->post('transmission'),
					'fuel'          => $this->input->post('fuel'),
					'price_per_day' => (int) $this->input->post('price_per_day'),
					'description'   => (string) $this->input->post('description'),
					'sort_order'    => (int) $this->input->post('sort_order'),
					'status'        => $this->input->post('status'),
				);

				if ($this->image_uploader->has_file('image'))
				{
					$path = $this->image_uploader->store('image', 'cars', 1280);

					if ($path === FALSE)
					{
						$image_error = $this->image_uploader->error;
					}
					else
					{
						$data['image'] = $path;
					}
				}

				if ($image_error === '')
				{
					if ($car)
					{
						$this->car_model->update($car->id, $data);

						if (isset($data['image']))
						{
							$this->image_uploader->remove($car->image);
						}

						$this->flash('success', $data['name'].' berhasil diperbarui.');
					}
					else
					{
						isset($data['image']) OR $data['image'] = Car_model::PLACEHOLDER;
						$this->car_model->insert($data);
						$this->flash('success', $data['name'].' berhasil ditambahkan.');
					}

					redirect('admin/cars');
				}
			}
		}

		$this->render('cars/form', array(
			'title'       => $car ? 'Ubah Mobil' : 'Tambah Mobil',
			'car'         => $car,
			'categories'  => $this->car_model->get_categories(),
			'fuels'       => $this->car_model->get_fuels(),
			'image_error' => $image_error,
		));
	}
}
