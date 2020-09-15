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
});


Route::get('x',function(){
/*SELECT *,
CASE 
	WHEN user_id IN (SELECT rt.user_id FROM retweets rt) 
    	THEN 'retweet'
        ELSE 'orig'
END AS context
FROM 
	(
        SELECT t.tweet as tweet, CONCAT(u.fname,' ',u.lname) as name, t.created_at as date_posted, t.id as tweet_id, t.user_id as tweet_user_id, u.id as user_id FROM tweets t 
        LEFT JOIN users u ON u.id  = t.user_id
        WHERE t.user_id = 1
        OR 
        t.user_id IN (SELECT f.following_user_id from followings f WHERE f.user_id = 1)
        UNION ALL 
        (
        	SELECT x.tweet as tweet, CONCAT(y.fname,' ',y.lname) as name, rt.created_at as date_posted, x.id as tweet_id, x.user_id as tweet_user_id, y.id as user_id from retweets rt 
            LEFT JOIN tweets x ON x.id = rt.tweet_id 
            LEFT JOIN users y ON y.id = rt.user_id 
            WHERE rt.user_id IN (SELECT g.following_user_id from followings g WHERE g.user_id = 1)
        )
    ) as x ORDER BY (date_posted) DESC */


    /*
    
    SELECT *,
CASE 
	WHEN user_id IN (SELECT rt.user_id FROM retweets rt WHERE rt.tweet_id = tweet_id) 
    	THEN 'retweet'
        ELSE 'orig'
END AS context
FROM 
	(
        SELECT t.tweet as tweet, CONCAT(u.fname,' ',u.lname) as name, t.created_at as date_posted, t.id as tweet_id, t.user_id as tweet_user_id, u.id as user_id FROM tweets t 
        LEFT JOIN users u ON u.id  = t.user_id
        WHERE t.user_id = 1
        OR 
        t.user_id IN (SELECT f.following_user_id from followings f WHERE f.user_id = 1)
        UNION ALL 
        (
        	SELECT x.tweet as tweet, CONCAT(y.fname,' ',y.lname) as name, rt.created_at as date_posted, x.id as tweet_id, x.user_id as tweet_user_id, y.id as user_id from retweets rt 
            LEFT JOIN tweets x ON x.id = rt.tweet_id 
            LEFT JOIN users y ON y.id = rt.user_id 
            WHERE rt.user_id IN (SELECT g.following_user_id from followings g WHERE g.user_id = 1)
        )
    ) as x ORDER BY (date_posted) DESC 

    SELECT *,
	CASE 
    	WHEN tweet_user_id IN (SELECT user_id FROM tweets) AND tweet_id IN  (SELECT id FROM tweets)
        THEN 'orig' ELSE 'retweet'
    END as context
FROM 
	(
        SELECT t.tweet as tweet, CONCAT(u.fname,' ',u.lname) as name, t.created_at as date_posted, t.id as tweet_id, t.user_id as tweet_user_id FROM tweets t 
        LEFT JOIN users u ON u.id  = t.user_id
        WHERE t.user_id = 1
        OR 
        t.user_id IN (SELECT f.following_user_id from followings f WHERE f.user_id = 1)
        UNION ALL 
        (
        	SELECT x.tweet as tweet, CONCAT(y.fname,' ',y.lname) as name, rt.created_at as date_posted, x.id as tweet_id, rt.user_id as tweet_user_id from retweets rt 
            LEFT JOIN tweets x ON x.id = rt.tweet_id 
            LEFT JOIN users y ON y.id = rt.user_id 
            WHERE rt.user_id IN (SELECT g.following_user_id from followings g WHERE g.user_id = 1)
        )
    ) as x ORDER BY (date_posted) DESC   

    */
   /* $currUserId = 2;
    $retweets = DB::table('retweets as rt')
              ->select('x.tweet as tweet,',DB::raw('CONCAT(y.fname," ",y.lname) as fullname'),
                      'rt.created_at as created_at','x.id as tweet_id','rt.user_id as tweet_user_id','y.profile_pic as profile_pic','y.username as username')
              ->leftJoin('users as y','y.id','=','rt.user_id')
              ->leftJoin('tweets as x','x.id','=','rt.tweet_id')
              ->orWhereIn('rt.user_id',function($query) use($currUserId){
                  $query->select('g.following_user_id')
                      ->from('followings as g')
                      ->where('g.user_id',$currUserId);
              })
              ->orWhereIn('rt.user_id',function($query){
                  $query->select('j.user_id')
                        ->from('followings as j');
              });
             

  $union = DB::table('tweets as t')
          ->select('t.tweet as tweet',DB::raw('CONCAT(u.fname," ",u.lname) as fullname'),'t.created_at as created_at','t.id as tweet_id','t.user_id as tweet_user_id',
              'u.profile_pic as profile_pic','u.username as username')
          ->leftJoin('users as u','u.id','=','t.user_id')
          ->where('t.user_id',$currUserId)
          ->orWhereIn('t.user_id',function($query) use($currUserId){
              $query->select('f.following_user_id')
                      ->from('followings as f')
                      ->where('f.user_id',$currUserId);
          })->unionAll($retweets)
          ->orderBy('created_at','DESC');
    
          
 $tweets = DB::query()
          ->select('*',DB::raw('CASE 
                          WHEN tweet_user_id IN (SELECT t.user_id from tweets t)
                          AND  tweet_id IN (SELECT t.id from tweets t)
                          THEN "original" ELSE "retweet" END as context'))
          ->from($union)
          ->get();

return $tweets;*/

/*$currUserId = Auth::guard('user')->user()->id;
        $retweets = DB::table('retweets as rt')
        ->select('x.tweet as tweet,',DB::raw('CONCAT(y.fname," ",y.lname) as fullname'),
                'rt.created_at as created_at','x.id as tweet_id','rt.user_id as tweet_user_id','y.profile_pic as profile_pic','y.username as username')
        ->leftJoin('users as y','y.id','=','rt.user_id')
        ->leftJoin('tweets as x','x.id','=','rt.tweet_id')
        ->orWhereIn('rt.user_id',function($query) use($currUserId){
            $query->select('g.following_user_id')
                ->from('followings as g')
                ->where('g.user_id',$currUserId);
        })
        ->orWhereIn('rt.user_id',function($query){
            $query->select('j.user_id')
                ->from('followings as j');
        });

        $union = DB::table('tweets as t')
        ->select('t.tweet as tweet',DB::raw('CONCAT(u.fname," ",u.lname) as fullname'),'t.created_at as created_at','t.id as tweet_id','t.user_id as tweet_user_id',
        'u.profile_pic as profile_pic','u.username as username')
        ->leftJoin('users as u','u.id','=','t.user_id')
        ->where('t.user_id',$currUserId)
        ->orWhereIn('t.user_id',function($query) use($currUserId){
        $query->select('f.following_user_id')
                ->from('followings as f')
                ->where('f.user_id',$currUserId);
        })->unionAll($retweets)
        ->orderBy('created_at','DESC');

        $tweets = DB::query()
        ->select('*',DB::raw('CASE 
                    WHEN tweet_user_id IN (SELECT t.user_id from tweets t WHERE t.id = tweet_id)
                 
                    THEN "original" ELSE "retweet" END as context'))
        ->from($union)
        ->get();
return $tweets;*/

$retweets = DB::table('retweets as rt')
                  ->select('t.tweet as tweet',DB::raw("CONCAT(u.fname,' ',u.lname) as name"),'u.profile_pic as profile_pic','rt.user_id as anon_id','rt.created_at as activity_date','t.user_id as tweet_user_id','t.id as tweet_id')
                  ->leftJoin('users as u','u.id','=','rt.user_id')
                  ->leftJoin('tweets as t','t.id','=','rt.tweet_id');
                  
        $comments = DB::table('comments as c')
                  ->select('t.tweet as tweet',DB::raw("CONCAT(u.fname,' ',u.lname) as name"),'u.profile_pic as profile_pic','c.user_id as anon_id','c.created_at as activity_date','t.user_id as tweet_user_id','t.id as tweet_id')
                  ->leftJoin('users as u','u.id','=','c.user_id')
                  ->leftJoin('tweets as t','t.id','=','c.tweet_id');
                

        $likes = DB::table('likes as l')
                    ->select('t.tweet as tweet',DB::raw("CONCAT(u.fname,' ',u.lname) as name"),'u.profile_pic as profile_pic','l.user_id as anon_id','l.created_at as activity_date','t.user_id as tweet_user_id','t.id as tweet_id')
                    ->leftJoin('tweets as t','t.id','=','l.tweet_id')
                    ->leftJoin('users as u','u.id','=','l.user_id')
                    ->unionAll($comments)
                    ->unionAll($retweets)
                    ->where('t.user_id',Auth::guard('user')->user()->id)
                    ->orderBy('activity_date','DESC');
                    

        $notifs = DB::query()
                  ->select('*', DB::raw(
                      'CASE
                            WHEN tweet_id IN (SELECT l.tweet_id FROM likes as l WHERE l.user_id = anon_id)
                            THEN "likes"
                            WHEN tweet_id IN (SELECT c.tweet_id FROM comments as c WHERE c.user_id = anon_id AND c.caption IS NOT NULL)
                            THEN "comments"
                            WHEN tweet_id IN (SELECT rt.tweet_id FROM retweets as rt WHERE rt.user_id = anon_id)
                            THEN "retweets"
                       END as context
                      '
                  ))
                  ->from($likes)
                  ->get();
        return $notifs;
});