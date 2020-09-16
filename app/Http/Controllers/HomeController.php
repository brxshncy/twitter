<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Tweet;
use DB;
use Auth;
use App\User;
class HomeController extends Controller
{
    public function home(){
        $currUserId = Auth::guard('user')->user()->id;
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

    
        $people = DB::table('users as u')
                ->select(DB::raw("CONCAT(u.fname,' ',u.lname) as fullName"),'u.username as username','u.id as userId','u.profile_pic as profile_pic','u.id as user_id')
                ->whereNotIn('id',[$currUserId])
                ->whereNotin('id',function($query) use($currUserId){
                    $query->select('f.following_user_id')
                          ->from('followings as f')
                          ->where('f.user_id',$currUserId);
                })
                ->get();
        return view('home')->with('tweets',$tweets)
                           ->with('people',$people);
    }
    public function postTweet(Request $request){
        $data = ['user_id' => $request->user_id, 'tweet' => $request->tweet];
        Tweet::create($data);
        return redirect('home');
    }
    public function profile($id){
    $people = DB::table('users as u')
                ->select(DB::raw("CONCAT(u.fname,' ',u.lname) as fullName"),'u.username as username','u.id as userId','u.profile_pic as profile_pic')
                ->whereNotIn('id',[$id])
                ->whereNotin('id',function($query) use($id){
                    $query->select('f.following_user_id')
                          ->from('followings as f')
                          ->where('f.user_id',$id);
                })
                ->get();
                $retweets = DB::table('retweets as rt')
                ->select('t.tweet as tweet',DB::raw("CONCAT(u.fname,' ',u.lname) as name"),'u.username as username','t.created_at as date','u.profile_pic as profile_pic',
                    't.id as tweet_id','rt.user_id as tweet_user_id')
                ->leftJoin('tweets as t','t.id','=','rt.tweet_id')
                ->leftJoin('users as u','rt.user_id','=','u.id')
                ->where('rt.user_id','=',Auth::guard('user')->user()->id);
                
    $union = DB::table('tweets as t')
                ->select('t.tweet',DB::raw("CONCAT(u.fname,' ',u.lname) as name"),'u.username as username','t.created_at as date','u.profile_pic as profile_pic',
                    't.id as tweet_id','t.user_id as tweet_user_id')
                ->leftJoin('users as u','u.id','=','t.user_id')
                ->unionAll($retweets)
                ->where('t.user_id',$id)
                ->orderBy('date','DESC');
                
    $tweets = DB::query()
                  ->select('*',DB::raw(
                      'CASE
                            WHEN tweet_id IN (SELECT t.id FROM tweets as t WHERE t.user_id = tweet_user_id)
                                THEN "original" ELSE "retweet"
                       END as context
                      '
                  ))
                  ->from($union)
                  ->get();
        
    $profile = User::where('id',$id)->first();
    return view('profile')->with('people',$people)
                          ->with('profile',$profile)
                          ->with('tweets',$tweets);
    }
    public function editProfile(Request $request,$id){
       if($request->profile_pic != null){
           $this->validate($request,[
               'profile_pic' => 'image|mimes:jpeg,png,jpg|max:8000'
           ]);
           $profilePic = $request->file('profile_pic'); 
           $newFileImgName = Str::random(40).'.'.$profilePic->getClientOriginalExtension();
           $profilePic->move(public_path("uploads"),$newFileImgName);
       }
       else{
           $old_profile = User::findorFail($id);
           $old_profile->profile_pic;
           $newFileImgName =  $old_profile->profile_pic;
       }
            $user = User::findorFail($id);
            $user->fname = $request->fname;
            $user->lname = $request->lname;
            $user->address = $request->address;
            $user->contact = $request->contact;
            $user->bday = $request->bday;
            $user->bio = $request->bio;
            $user->profile_pic =$newFileImgName;
            $user->save();
            return redirect()->back()->with('succ','Profile Updated Successfully!');
    }

}
