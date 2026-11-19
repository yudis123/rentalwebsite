<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Unggah gambar dengan aman: periksa isi file (bukan ekstensi), beri nama acak,
 * lalu tulis ulang lewat GD (memperkecil bila terlalu lebar dan membuang data
 * selain piksel). File disimpan di assets/uploads/<subfolder>/.
 */
class Image_uploader {

	const MAX_BYTES  = 2097152;  // 2 MB
	const MAX_PIXELS = 40000000; // 40 megapiksel
	const BASE_DIR   = 'assets/uploads/';

	/** Pesan kesalahan terakhir. */
	public $error = '';

	protected $types = array(
		'image/jpeg' => 'jpg',
		'image/png'  => 'png',
		'image/webp' => 'webp',
	);

	/** Apakah pengguna memilih file pada kolom ini? */
	public function has_file($field)
	{
		return isset($_FILES[$field])
			&& is_array($_FILES[$field])
			&& ! is_array($_FILES[$field]['error'])
			&& $_FILES[$field]['error'] !== UPLOAD_ERR_NO_FILE;
	}

	/**
	 * Simpan unggahan. Mengembalikan path relatif (mis. assets/uploads/cars/ab12.jpg)
	 * atau FALSE; alasan gagal ada di $this->error.
	 */
	public function store($field, $subdir, $max_width = 1280, $max_height = 0)
	{
		$this->error = '';

		if ( ! $this->has_file($field))
		{
			return $this->fail('Tidak ada gambar yang dipilih.');
		}

		$file = $_FILES[$field];

		if (in_array($file['error'], array(UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE), TRUE) OR $file['size'] > self::MAX_BYTES)
		{
			return $this->fail('Ukuran gambar maksimal 2 MB.');
		}

		if ($file['error'] !== UPLOAD_ERR_OK OR ! is_uploaded_file($file['tmp_name']))
		{
			return $this->fail('Gambar gagal diunggah. Silakan coba lagi.');
		}

		$finfo = new finfo(FILEINFO_MIME_TYPE);
		$mime  = $finfo->file($file['tmp_name']);
		$size  = @getimagesize($file['tmp_name']);

		if ( ! isset($this->types[$mime]) OR $size === FALSE)
		{
			return $this->fail('Format gambar harus JPG, PNG, atau WebP.');
		}

		if ($size[0] * $size[1] > self::MAX_PIXELS)
		{
			return $this->fail('Dimensi gambar terlalu besar.');
		}

		$subdir = trim(preg_replace('/[^a-z0-9_\-]/i', '', $subdir), '/');
		$dir    = FCPATH.self::BASE_DIR.$subdir;

		if ( ! is_dir($dir) && ! @mkdir($dir, 0755, TRUE))
		{
			return $this->fail('Folder unggahan tidak bisa dibuat.');
		}

		$relative = self::BASE_DIR.$subdir.'/'.bin2hex(random_bytes(12)).'.'.$this->types[$mime];

		if ( ! $this->reencode($file['tmp_name'], FCPATH.$relative, $mime, (int) $max_width, (int) $max_height))
		{
			return $this->fail('Gambar tidak bisa diproses. Coba file lain.');
		}

		return $relative;
	}

	/** Hapus file unggahan lama. Hanya menyentuh isi assets/uploads/. */
	public function remove($relative)
	{
		$relative = (string) $relative;

		if (strpos($relative, self::BASE_DIR) !== 0 OR strpos($relative, '..') !== FALSE)
		{
			return FALSE;
		}

		$path = FCPATH.$relative;

		return is_file($path) ? @unlink($path) : FALSE;
	}

	protected function reencode($source, $target, $mime, $max_width, $max_height = 0)
	{
		$image = @imagecreatefromstring(file_get_contents($source));

		if ( ! $image)
		{
			return FALSE;
		}

		// Foto dari ponsel sering tersimpan miring; luruskan sesuai data EXIF.
		if ($mime === 'image/jpeg' && function_exists('exif_read_data'))
		{
			$exif   = @exif_read_data($source);
			$angles = array(3 => 180, 6 => -90, 8 => 90);

			if ( ! empty($exif['Orientation']) && isset($angles[$exif['Orientation']]))
			{
				$rotated = imagerotate($image, $angles[$exif['Orientation']], 0);

				if ($rotated)
				{
					$this->free($image);
					$image = $rotated;
				}
			}
		}

		if ( ! imageistruecolor($image))
		{
			imagepalettetotruecolor($image);
		}

		// Perkecil (tanpa mengubah proporsi) agar muat di batas lebar dan tinggi.
		$width  = imagesx($image);
		$height = imagesy($image);
		$ratio  = 1;

		if ($max_width > 0 && $width > $max_width)
		{
			$ratio = min($ratio, $max_width / $width);
		}

		if ($max_height > 0 && $height > $max_height)
		{
			$ratio = min($ratio, $max_height / $height);
		}

		if ($ratio < 1)
		{
			// Aktifkan penyimpanan alpha lebih dulu agar transparansi ikut terbawa.
			imagealphablending($image, FALSE);
			imagesavealpha($image, TRUE);
			$scaled = imagescale($image, max(1, (int) round($width * $ratio)), max(1, (int) round($height * $ratio)));

			if ($scaled)
			{
				$this->free($image);
				$image = $scaled;
			}
		}

		// Pertahankan transparansi (PNG/WebP).
		imagealphablending($image, FALSE);
		imagesavealpha($image, TRUE);

		switch ($mime)
		{
			case 'image/png':
				$ok = imagepng($image, $target, 6);
				break;
			case 'image/webp':
				$ok = function_exists('imagewebp') && imagewebp($image, $target, 85);
				break;
			default:
				$ok = imagejpeg($image, $target, 85);
		}

		$this->free($image);

		return (bool) $ok;
	}

	protected function fail($message)
	{
		$this->error = $message;

		return FALSE;
	}

	/**
	 * Lepas memori gambar. Sejak PHP 8.0 gambar GD dibebaskan otomatis,
	 * dan imagedestroy() usang sejak PHP 8.5.
	 */
	private function free($image)
	{
		if (PHP_VERSION_ID < 80000)
		{
			imagedestroy($image);
		}
	}
}
