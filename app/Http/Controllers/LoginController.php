<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Hash;
use Auth;
class LoginController extends Controller
{
    public function login(Request $request){
        if($request->isMethod('post')){
            $username = $request->username;

            if(is_numeric($username)){
                if(Auth::guard('user')->attempt(['contact' => $username, 'password' => $request->password])){
                    return redirect('home');
                }
                else{
                    return redirect('/')->with('err','Invalid Username/Email/Phone Number and Password Combination!');
                }
            }
            else if(filter_var($username,FILTER_VALIDATE_EMAIL)){
                if(Auth::guard('user')->attempt(['email' => $username, 'password' => $request->password])){
                    return redirect('home');
                }
                else{
                    return redirect('/')->with('err','Invalid Username/Email/Phone Number and Password Combination!');
                }
            }
            else{
                if(Auth::guard('user')->attempt(['username' => $username, 'password' => $request->password])){
                    return redirect('home');
                }
                else{
                    return redirect('/')->with('err','Invalid Username/Email/Phone Number and Password Combination!');
                }
            }
          
        }
        return view('welcome');
    }
    public function signup(Request $request){
        if($request->isMethod('post')){
           $data = request()->validate(
               [
                   'fname' => 'required',
                   'lname' => 'required',
                   'address' => 'required',
                   'contact' => 'required',
                   'bday' => 'required|date|before:2001-00-00',
                   'gender' => 'required',
                   'username' => 'required',
                   'email' => 'required',
                   'password' => 'required',
               ],
               [
                   'fname.required' => 'First Name is required',
                   'lname.required' => 'Last Name is required',
                   'address.required' => 'Address is required',
                   'contact.required' => 'Contact is required',
                   'bday.required' => 'Birthday is required',
                   'bday.before' => 'Age must be atleast 20 years old above',
                   'gender.required' => 'Gender is required',
                   'username.required' => 'Username is required',
                   'email.required' => 'Email is required',
                   'password.required' => 'Password is required',
               ]
            );
            $data['password'] = Hash::make($request->password);
            User::create($data);
            return redirect('/')->with('succ','You succesfully registered, login your account and start tweeting now!');
        }
        return view('signup');
    }
    public function logout(){
        Auth::guard('user')->logout();
        return redirect('/');
    }
}
