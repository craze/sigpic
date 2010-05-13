<?php
include_once 'classes/SigpicDownload.class.php' ;
/* Sigpic Module: Xfire
 *
 * Contains means and methods for pulling data from the Xfire XML API.
 *
 * @package Sigpic
 * @author Geir Andre Halle
 * @version v0.9.8
 * @copyright Copyright (c) 2009, Geir Andre Halle
 * @license http://www.gnu.org/licenses/gpl.txt
 */
class SigpicModuleXfire {
    private $xfireUsername = '' ;
    private $xfireURL = 'http://www.xfire.com/xml/' ;
    private $xfireFeed = 'live' ;
    private $xfire ;
/*
 * Xfire constructor
 *
 * @param string $username Xfire username
 * @param string $feedtype See http://xfireplus.com/page.php?26 for feedtypes
 */
    public function __construct($username, $feedtype) {
        $this->xfireUsername = $username ;
        if ($feedtype) { $this->xfireFeed = $feedtype ; }
        $this->xfireURL = $this->xfireURL . $username . '/' . $this->xfireFeed . '/' ;

        $this->xfire = new SigpicDownload($this->xfireURL, 30) ;
    }

    /*
     * Function for returning data from various fields in the XML.
     *
     * @param string $var XML field name
     * $return string XML field value
     */
    public function value($var) {
        $xml = simplexml_load_file( $this->xfire->cachefile() ) ;
        $response = $xml->$var;
        return $response;
    }

/*
 * Unused function that returns the URL address used for XML download.
 * @return string Uniform Resource Locator
 */
    public function url() {
        return $this->xfireURL ;
    }
}
?>
