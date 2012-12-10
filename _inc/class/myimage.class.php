<?php
/**
 * MyImage
 * 
 * @package myGalerie
 * @author Fabien SANCHEZ
 * @copyright 2012
 * @version 1.2
 * @access public
 */
class MyImage {

	private $im;

	private $chemin;
	private $mime;
	private $l;
	private $h;
	private $type;

	private $chargeImage;
	private $retourImage;
	private $retourTempsImage;

	/**
	 * MyImage::__construct()
	 * 
	 * @param chaine $file chemin du fichier de l'image ou image encodé en base64
	 */
	public function __construct($file = null) {
		MyDebug::traceFonction();

		if (!is_null($file) && file_exists($file)) {
			$this->charge($file);
		} elseif (!is_null($file)) {
			$this->charge64($file);
		} else {
			throw new InvalidArgumentException();
		}

	}

	// public function __destruct(){
	// MyDebug::traceFonction();
	// imagedestroy($this->im);
	// unset($this->im,
	// $this->chemin,
	// $this->mime,
	// $this->l,
	// $this->h,
	// $this->type,
	// $this->chargeImage,
	// $this->retourImage,
	// $this->retourTempsImage);
	// }

	/**
	 * MyImage::getMime()
	 * 
	 * @return
	 */
	public function getMime() {
		return $this->mime;
	}

	/**
	 * MyImage::getLargeur()
	 * 
	 * @return
	 */
	public function getLargeur() {
		return $this->l;
	}

	/**
	 * MyImage::getHauteur()
	 * 
	 * @return
	 */
	public function getHauteur() {
		return $this->h;
	}

	/**
	 * MyImage::charge()
	 * 
	 * @param mixed $file
	 * @return
	 */

	public function charge($file) {
		MyDebug::traceFonction();

		$info = "";

		$info = getimagesize($file);

		$this->chemin = $file;
		$this->l = $info[0];
		$this->h = $info[1];
		$this->type = $info[2];
		$this->mime = $info['mime'];

		switch ($this->type) {
			case IMG_JPG:
				$this->chargeImage = function ($image) {
					return imagecreatefromjpeg($image);
				}
				;
				$this->retourImage = function ($resImage, $nom) {
					return imagejpeg($resImage, $nom);
				}
				;
				$this->retourTempsImage = function ($resImage) {
					return imagejpeg($resImage);
				}
				;
				break;
			case IMG_GIF:
				$this->chargeImage = function ($image) {
					return imagecreatefromgif($image);
				}
				;
				$this->retourImage = function ($resImage, $nom) {
					return imagegif($resImage, $nom);
				}
				;
				$this->retourTempsImage = function ($resImage) {
					return imagegif($resImage);
				}
				;
				break;
			case IMG_PNG:
				$this->chargeImage = function ($image) {
					return imagecreatefrompng($image);
				}
				;
				$this->retourImage = function ($resImage, $nom) {
					return imagepng($resImage, $nom);
				}
				;
				$this->retourTempsImage = function ($resImage) {
					return imagepng($resImage);
				}
				;
				break;
			default:
				$this->im = null;
				$this->chargeImage = function ($image) {
					return null;
				}
				;
				$this->retourImage = function ($resImage, $nom) {
					return false;
				}
				;
				$this->retourTempsImage = function ($resImage) {
					return false;
				}
				;
				break;
		}

		$this->im = call_user_func($this->chargeImage, $file);

	}

	/**
	 * MyImage::charge64()
	 * 
	 * @param mixed $img64
	 * @return
	 */
	public function charge64($img64) {
		MyDebug::traceFonction();

		$imgtmp = 'img.tmp';
		$dataImg = base64_decode($img64);
		fwrite(fopen($imgtmp, "w"), $dataImg);
		fclose($imgtmp);

		$this->charge($imgtmp);

		unlink($imgtmp);

	}

	/**
	 * MyImage::sauve()
	 * 
	 * @param mixed $newFichier
	 * @return
	 */
	public function sauve($newFichier = null) {
		MyDebug::traceFonction();

		if (is_null($newFichier)) {
			call_user_func($this->retourImage, $this->im, $this->chemin);
		} else {
			call_user_func($this->retourImage, $this->im, $newFichier);
		}
	}

	/**
	 * MyImage::affiche()
	 * 
	 * @return
	 */
	public function affiche() {
		MyDebug::traceFonction();

		MyDebug::trace("affiche -> Content-Type: {$this->mime}");

		header("Content-Type: {$this->mime}");
		call_user_func($this->retourTempsImage, $this->im);
	}

	/**
	 * MyImage::reSize()
	 * 
	 * @param mixed $taille
	 * @param string $unite
	 * @return
	 */
	public function reSize($taille, $unite = 'px') {
		MyDebug::traceFonction();

		$imgOut = null;
		$lOut = 0;
		$hOut = 0;

		$unite = strtolower($unite);

		switch ($unite) {
			case "px":
				if ($this->l < $this->h) {
					// portrait
					$lOut = round($taille * $this->l / $this->h);
					$hOut = $taille;
				} else {
					// paysage
					$lOut = $taille;
					$hOut = round($taille * $this->h / $this->l);

				}
				break;
			case "%":
				$lOut = round($this->l * $taille / 100);
				$hOut = round($this->h * $taille / 100);
				break;
			default:
				return false;
		}

		$imgOut = imagecreatetruecolor($lOut, $hOut);
		imagecopyresampled($imgOut, $this->im, 0, 0, 0, 0, $lOut, $hOut, $this->l, $this->
			h);

		imagedestroy($this->im);
		$this->im = $imgOut;
		$this->l = $lOut;
		$this->h = $hOut;
		return true;
	}

	/**
	 * MyImage::reDim()
	 * 
	 * @param mixed $tailleL
	 * @param mixed $tailleH
	 * @return
	 */
	public function reDim($tailleL, $tailleH) {
		MyDebug::traceFonction();

		$imgOut = null;
		$lOut = $tailleL;
		$hOut = $tailleH;
		$tailleResize = 0;
		$propSrc = ($this->l / $this->h);
		$propDes = ($lOut / $hOut);

		MyDebug::trace('lout / hout = ' . $propDes . ' & l / h = ' . $propSrc);

		$imgOut = imagecreatetruecolor($lOut, $hOut);

		if ($propDes > $propSrc) {
			imagecopyresampled($imgOut, $this->im, 0, 0, 0, round(($this->h - ($this->l / $propDes)) /
				2), $lOut, $hOut, $this->l, round($this->l / $propDes));
		} else {
			imagecopyresampled($imgOut, $this->im, 0, 0, round(($this->l - ($this->h * $propDes)) /
				2), 0, $lOut, $hOut, round($this->h * $propDes), $this->h);
		}

		imagedestroy($this->im);
		$this->im = $imgOut;
		$this->l = $lOut;
		$this->h = $hOut;
		return true;

	}

	/**
	 * MyImage::base64()
	 * 
	 * @return
	 */
	public function base64() {
		MyDebug::traceFonction();

		$imgtmp = 'img.tmp';

		call_user_func($this->retourImage, $this->im, $imgtmp);
		$imgbinary = fread(fopen($imgtmp, "r"), filesize($imgtmp));

		unlink($imgtmp);

		return base64_encode($imgbinary);

	}

}

?>