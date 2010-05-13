<?php
include_once 'classes/SigpicDownload.class.php' ;
/* Sigpic Module: XML
 *
 * Contains means and methods for pulling data from any XML feed.
 *
 * @package Sigpic
 * @author Geir Andre Halle
 * @version v0.9.8
 * @copyright Copyright (c) 2009, Geir Andre Halle
 * @license http://www.gnu.org/licenses/gpl.txt
 */
class SigpicModuleXML {
    private $xmlurl = '' ;
    private $xml ;
    private $ttl = 300 ; // Default time-to-live in seconds between updates

/*
 * XML constructor
 *
 * @param string $url Uniform Resource Identifier for custom XML-feed
 * @param string $ttl Minimum amount of seconds between updates
 */
    public function __construct($url, $ttl) {
        if ($ttl) { $this->ttl = $ttl ; } // Use default TTL if not specified
        $this->xml = new SigpicDownload($url, $this->ttl) ;
    }
    /*
     * Function for returning data from various fields in the XML.
     *
     * @param string $var XML field name
     * $return string XML field value
     */
    public function value($var) {
        $xml = simplexml_load_file( $this->xml->cachefile() ) ;
        $response = $xml->$var ;
        return $response ;
    }
}
?>
