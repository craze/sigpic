<?php
// Remember to include classes instantiated (with = new Something)
include_once 'classes/Sigpic.class.php' ; // Much needed Sigpic core

/* Example Signature Picture
 * TIP: Name your script index.php to use URL without filename
 *
 * @package Sigpic
 * @author Geir Andre Halle
 * @version v0.9.8
 * @copyright Copyright (c) 2009, Geir Andre Halle
 * @license http://www.gnu.org/licenses/gpl.txt
 */

// Make our new signature. Background only :-/
$sig = new Sigpic('images/uh60.png') ;

/*
 * Converting image to true colors ensures proper display of colors, and
 * may be used strategically to display items in actual colors or using the
 * background image color palette. Items will be displayed using background
 * palette until this command is run, and actual colors after that.
 */
$sig->setTrueColor() ;

// Add our name (size, angle, x, y, color, font, text)
$sig->addElementText(20, 0, 10, -8, '#002800', 'fonts/CrassRoots.ttf', 'NAME GOES HERE') ;

// Manufacture and display the final product
$sig->display() ;
?>