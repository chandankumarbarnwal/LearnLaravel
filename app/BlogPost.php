<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes ;

class BlogPost extends Model
{
    // protected $table = 'blogposts';
    use SoftDeletes;

    protected $fillable = ['title', 'content'];

    public function comment()
    {
    	return $this->hasMany('App\Comment');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function boot()
    {
    	parent::boot();
        
    	static::deleting(function (BlogPost $blogPost){
    		$blogPost->comment()->delete();
    	});

        static::restoring(function (BlogPost $blogPost){
            $blogPost->comment()->restore();
        });
    }

    
}
