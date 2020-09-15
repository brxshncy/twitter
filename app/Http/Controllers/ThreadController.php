<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Tweet;
class ThreadController extends Controller
{
    public function thread($tweet_id){
        $currUserId = Auth::guard('user')->user()->id;
        $tweet = DB::table('tweets as t')
                     ->select('t.*',DB::raw('CONCAT(u.fname," ",u.lname) as name'),'u.username as username','u.profile_pic as profile_pic','t.id as tweet_id')
                     ->leftJoin('users as u','u.id','=','t.user_id')
                     ->where('t.id',$tweet_id)
                     ->first();

        $comments = DB::table('comments as c')
                     ->select(DB::raw('CONCAT(u.fname," ",u.lname) as name'),'u.username as username','c.caption as caption','u.profile_pic as profile_pic')
                     ->leftJoin('users as u','u.id','=','c.user_id')
                     ->leftJoin('tweets as t','t.id','=','c.tweet_id')
                     ->where('t.id',$tweet_id)
                     ->orderBy('c.created_at','DESC')
                     ->get();

        $people = DB::table('users as u')
                ->select(DB::raw("CONCAT(u.fname,' ',u.lname) as fullName"),'u.username as username','u.id as userId','u.profile_pic as profile_pic','u.id as user_id')
                ->whereNotIn('id',[$currUserId])
                ->whereNotin('id',function($query) use($currUserId){
                    $query->select('f.following_user_id')
                          ->from('followings as f')
                          ->where('f.user_id',$currUserId);
                })
                ->get();
        return view('tweet-thread')->with('tweet',$tweet)
                                   ->with('people',$people)
                                   ->with('listComments',$comments);
    }
}
