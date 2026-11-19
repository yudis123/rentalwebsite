<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	protected $table = 'users';

	public function all()
	{
		return $this->db->order_by('name', 'ASC')->get($this->table)->result();
	}

	public function find($id)
	{
		return $this->db->get_where($this->table, array('id' => (int) $id))->row();
	}

	public function find_by_username($username)
	{
		return $this->db->get_where($this->table, array('username' => $username))->row();
	}

	public function username_taken($username, $except_id = 0)
	{
		return $this->db
			->where('username', $username)
			->where('id !=', (int) $except_id)
			->count_all_results($this->table) > 0;
	}

	public function count_all()
	{
		return $this->db->count_all($this->table);
	}

	/** Jumlah superadmin aktif selain user tertentu. */
	public function count_other_active_superadmins($except_id)
	{
		return $this->db
			->where(array('role' => 'superadmin', 'is_active' => 1, 'id !=' => (int) $except_id))
			->count_all_results($this->table);
	}

	public function insert(array $data)
	{
		$this->db->insert($this->table, $data);

		return $this->db->insert_id();
	}

	public function update($id, array $data)
	{
		return $this->db->where('id', (int) $id)->update($this->table, $data);
	}

	public function delete($id)
	{
		return $this->db->where('id', (int) $id)->delete($this->table);
	}

	public function touch_login($id)
	{
		return $this->update($id, array('last_login_at' => date('Y-m-d H:i:s')));
	}

	// --- Pembatasan percobaan login -------------------------------------

	public function too_many_attempts($key)
	{
		$since = date('Y-m-d H:i:s', time() - 60 * (int) $this->config->item('login_lock_minutes'));

		$count = $this->db
			->where('throttle_key', $key)
			->where('attempted_at >=', $since)
			->count_all_results('login_attempts');

		return $count >= (int) $this->config->item('login_max_attempts');
	}

	public function record_attempt($key)
	{
		$this->db->insert('login_attempts', array(
			'throttle_key' => $key,
			'attempted_at' => date('Y-m-d H:i:s'),
		));

		// Buang catatan lama agar tabel tidak menumpuk.
		$this->db->where('attempted_at <', date('Y-m-d H:i:s', time() - 86400))->delete('login_attempts');
	}

	public function clear_attempts($key)
	{
		$this->db->where('throttle_key', $key)->delete('login_attempts');
	}
}
