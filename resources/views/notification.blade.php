@extends('layouts.main')
@section('title')
@php
    $title = "";
@endphp
    Notifications
@endsection
@section('sidebar')
    @include('layouts.sidebar')
@endsection
@section('center')
<div class="feed">
    <div class="feed-header notif">
        <h5>Notifications</h5> 
        <div class="all">
            <ul>
                <li class="active">All</li>
                <li>Mentions</li>
            </ul>
        </div>
    </div>
        @if(count($notifs) > 0)
            @foreach($notifs as $notif)
            <a href="{{ route('view-thread',$notif->tweet_id) }}" style="text-decoration: none;">
                <div class="notif-feed">
                    <div class="icon">
                        @if($notif->context == 'retweets')
                            <i class="fas fa-retweet"></i>
                        @elseif($notif->context == 'comments')
                            <i class="fas fa-comment"></i>
                        @elseif($notif->context == 'likes')
                            <i class="fas fa-heart like"></i>
                        @endif
                        
                    </div>
                    <div class="content">
                        <div class="img">
                            @if($notif->profile_pic === null)
                                <img src="{{ asset('img/no-pic.png') }}" alt="">
                            @else 
                                <img src="{{ asset('uploads/')."/".$notif->profile_pic }}" alt="">
                            @endif
                        </div>
                        <div class="act">
                            <p>
                                 <span style="font-weight:bold;">{{ ucwords($notif->name) }}</span> 
                                @if($notif->context == 'retweets')
                                    retweets your tweet 
                                @elseif($notif->context == 'comments')
                                     commented your tweet 
                                @elseif($notif->context == 'likes')
                                        likes your tweet 
                                @endif
                           </p>
                        </div>
                        <div class="content-tweet">
                             <p> {{ $notif->tweet }}</p>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        @endif
</div>
@endsection
@section('other')
    @include('layouts.others')
@endsection