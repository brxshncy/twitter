<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Following;
use App\User;
use App\Like;
use App\Tweet;
use Auth;
use App\Notifications\newLike;
use DB;
class AjaxController extends Controller
{
    public function followUser(Request $request){
        if($request->ajax()){
            
           $data = $this->validate($request, ['user_id' => 'required|integer','following_user_id' => 'required|integer']);
            Following::create($data);
            $person = User::findorFail(intval($request->following_user_id));
            $user = ucwords($person->fname." ".$person->lname);
            return response()->json(['message' =>  $user.' is Followed'],200);
        }
    }
    public function loadTweets(Request $request){
        if($request->ajax()){
            return $request->all();
        }
    }
    public function likeTweet(Request $request){
        $checkLike = Like::where('user_id',$request->user_id)
                          ->where('tweet_id',$request->tweet_id)
                          ->first();
        if($checkLike){
            $checkLike->delete();
            $num_likes = Tweet::find($request->tweet_id)->getLikes();
            return response()->json(
                [
                    'message' => 'Tweet Unliked',
                    'num_likes' =>  $num_likes,
                    'tweet_id' => $request->tweet_id
                ],200
            );
        }
        else{
            $data = ['user_id' => $request->user_id,'tweet_id' => $request->tweet_id];
            Like::create($data);
            $num_likes = Tweet::find($request->tweet_id)->getLikes();
            if($request->user_id != $request->posterId){
                $poster = User::where('id',$request->posterId)->first();
                $liker = User::where('id',$request->user_id)->first();
                $dataToNotification = new \stdClass();
                $dataToNotification->user_id = Auth::guard('user')->user()->id;
                $dataToNotification->tweet_id = $request->tweet_id;
                $dataToNotification->name = ucwords($liker->fname." ".$liker->lname);
                $dataToNotification->username = $liker->username;
                $poster->notify(new newLike($dataToNotification));
            }
            return response()->json(['success' => 'Liked tweet','num_likes' => $num_likes,'tweet_id' => $request->tweet_id],200);
           
        }
    }

}
