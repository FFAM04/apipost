<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Post extends Model
{
    protected $connection = 'mysql';
    protected $table = 'posts';
    public $timestamps = false;
    protected $guarded = [];
}
