<?php
// Remember to include classes instantiated (with = new Something)
include_once 'classes/Sigpic.class.php' ; // Much needed Sigpic core
include_once 'classes/SigpicModuleTSV.class.php' ; // Optional module
include_once 'classes/SigpicModuleXfire.class.php' ; // Optional module
include_once 'classes/SigpicModuleXML.class.php' ; // Optional module
include_once 'classes/SigpicRandom.class.php' ; // Optional module

/* Example Signature Picture
 * TIP: Name your script index.php to use URL without filename
 * 
 * @package Sigpic
 * @author Geir Andre Halle
 * @version v0.9.8
 * @copyright Copyright (c) 2009, Geir Andre Halle
 * @license http://www.gnu.org/licenses/gpl.txt
 */

// Some default values
$name   = 'Sikorsky UH-60 Black Hawk';
$background = 'images/uh60.png' ;
$font   = 'fonts/CrassRoots.ttf';

/*
 * Example on how to change values on the fly
 * Usage: Sigpic.php?name=Something+else
 */
if(!empty($_GET['name'])) { $name = $_GET['name']; }

// Make our new signature. Background only :-/
$sig = new Sigpic($background) ;


/*
 * Converting image to true colors ensures proper display of colors, and
 * may be used strategically to display items in actual colors or using the
 * background image color palette. Items will be displayed using background
 * palette until this command is run, and actual colors after that.
 */
$sig->setTrueColor() ;


/*
 * TSV Module example
 * (<'name'[, 'bannertype']>)
 * Downloads and displays a cached copy of
 * http://userb.tsviewer.com/<bannertype>/<name>.png
 *
 * @param string $name Is mandatory
 * @param string $bannertype Is optional, and defaults to '2'.
 */
$tsv = new SigpicModuleTSV('Anonymous', '3') ;
$sig->addElementImage( $tsv->cache() , '-0', '-0') ;


/*
 * Xfire Module example, live status
 * (<'xfireusername'[, 'feedtype']>)
 * See http://www.xfire.com/xml/<yourusername>/live/ for
 * ideas on what fields to use. Instead of live you can also use
 * one of profile,gameplay,servers,gamerig,friends,screenshots
 *
 * @param string $xfireusername is mandatory
 * @param string $feedtype is optional, and defaults to 'live' if omitted
 */
// This line loads the Xfire module for a chosen username and optional feedtype
$xfire = new SigpicModuleXfire('gahalle'); // or new SigpicModuleXfire('gahalle', 'live');
// Following line builds an Xfire status line but won't do anything with it yet
$xfireline = "Xfire says " . $xfire->value(nickname) . " is " . $xfire->value(status) ;
// This one adds in-game status to the line above, if there is one:
if (trim($xfire->value(game))) { $xfireline .= " and playing «".$xfire->value(game)."»"; }
// And the usual method for adding it as text to the signature
$sig->addElementText(6, 0, 50, 6, '#996600', 'fonts/NotCourierSans-Bold.otf', $xfireline) ;

/*
 * Xfire data can also be pulled from the generic XML module using the
 * complete Uniform Resource Identifier.
 */
$xml = new SigpicModuleXML('http://www.xfire.com/xml/gahalle/live/') ;
$sig->addElementText(6, 0, 50, 14, '#996600', 'fonts/NotCourierSans-Bold.otf', $xml->value(customstatus)) ;

// Add our name (size, angle, x, y, color, font, text)
$sig->addElementText(20, 0, 10, -8, '#002800', $font, $name) ;

// Some extras, just for fun
$sig->addElementImage('images/flag_no.gif', -8, 8); // An avatar image
$sig->addElementText(10, 0, 5, 5, '#66cc66', 'fonts/NotCourierSans.otf', date('H:i')) ; // 24h clock
$sig->addElementText(15, 4, 10, -25, '#999900', $font, 'Example:') ; // Guess :)
$sig->addElementText(6, 0, -5, -30, '#996600', 'fonts/NotCourierSans-Bold.otf', SigpicRandom::lineFromFile('text/quotes.txt') ) ;



// Manufacture and display the final product
$sig->display() ;

?>