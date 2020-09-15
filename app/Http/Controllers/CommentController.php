<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Notifications\newComment;
use App\User;
class CommentController extends Controller
{
    public function comment(Request $request){
       $data = [
           'user_id' => $request->user_id,
           'tweet_id' => $request->tweet_id,
           'caption' => $request->caption,
       ];
       $poster = User::where('id',$request->poster_id)->first();
       $commenter =  User::where('id',$request->user_id)->first();
       $dataNotification = new \stdClass();
       $dataNotification->user_id = $request->user_id;
       $dataNotification->tweet_id = $request->tweet_id;
       $dataNotification->name = ucwords($commenter->fname." ".$commenter->lname);
       $dataNotification->username = $commenter->username;
       $poster->notify(new newComment($dataNotification));
       Comment::create($data);
       return redirect()->back()->with('succ','Your tweet was sent');
    }
}
