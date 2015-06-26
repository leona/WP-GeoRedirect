<?php 
namespace GeoRedirect\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Rule extends Eloquent {
    protected $table = 'georedirect_rules';
    protected $fillable = array('country', 'target', 'target_type', 'redirect_url', 'force_redirect');
    public $timestamps = false;
}