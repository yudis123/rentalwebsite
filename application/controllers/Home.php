<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function index()
	{
		$this->load->model('car_model');

		$data = array(
			'site'       => $this->setting_model->all(),
			'cars'       => $this->car_model->get_all(),
			'categories' => $this->car_model->get_categories(),
			'car_count'  => $this->car_model->count_all(),
			'min_price'  => $this->car_model->min_price(),
		);

		$this->load->view('layouts/header', $data);
		$this->load->view('home', $data);
		$this->load->view('layouts/footer', $data);
	}
}
