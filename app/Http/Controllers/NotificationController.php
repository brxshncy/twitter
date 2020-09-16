<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
class NotificationController extends Controller
{
    public function index(){
        $checkifRead = Auth::guard('user')->user()->unreadNotifications->markAsRead();
        $people = DB::table('users as u')
                  ->select(DB::raw("CONCAT(u.fname,' ',u.lname) as fullName"),'u.username as username','u.id as userId','u.profile_pic as profile_pic','u.id as user_id')
                  ->whereNotIn('id',[Auth::guard('user')->user()->id])
                  ->whereNotIn('id',function($query){
                      $query->select('f.following_user_id')
                            ->from('followings as f')
                            ->where('f.user_id',Auth::guard('user')->user()->id);
                  })->get();

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

        return view('notification')->with('people',$people)
                                   ->with('notifs',$notifs);
    }
}
