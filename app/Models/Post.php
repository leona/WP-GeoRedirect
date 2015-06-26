<?php 
namespace GeoRedirect\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Post extends Eloquent {
    protected $table = 'posts';
    public $timestamps = false;
}