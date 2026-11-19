<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Car_model extends CI_Model {

	const PLACEHOLDER = 'assets/img/cars/placeholder.svg';

	protected $table = 'cars';

	/** Semua mobil: yang ready lebih dulu, lalu urutan tampil. */
	public function get_all()
	{
		return $this->db
			->order_by("FIELD(status, 'ready', 'rented')", '', FALSE)
			->order_by('sort_order', 'ASC')
			->order_by('price_per_day', 'ASC')
			->get($this->table)
			->result();
	}

	/** Daftar untuk admin, dengan pencarian nama/kategori dan filter status. */
	public function search($keyword = '', $status = '')
	{
		if ($keyword !== '')
		{
			$this->db->group_start()
				->like('name', $keyword)
				->or_like('category', $keyword)
				->group_end();
		}

		if (in_array($status, array('ready', 'rented'), TRUE))
		{
			$this->db->where('status', $status);
		}

		return $this->db
			->order_by('sort_order', 'ASC')
			->order_by('name', 'ASC')
			->get($this->table)
			->result();
	}

	public function find($id)
	{
		return $this->db->get_where($this->table, array('id' => (int) $id))->row();
	}

	public function recent($limit = 5)
	{
		return $this->db->order_by('updated_at', 'DESC')->limit($limit)->get($this->table)->result();
	}

	/** Daftar kategori yang dipakai, untuk tombol filter. */
	public function get_categories()
	{
		$rows = $this->db
			->select('category')
			->group_by('category')
			->order_by('MIN(sort_order)', 'ASC', FALSE)
			->get($this->table)
			->result();

		return array_map(function ($row) {
			return $row->category;
		}, $rows);
	}

	public function get_fuels()
	{
		$rows = $this->db->select('fuel')->distinct()->order_by('fuel', 'ASC')->get($this->table)->result();

		return array_map(function ($row) {
			return $row->fuel;
		}, $rows);
	}

	public function count_all()
	{
		return $this->db->count_all($this->table);
	}

	public function count_by_status($status)
	{
		return $this->db->where('status', $status)->count_all_results($this->table);
	}

	/** Harga sewa termurah dari mobil yang ready. */
	public function min_price()
	{
		$row = $this->db
			->select_min('price_per_day', 'price')
			->where('status', 'ready')
			->get($this->table)
			->row();

		return $row && $row->price !== NULL ? (int) $row->price : 0;
	}

	public function insert(array $data)
	{
		$data['slug'] = $this->unique_slug($data['name']);
		$this->db->insert($this->table, $data);

		return $this->db->insert_id();
	}

	public function update($id, array $data)
	{
		if (isset($data['name']))
		{
			$data['slug'] = $this->unique_slug($data['name'], $id);
		}

		return $this->db->where('id', (int) $id)->update($this->table, $data);
	}

	public function delete($id)
	{
		return $this->db->where('id', (int) $id)->delete($this->table);
	}

	/** Slug unik dari nama, mis. "Toyota Avanza" -> "toyota-avanza-2". */
	protected function unique_slug($name, $except_id = 0)
	{
		$base = url_title($name, '-', TRUE);
		$base = $base !== '' ? substr($base, 0, 100) : 'mobil';
		$slug = $base;
		$n    = 2;

		while ($this->db->where('slug', $slug)->where('id !=', (int) $except_id)->count_all_results($this->table) > 0)
		{
			$slug = $base.'-'.$n++;
		}

		return $slug;
	}
}
