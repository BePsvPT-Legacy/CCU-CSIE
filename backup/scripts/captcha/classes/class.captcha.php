<?php 
/*
    Copyright (C) 2009  Murad <Murawd>
						Josh L. <Josho192837>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
class SecurityImage{
	var $oImage;
	var $iWidth;
	var $iHeight;
	var $iNumChars;
	var $iNumLines;
	var $iSpacing;
	var $sCode;

	function SecurityImage($iWidth = 250, $iHeight = 50, $iNumChars = 5, $iNumLines = 50){
		$this->iWidth = $iWidth;
		$this->iHeight = $iHeight;
		$this->iNumChars = $iNumChars;
		$this->iNumLines = $iNumLines;
		$this->oImage = imagecreate($iWidth, $iHeight);
			imagecolorallocate($this->oImage, 255, 255, 255);
		$this->iSpacing = (int)($this->iWidth / $this->iNumChars);
	}

	function DrawLines() {
		for ($i = 0; $i < $this->iNumLines; $i++) {
			$iRandColour = rand(190, 250);
			$iLineColour = imagecolorallocate($this->oImage, $iRandColour, $iRandColour, $iRandColour);
			imageline($this->oImage, rand(0, $this->iWidth), rand(0, $this->iHeight), rand(0, $this->iWidth), rand(0, $this->iHeight), $iLineColour);
		}
	}

	function GenerateCode() {
		$this->sCode = '';
		for ($i = 0; $i < $this->iNumChars; $i++) {
			$this->sCode .= chr(rand(65, 90));
			$_SESSION['captcha_code'] = $this->sCode;
		}
	}

	function DrawCharacters() {
		for ($i = 0; $i < strlen($this->sCode); $i++) {
			$iCurrentFont = rand(2, 6);
			$iRandColour = rand(0, 128);
			$iTextColour = imagecolorallocate($this->oImage, $iRandColour, $iRandColour, $iRandColour);
			imagestring($this->oImage, $iCurrentFont, $this->iSpacing / 3 + $i * $this->iSpacing, ($this->iHeight - imagefontheight($iCurrentFont)) / 2, $this->sCode[$i], $iTextColour);
		}
	}

	function Create($sFilename = '') {
		// check if GD GIF library present
		if (!function_exists('imagegif')) {
			return false;
		}

		$this->DrawLines();
		$this->GenerateCode();
		$this->DrawCharacters();

		if ($sFilename != '') {
			imagegif($this->oImage, $sFilename);
		}else{
			header('Content-type: image/gif');
			imagegif($this->oImage);
		}

		imagedestroy($this->oImage);
		return true;
	}
}
?>