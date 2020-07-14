<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes ;
use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use App\Scopes\DeletedAdminScope;
use Illuminate\Support\Facades\Cache;


class BlogPost extends Model
{
    // protected $table = 'blogposts';
    use SoftDeletes;

    protected $fillable = ['title', 'content', 'user_id'];

    public function comment()
    {
    	// return $this->hasMany('App\Comment');
        return $this->hasMany('App\Comment')->latest();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag')->withTimestamps()->as('tagged');
    }

    public function scopeLates(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function scopeMostCommented(Builder $query)
    {
        return $query->withCount('comment')->orderBy('comment_count', 'desc');
    }


    public function scopeLatestWithRelations(Builder $query)
    {
        return $query->latest()->withCount('comment')
        ->with('user')->with('tags');

    }



    public static function boot()
    {
        static::addGlobalScope(new DeletedAdminScope);
    	parent::boot();

        // static::addGlobalScope(new LatestScope);
        
    	static::deleting(function (BlogPost $blogPost){
    		$blogPost->comment()->delete();
    	});

        static::updating(function (BlogPost $blogPost) {
            Cache::forget("blog-post-{$blogPost->id}");
            // Cache::tags(['blog-post'])->forget("blog-post-{$blogPost->id}");

        });

        static::restoring(function (BlogPost $blogPost){
            $blogPost->comment()->restore();
        });
    }

    
}
