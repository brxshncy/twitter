<?php

use Illuminate\Support\Facades\Route;
use App\User;
use App\Like;
use App\Tweet;
use App\Notifications\newLike;

Route::get('/','LoginController@login')->name('home');
Route::post('/','LoginController@login')->name('login');
Route::get('signup','LoginController@signup')->name('signup');
Route::post('signup','LoginController@signup')->name('post-signup');
Route::get('logout','LoginController@logout')->name('logout');
Route::group(['middleware' => ['user']],function(){
    Route::get('home','HomeController@home')->name('home');
    Route::post('home','HomeController@postTweet')->name('post-tweet');
    Route::get('people','HomeController@people')->name('who_to_follow');
    Route::get('profile/{id}','HomeController@profile')->name('profile');
    Route::post('profile/{id}','HomeController@editProfile')->name('edit-profile');
    Route::post('follow','AjaxController@followUser')->name('followUser');
    Route::post('loadtweet','AjaxController@loadTweets')->name('loadtweet');
    Route::post('like-tweet','AjaxController@likeTweet')->name('like-tweet');
    Route::post('comment','CommentController@comment')->name('comment');
    Route::post('retweet','RetweetController@retweet')->name('retweet');
    Route::get('view-thread/{tweet_id}','ThreadController@thread')->name('view-thread');
    Route::get('notification','NotificationController@index')->name('notifications');
    Route::get('message','MessageController@message')->name('message');
});


Route::get('x',function(){

    $profile = 1;
    $following = DB::table('followings as f')
                 ->select(DB::raw("COUNT(following_user_id) as following"))
                 ->where('user_id',$profile)
                 ->first();
    return $following->following;
});