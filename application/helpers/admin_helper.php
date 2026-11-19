<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Pembuat kolom form admin: label, input, petunjuk, dan pesan kesalahan
| dalam satu panggilan agar view tetap ringkas dan seragam.
|
| Opsi ($opt): type, hint, required, placeholder, attrs (string atribut tambahan).
*/

if ( ! function_exists('_admin_field_wrap'))
{
	function _admin_field_wrap($name, $label, $control, $opt)
	{
		$required = ! empty($opt['required']) ? ' <span class="text-rose-500">*</span>' : '';
		$hint     = isset($opt['hint']) ? '<p class="form-hint">'.e($opt['hint']).'</p>' : '';
		$error    = form_error($name, '<p class="form-error">', '</p>');

		return '<div>'
			.'<label for="f-'.e($name).'" class="form-label">'.e($label).$required.'</label>'
			.$control.$error.($error === '' ? $hint : '')
			.'</div>';
	}
}

if ( ! function_exists('_admin_field_attrs'))
{
	function _admin_field_attrs($name, $opt)
	{
		$has_error = form_error($name) !== '';

		return ' id="f-'.e($name).'" name="'.e($name).'"'
			.' class="form-input'.($has_error ? ' form-input-invalid' : '').'"'
			.( ! empty($opt['required']) ? ' required' : '')
			.(isset($opt['placeholder']) ? ' placeholder="'.e($opt['placeholder']).'"' : '')
			.(isset($opt['attrs']) ? ' '.$opt['attrs'] : '');
	}
}

if ( ! function_exists('admin_input'))
{
	function admin_input($name, $label, $value = '', $opt = array())
	{
		$type = isset($opt['type']) ? $opt['type'] : 'text';
		// Password tidak pernah diisi ulang ke halaman.
		$val  = $type === 'password' ? '' : set_value($name, $value);

		$control = '<input type="'.e($type).'" value="'.$val.'"'._admin_field_attrs($name, $opt).'>';

		return _admin_field_wrap($name, $label, $control, $opt);
	}
}

if ( ! function_exists('admin_textarea'))
{
	function admin_textarea($name, $label, $value = '', $opt = array())
	{
		$rows    = isset($opt['rows']) ? (int) $opt['rows'] : 3;
		$control = '<textarea rows="'.$rows.'"'._admin_field_attrs($name, $opt).'>'.set_value($name, $value).'</textarea>';

		return _admin_field_wrap($name, $label, $control, $opt);
	}
}

if ( ! function_exists('admin_select'))
{
	/** $options: array('nilai' => 'Label') */
	function admin_select($name, $label, $options, $value = '', $opt = array())
	{
		$current = set_value($name, $value, FALSE);
		$control = '<select'._admin_field_attrs($name, $opt).'>';

		foreach ($options as $key => $text)
		{
			$control .= '<option value="'.e($key).'"'.((string) $key === (string) $current ? ' selected' : '').'>'.e($text).'</option>';
		}

		$control .= '</select>';

		return _admin_field_wrap($name, $label, $control, $opt);
	}
}

if ( ! function_exists('admin_date'))
{
	/** "2026-11-19 08:30:00" -> "19 Nov 2026, 08.30" */
	function admin_date($datetime)
	{
		if (empty($datetime))
		{
			return '—';
		}

		$months = array(1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des');
		$time   = strtotime($datetime);

		return date('j', $time).' '.$months[(int) date('n', $time)].' '.date('Y, H.i', $time);
	}
}
