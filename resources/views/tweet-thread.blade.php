@extends('layouts.main')
@section('css')
<link rel="stylesheet" href="{{ asset('css/thread.css') }}">
@endsection
@section('title')
{{ ucwords($tweet->name) ." on Twitter" }}
@endsection
@section('sidebar')
    @php
        $title = '';
    @endphp
    @include('layouts.sidebar')
@endsection


@section('center')
<div class="feed">
    <div class="feed-header">
        <h5>Tweet</h5>
    </div>
    <div class="thread-container" style="border-bottom:1px solid rgb(56,68,77);">
        <div class="flex-header">
            <div class="tweet-img">
                @if($tweet->profile_pic === null)
                    <img src="{{ asset('img/no-pic.png') }}" alt="">
                @else
                    <img src="{{ asset('uploads/')."/".$tweet->profile_pic }}" alt="">
                @endif
            </div>
            <div class="user-info">
                <div class="name">
                    <b>{{ $tweet->name }}</b>
                </div>
                <div class="username">
                    <span>{{ "@".$tweet->username }}</span>
                </div>
            </div>
        </div>
        <div class="tweet-thread">
            <div class="tweet">
                <p>{{ $tweet->tweet }}</p>
            </div>
            <div class="tweet-date">
                <span>{{ date('H:i A',strtotime($tweet->created_at)) }}</span>
                <span>{{ date('F j, Y',strtotime($tweet->created_at)) }}</span>
            </div>
            <div class="stats">
                @php
                    $comments = App\Tweet::find($tweet->id)->numComment();
                    $likes = App\Tweet::find($tweet->id)->getLikes();
                    $retweets = App\Tweet::find($tweet->id)->numRetweet();
                @endphp
                @if($comments > 0)
                    <span> <b>{{ $comments }}</b>  {{ $comments == 1 ? 'Comment' : 'Comments' }}</span>
                @endif
                @if($likes > 0)
                        <span> <b>{{ $likes }}</b>  {{ $likes == 1 ? 'Like' : 'Likes' }} </span>
                @endif
                @if($retweets > 0)
                <span> <b>{{ $retweets }}</b>  {{ $retweets == 1 ? 'Like' : 'Likes' }} </span>
                 @endif
                <!--<span> <b>5</b>  Retweets</span>-->
            </div>
            <div class="stat-icon">
                <button>
                    <i class="far fa-comment"></i>
                <span>{{ $comments > 0 ? $comments : '' }}</span>
                </button>
                <button>
                    <i class="fas fa-retweet"></i>
                    <span>{{ $retweets > 0 ? $retweets : '' }}</span>
                </button>
                <button>
                    <i class="far fa-heart"></i>
                    <span>{{ $likes  > 0 ? $likes : ''}}</span>
                </button>
            </div>
            <div class="reply">
                <form action="">
                    <input type="text" placeholder="Tweet your reply">
                    <div class="reply-btn">
                        <button>Reply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@if(count($listComments) > 0)
    @foreach($listComments as $comment)
        <div class="feed-tweet" style="">
            <div class="tweet-img">
                @if($comment->profile_pic === null)
                    <img src="{{ asset('img/no-pic.png') }}"alt=""> 
                @else 
                    <img src="{{ asset('uploads/').'/'.$comment->profile_pic }}"alt=""> 
                @endif
            </div>
            <div class="tweet-post">
                <div class="user-info" style="margin-left:0 !important; flex-direction:row !important;">
                    <b >{{ ucwords($comment->name) }}</b>
                    <span style="color:rgb(169,174,182);margin-left:10px;">
                        {{ 
                            '@'.$comment->username .". "
                        }}
                        {{
                            Carbon\Carbon::create($tweet->created_at)->diffForHumans()
                        }}
                    </span>
                </div>
                <div class="caption">
                    <p>{{ $comment->caption }}</p>
                </div>
            </div>
        </div>
    @endforeach
@endif
</div>
@endsection


@section('other')
@include('layouts.others')
@endsection