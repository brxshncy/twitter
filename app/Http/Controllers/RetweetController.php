<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Retweet;
use App\CaptionRetweet;
use App\Notifications\newRetweet;
use App\User;


class RetweetController extends Controller
{
    public function retweet(Request $request){
        $data = ['tweet_id' => $request->tweet_id, 'user_id' => $request->user_id];
        $retweet = ['tweet_id' => $request->tweet_id,'caption' => $request->caption];

        $poster = User::where('id',$request->poster_id)->first();
        $rt = User::where('id',$request->user_id)->first();

        $dataNotification = new \stdClass();
        $dataNotification->user_id = $request->user_id;
        $dataNotification->tweet_id = $request->tweet_id;
        $dataNotification->name = ucwords($rt->fname." ".$rt->lname);
        $dataNotification->username = $rt->username;
        $poster->notify(new newRetweet($dataNotification));
        Retweet::create($data);
        return redirect()->back()->with('succ','Tweet Retweeted!');
    }
}
