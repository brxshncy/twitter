@extends('layouts.main')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection
@section('title')
    {{ ucwords(Auth::guard('user')->user()->fname." ".Auth::guard('user')->user()->lname)."(@".Auth::guard('user')->user()->username.")" }}
@endsection

@php
    $title = "profile";
@endphp
@section('sidebar')
    @include('layouts.sidebar')
@endsection
@section('center')
<div class="feed">
    <div class="feed-header">
        <h5>{{ ucwords(Auth::guard('user')->user()->fname." ".Auth::guard('user')->user()->lname) }}</h5>
    </div>
    <div class="cover-photo">

    </div>
    <div class="profile-info">
        <div class="header-profile">
            <div class="img-container">
                @if($profile->profile_pic === null)
                    <img src="{{ asset('img/no-pic.png') }}" alt="">
                
                @else
                <img src="{{ asset('uploads/').'/'.$profile->profile_pic }}" alt="">
                @endif
            </div>
           <div class="edit-btn">
               <button type="button" data-toggle="modal" data-target="#exampleModal">Edit Profile</button>
           </div>
        </div>
        <div class="info">
            <h4>{{ ucwords($profile->fname." ".$profile->lname) }}</h4>
            <span>{{ "@".$profile->username }}</span>
            <h5>{{ $profile->bio }}</h5>
            <div class="other-info">
                <i class="fas fa-map-marker-alt"></i> <span>{{ ucwords($profile->address) }}</span> 
                <i class="fas fa-birthday-cake"></i><span>{{ date("F j, Y",strtotime($profile->bday)) }}</span>
                <i class="far fa-calendar-alt"></i><span>{{ date("F j, Y",strtotime($profile->created_at)) }}</span>
            </div>
            <div class="stats">
                <span><b>50</b> following</span>
                <span><b>60</b> followers</span>
            </div>
            <div class="activity">
                <ul>
                    <li class="active">Tweets</li>
                    <li>Tweets & Replies</li>
                    <li>Media</li>
                    <li>Likes</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@include('modals.edit-profile')
@section('other')
@include('layouts.others')
@endsection
@endsection
