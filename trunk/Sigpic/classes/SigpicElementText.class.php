<?php
include_once 'classes/SigpicElement.class.php' ;

/* 
 * @package Sigpic
 * @author Geir Andre Halle
 * @version v0.9.8
 * @copyright Copyright (c) 2009, Geir Andr� Halle
 * @license http://www.gnu.org/licenses/gpl.txt
 */

class SigpicElementText extends SigpicElement
{
	/*
	 * Default variables
	 */
	protected $size = 0 ;
	protected $angle = 0 ;
	protected $color = 0 ;
	protected $fontfile = '' ;
	protected $text = '' ;

	/*
	 * SigpicElementText Constructor
	 * @param int 	$size 	Font size
	 * @param int 	$angle 	Text angle
	 * @param int 	$px 	Horizontal position
	 * @param int 	$py 	Vertical position
	 * @param int 	$color 	Hexadecimal color index, 000000-ffffff
	 * @param string $fontfile Font filename with relative path
	 * @param string $text	User-defined textstring
	 */
	public function __construct($size, $angle, $pos_x, $pos_y, $hex_color, $fontfile, $text)
	{

		// Register instance variables
		$this->size = $size ;
		$this->angle = $angle ;
		$this->px = $pos_x ;
		$this->py = $pos_y ;
		$this->color = $hex_color ;
		$this->fontfile = $fontfile ;
		$this->text = $text ;

		// Calculate object size
		$size = $this->getSizeArray($size, $angle, $fontfile, $text) ;
		$this->sx = $size[0] ;
		$this->sy = $size[1] ;
	}

	/*
	 * @return int Font size
	 */
	public function getSize()	
	{	
		return $this->size ;	
	}

	/*
	 * @return int Text angle
	 */
	public function getAngle()	
	{	
		return $this->angle ;	
	}

	/*
	 * @return int Color identifier
	 */
	public function getColor()	
	{ 	
		return $this->color ;   
	}

	/*
	 * @return string Font filename with relative path
	 */
	public function getFontfile()
	{
		return $this->fontfile ;
	}

	/*
	 * @return string User-defined textstring
	 */
	public function getText()
	{
		return $this->text ;
	}

	/*
	 * Calculate an array containing dimensions.
	 * @return array [0] = width, [1] = height
	 */
	public function getSizeArray($size, $angle, $fontfile, $text)
	{
		$sizearray = imagettfbbox($size, $angle, $fontfile, $text) ;
	
	 	// Corners used to calculate size depends on angle
		$rad = deg2rad($angle);
		switch(true)
		{
			// Odd quadrants, 0-90 and 180-270
			case ((sin($rad) * cos($rad)) >= 0):
				$sizevalue[0] = abs($sizearray[2] - $sizearray[6]) ;
				$sizevalue[1] = abs($sizearray[3] - $sizearray[7]) ;
				
				break;
				// Even quadrants, 90-180 and 270-360
			case ((sin($rad) * cos($rad)) < 0):
				$sizevalue[0] = abs($sizearray[0] - $sizearray[4]) ;
				$sizevalue[1] = abs($sizearray[1] - $sizearray[5]) ;
				break;
		}
		return $sizevalue;
	}
	
	function __destruct()
	{
		parent::__destruct() ;
	}
}

?>