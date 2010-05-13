<?php
include_once 'classes/SigpicDownload.class.php' ;
/* Sigpic Module: TSViewer
 *
 * Example module containing means and methods for displaying the TSViewer.com
 * userbanner. Normally, you'd just addElementImage any images. Even TSV ones.
 *
 * @package Sigpic
 * @author Geir Andre Halle
 * @version v0.9.8
 * @copyright Copyright (c) 2009, Geir Andre Halle
 * @license http://www.gnu.org/licenses/gpl.txt
 */
class SigpicModuleTSV {
    private $tsvName = 'Anonymous' ;
    private $tsvType = '2' ;
    private $tsvURL = 'http://userb.tsviewer.com/' ;
    private $tsvCache = '';
    private $ttl = 30 ;
    private $tsv ;
/*
 * TSV constructor
 *
 * @param string $name Teamspeak nickname
 * @param string $bannertype 1, 2 or 3, optionally followed by _serverID
 */
    public function __construct($name, $bannertype, $ttl) {
        $this->tsvName = $name ;
        if ($bannertype) { $this->tsvType = $bannertype ; }
        if ($ttl) { $this->ttl = $ttl ; }
        $this->tsvURL = $this->tsvURL . $this->tsvType . '/' . $this->tsvName . '.png' ;

        $this->tsv = new SigpicDownload($this->tsvURL, $this->ttl) ;
        $this->tsvCache = $this->tsv->cachefile() ;
    }

    public function cache() {
        return $this->tsvCache ;
    }

    public function url () {
        return $this->tsvURL ;
    }
}
?>
