<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_model extends CI_Model {

	protected $table = 'settings';
	protected $cache;

	/** Semua pengaturan: nilai dari database menimpa nilai bawaan. */
	public function all()
	{
		if ($this->cache === NULL)
		{
			$this->cache = (array) $this->config->item('site_defaults');

			foreach ($this->db->get($this->table)->result() as $row)
			{
				$this->cache[$row->name] = (string) $row->value;
			}
		}

		return $this->cache;
	}

	public function get($name, $default = '')
	{
		$all = $this->all();

		return isset($all[$name]) ? $all[$name] : $default;
	}

	/** Simpan beberapa pengaturan sekaligus: array('kunci' => 'nilai'). */
	public function set_many(array $values)
	{
		$this->db->trans_start();

		foreach ($values as $name => $value)
		{
			$this->db->replace($this->table, array('name' => $name, 'value' => (string) $value));
		}

		$this->db->trans_complete();
		$this->cache = NULL;

		return $this->db->trans_status();
	}
}
