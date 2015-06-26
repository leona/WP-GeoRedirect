<?php
namespace GeoRedirect\Controllers;
use GeoRedirect\Models\Helper;
use GeoRedirect\Models\Rule;
use Herbert\Framework\Http;

class MainController {

    private $http;
    private $return_msg = null;
    
    public function __construct(Http $http) {
        $this->http = $http;
        
        if (Helper::post() && $this->http->get('csrf_token') == $_SESSION['csrf_token']) 
            $this->show();
    }
    
    public function index() {
        $_SESSION['csrf_token'] = Helper::genKey('64');

        return view('@GeoRedirect/main.twig', array(
                'action_url'    => menu_page_url('geo-redirect', false),
                'csrf_token'    => $_SESSION['csrf_token'],
                'current_rules' => Rule::all(),
                'countries'     => Helper::countries(),
                'posts'         => get_posts(),
                'categories'    => get_categories(),
                'return_msg'    => $this->return_msg
            )
        );
    }
    
    private function show() {
        $this->return_msg = true;
        
        switch($this->http->get('request_type')) {
            case 'mass':
                $this->addMass();
            break;
            case 'specific':
                $this->addSpecific();
            break;
            case 'current':
                $this->deleteRules();
            break;
        }
    }
    
    private function deleteRules() {
        if (is_array($this->http->get('delete')))
            foreach($this->http->get('delete') as $key => $rule_id) 
                Rule::find($key)->delete();
    }
    
    private function addMass() {
        Rule::create([
            'country'        => $this->http->get('country'),
            'redirect_url'   => $this->http->get('redirect_url'),
            'force_redirect' => $this->http->get('force_redirect') ? false : true
        ]);
    }
    
    private function addSpecific() {
        $target      = null;
        $target_type = null;
        
        foreach($this->http->get('target') as $key => $value) {
            if (!empty($value)) {
                $target      = $value;
                $target_type = $key;
            }
        }
    
        Rule::create([
            'country'        => $this->http->get('country'),
            'redirect_url'   => $this->http->get('redirect_url'),
            'target'         => $target,
            'target_type'    => $target_type,
            'force_redirect' => $this->http->get('force_redirect') ? false : true
        ]);
    }
}