<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    // protected $table = 'blogposts';

    protected $fillable = ['title', 'content'];

    public function comment()
    {
    	return $this->hasMany('App\Comment');
    }




}
