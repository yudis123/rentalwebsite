<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

	public function index()
	{
		$this->render('dashboard', array(
			'title'       => 'Dashboard',
			'car_total'   => $this->car_model->count_all(),
			'car_ready'   => $this->car_model->count_by_status('ready'),
			'car_rented'  => $this->car_model->count_by_status('rented'),
			'user_total'  => $this->user_model->count_all(),
			'recent_cars' => $this->car_model->recent(5),
		));
	}
}
