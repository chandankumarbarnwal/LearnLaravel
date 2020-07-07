<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes ;
use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;


class Comment extends Model
{
	use SoftDeletes;
    public function blogPost()
    {
    	return $this->belongsTo('App\BlogPost');
    }

    public function scopeLatest(Builder $query)
    {
    	return $query->orderBy(static::CREATED_AT,'desc');
    }

    public static function boot(){
    	parent::boot();

    	// static::addGlobalScope(new latestScope);
    }


}
