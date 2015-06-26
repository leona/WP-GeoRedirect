<?php

namespace GeoRedirect\Models;
use \GeoIp2\Database\Reader;

class Geo {
    
    public $code;
    public $name;
    
    public function __construct() {
        $this->source_ip = $_SERVER['REMOTE_ADDR'];;
        $this->source_database = get_option('georedirect_source');
        
        if (empty($this->source_database))
            $this->source_database = 'geolite';
    }
    
    public function query() {
        switch($this->source_database) {
            case 'geolite':
                $this->geoLiteHandler();
            break;
        }
        
        return array('name' => $this->name, 'code' => $this->code);
    }
    
    private function geoLiteHandler() {
        try {
            $handler = new Reader(__DIR__ . '/../../data/GeoLite2-Country.mmdb');
            $handler = $handler->country($this->source_ip);
        
            $this->code = $handler->country->isoCode;
            $this->name = $handler->country->names['en'];
        } catch(\Exception $e) {}
    }

}