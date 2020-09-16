<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
class MessageController extends Controller
{
    public function message(){
        $currUserId = Auth::guard('user')->user()->id;
        $people = DB::table('users as u')
        ->select(DB::raw("CONCAT(u.fname,' ',u.lname) as fullName"),'u.username as username','u.id as userId','u.profile_pic as profile_pic','u.id as user_id')
        ->whereNotIn('id',[$currUserId])
        ->whereNotin('id',function($query) use($currUserId){
            $query->select('f.following_user_id')
                  ->from('followings as f')
                  ->where('f.user_id',$currUserId);
        })
        ->get();

        return view('message')->with('people',$people);
    }
}
