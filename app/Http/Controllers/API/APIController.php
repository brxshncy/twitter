<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User;

class APIController extends Controller
{
    public function clearNotif($id){
         Auth::guard('user')->user()->where('id',$id)->unreadNotification->markAsRead();
         return "success";
    }
}
