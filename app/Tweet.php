<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    protected $guarded=[];

    public function likes(){
       return $this->hasMany(Like::class,'tweet_id');
    }
    public function getLikes(){
        return $this->likes->count();
    }
    public function comment(){
        return $this->hasMany(Comment::class,'tweet_id');
    }
    public function numComment(){
        return $this->comment->count();
    }
    public function retweet(){
        return $this->hasMany(Retweet::class,'tweet_id');
    }
    public function numRetweet(){
        return $this->retweet->count();
    }
    public function poster(){
        return $this->hasOne(User::class);
    }
}
