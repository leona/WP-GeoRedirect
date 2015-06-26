<?php
namespace GeoRedirect\Controllers;
use GeoRedirect\Models\Post;
use GeoRedirect\Models\Rule;
use GeoRedirect\Models\Helper;
use Illuminate\Database\Capsule\Manager as Capsule;

class RouteController {

    public $client_data = array();
    private $rule;
    private $redirect_store;
    private $redirect_url;
    
    public function __construct($geo) {
        $this->preValidation(function() use($geo) {
           $this->client_data = $geo->query();
           
           $this->queryRules(function() {
               $this->prepRoute()->execRoute();
           });
        });
    }
    
    private function prepRoute() {
        if ($this->rule->force_redirect == false) {
            if ($this->routeExists() == false) {
                if ($this->rule->target == null && !empty($this->rule->redirect_url)) {
                    $this->redirect_url = $this->rule->redirect_url;
                }
            }
        } else {
            switch($this->rule->target_type) {
                case 'post':
                    $this->redirect_url = get_post($this->rule->target)->post_name;
                break;
                case 'category':
                    $this->redirect_url = '/category/' . get_category($this->rule->target)->slug;
                break;
            }
        
        }
        
        return $this;
    }
    private function execRoute() {
        if (!empty($this->redirect_url)) {
            header('Location: ' . $this->redirect_url);
            exit;
        }
    }
    private function routeExists() {
        if (isset($_COOKIE['redirects'])) {
            $this->redirect_store = json_decode($_COOKIE['redirects']);
        
            if (is_object($this->redirect_store)) {
                if (!empty($this->redirect_store->{$this->rule->id})) {
                    return true;
                }
            }
        }
        
        if (is_array($this->redirect_store))
            $this->redirect_store[$this->rule->id] = 'true';
        if (is_object($this->redirect_store))
            $this->redirect_store->{$this->rule->id} = 'true';
        if (empty($this->redirect_store))
            $this->redirect_store = array();
            
        setcookie('redirects', json_encode($this->redirect_store), strtotime('30 days from now'));
        
        return false;
    }
    
    private function queryRules($callback) {
        $this->rule = Rule::where(function($query) {
            return $query->where('country', '=', 'IE');
        })->where(function($query) {
            return $query->where('target', '=', null)->orWhere('target', '=', $_SERVER['REQUEST_URI']);
        })->first();
        
        if (!empty($this->rule)) $callback();
    }
    
    private function preValidation($callback) {
        if (Capsule::schema()->hasTable('georedirect_rules') && !is_admin() && !Helper::isCrawler()) 
            $callback();
    }
}
