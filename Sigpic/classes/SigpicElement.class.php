<?php
/* SigpicElement is the class responsible for correct placement of various
 * datatypes on the Sigpic canvas.
 *
 * @package Sigpic
 * @author Geir Andre Halle
 * @version v0.9.8
 * @copyright Copyright (c) 2009, Geir Andre Halle
 * @license http://www.gnu.org/licenses/gpl.txt
 */
class SigpicElement {
	/*
	 * Default variables that are changed automatically or using function calls.
	 */
	protected $sx = 0; // Element width
	protected $sy = 0; // Element height

	/*
	 * Constructor for a new Sigpic Element
	 *
	 */
	public function __construct($px, $py, $sx, $sy)
	{
		$this->sx = $sx;
		$this->sy = $sy;
	}

	public function getSizeX()
	{
		return $this->sx ;
	}

	public function getSizeY()
	{
		return $this->sy ;
	}

	protected function __destruct()
	{

	}
}
?>