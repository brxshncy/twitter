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
    // This is my controller for my home view
    public function home(){

        // In this Controller I've constructed a query that will retrieve Tweets, Retweets, and other Users that is currently not followed by the current logged in User.
        // This query will then be view on my the current loggin User's homepage.
        // I am using Query Builder for some complex queries  since its my own comfort for complex queries

        $currUserId = Auth::guard('user')->user()->id; // I initiliaze the current logged in user by storing on variable currUserId

        $retweets = DB::table('retweets as rt') // I select retweets table and put an alias of rt 
                    ->select('x.tweet as tweet,',DB::raw('CONCAT(y.fname," ",y.lname) as fullname'), 
                    'rt.created_at as created_at','x.id as tweet_id','rt.user_id as tweet_user_id','y.profile_pic as profile_pic','y.username as username') 
                    ->leftJoin('users as y','y.id','=','rt.user_id')              // Select specific column these includes tweet,name,tweet id, rt.user_id, profile_pic, and username
                    ->leftJoin('tweets as x','x.id','=','rt.tweet_id')            // Left Join to users and tweets table
                    ->orWhereIn('rt.user_id',function($query) use($currUserId){   // OR clause A WHERE IN clause to filter o na subquery (rt.user_id) where the rt.user_id is on followings table
                        $query->select('g.following_user_id')                     //  where the user_id of followings table is equal to current loggined user_id
                            ->from('followings as g')
                            ->where('g.user_id',$currUserId);
                    })
                    ->orWhereIn('rt.user_id',function($query){       // OR WHERE IN the rt.user_id is IN SELECT user_id IN followings
                        $query->select('j.user_id')                  // This will display the retweets of the followed user by the current logged in user 
                              ->from('followings as j');             // and will also display the retweets of current logged in user 
                    });

        $union = DB::table('tweets as t')
                ->select('t.tweet as tweet',DB::raw('CONCAT(u.fname," ",u.lname) as fullname'),'t.created_at as created_at','t.id as tweet_id','t.user_id as tweet_user_id',
                    'u.profile_pic as profile_pic','u.username as username') // Same with the query above I select all the tweet and the user of the tweet
                    ->leftJoin('users as u','u.id','=','t.user_id')          // and also the tweet of the current user logged in 
                    ->where('t.user_id',$currUserId)
                    ->orWhereIn('t.user_id',function($query) use($currUserId){
                        $query->select('f.following_user_id')
                                ->from('followings as f')
                                ->where('f.user_id',$currUserId);
                    })->unionAll($retweets)                               // And union all the data retrieve from retweets table, and tweets table
                ->orderBy('created_at','DESC');                          // ordered by created_at in descending order
                
        $tweets = DB::query()
                ->select('*',DB::raw('CASE 
                                 WHEN tweet_user_id IN (SELECT t.user_id from tweets t WHERE t.id = tweet_id)    
                                THEN "original" ELSE "retweet" END as context'))
                ->from($union)  //To identify if the specific retrieved tweet is original or a retweet.
                ->get();        // I then make a CASE statement from the query above, WHEN the tweet_user_id (user_id between the tweets and retweets table) 
                                // WHERE the id of the tweet is = tweet_id(tweet id retrieved from tweets and retweets table)
                                

    
        $people = DB::table('users as u')
                ->select(DB::raw("CONCAT(u.fname,' ',u.lname) as fullName"),'u.username as username','u.id as userId','u.profile_pic as profile_pic','u.id as user_id')
                ->whereNotIn('id',[$currUserId])
                ->whereNotin('id',function($query) use($currUserId){        // A query that retrieves all user that is not followed by the current logged in user
                    $query->select('f.following_user_id')
                          ->from('followings as f')
                          ->where('f.user_id',$currUserId);
                })
                ->get(); 
        return view('home')->with('tweets',$tweets)   // compact it with the home view 
                           ->with('people',$people);
    }
    public function postTweet(Request $request){
        $data = ['user_id' => $request->user_id, 'tweet' => $request->tweet];   // This is when the user post a tweet, it will be stored on the database.
        Tweet::create($data);                                                  // I didn't add validation because the tweet is can be nullable, and the user_id is given on the form. 
        return redirect('home');
    }
    // In this controller the user can view its profile, or other user's profile. In the profile view, the user can view all tweets and retweets of the user profile selected.
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
                ->where('rt.user_id','=',$id);
                
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
                  ))                              // I use same query to retrieve tweets and retweets of the current viewed profile user 
                  ->from($union)
                  ->get();
        
    $profile = User::where('id',$id)->first();
    return view('profile')->with('people',$people)
                          ->with('profile',$profile)
                          ->with('tweets',$tweets);
    }
    // This is the controller if the user will edit his profile. 
    public function editProfile(Request $request,$id){
       if($request->profile_pic != null){           // first I check if the image uploaded by the user is null, I set the image column into nullable.
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
            $user = User::findorFail($id);        // and in this code the user can update its information. 
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
