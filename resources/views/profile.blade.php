@extends('layouts.main')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection
@section('title')
    {{ ucwords(Auth::guard('user')->user()->fname." ".Auth::guard('user')->user()->lname)." (@".Auth::guard('user')->user()->username.")" }}
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
    <div class="profile-info" style="border-bottom:1px solid rgb(115,131,143);">
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
                @php
                    $followers = DB::table('followings as f')
                                    ->select(DB::raw("COUNT(following_user_id) as follower"))
                                    ->where('following_user_id',$profile->id)
                                    ->first();
                    $following = DB::table('followings as f')
                                    ->select(DB::raw("COUNT(following_user_id) as following"))
                                    ->where('user_id',$profile->id)
                                    ->first();
                @endphp 
                    @if($following->following > 0)
                        <span><b>{{ $following->following }}</b> following</span>
                    @else
                     <span>No following</span>
                    @endif
                    @if($followers->follower > 0)
                        <span><b>{{ $followers->follower }}</b> followers</span>
                    @else
                        <span>No followers</span>
                    @endif
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
    @if(count($tweets) > 0)
        @foreach($tweets as $tweet)
            <div class="feed-tweet  <?php echo $tweet->context === 'retweet' ? 'nocaption' : ''  ?>">
                @if($tweet->context ==='retweet')
                            @php
                                $orig = DB::table('tweets as t')
                                    ->select('u.profile_pic as profile_pic',DB::raw('CONCAT(u.fname," ",u.lname) as name'),'u.username as username','rt.created_at as date_posted')
                                    ->leftJoin('users as u','u.id','=','t.user_id')
                                    ->leftJoin('retweets as rt','rt.tweet_id','=','t.id')
                                    ->where('rt.tweet_id','=',$tweet->tweet_id)
                                    ->first();
                            @endphp
                            <div class="user-info" style="padding:0 30px 5px">
                                @php
                                    $myself = App\Retweet::where('user_id',Auth::guard('user')->user()->id )
                                                        ->where('tweet_id',$tweet->tweet_id)->first();
                                @endphp
                                @if($myself)
                                    <span style="color:rgb(169,174,182); font-size:small;">   
                                        <i class="fas fa-retweet"></i> You retweeted
                                    </span>
                                @else
                                    <span style="color:rgb(169,174,182); font-size:small;">   
                                        <i class="fas fa-retweet"></i> retweeted
                                    </span>
                                    <small style="color:#fff">{{ ucwords($tweet->name) }}</small>
                                    <input type="hidden" class="user_id" value={{ Auth::guard('user')->user()->id}}>
                                @endif
                            </div>
                            <div class="feed-tweet-x">
                                <div class="tweet-img">
                                    @if($orig->profile_pic === null)
                                        <img src="{{ asset('img/no-pic.png') }}"alt=""> 
                                    @else 
                                        <img src="{{ asset('uploads/').'/'.$orig->profile_pic }}"alt=""> 
                                    @endif
                                </div>
                                <div class="tweet-post">
                                    <div class="user-info">
                                        <b>{{ ucwords($orig->name) }}</b>
                                        <input type="hidden" class="user_id" value={{ Auth::guard('user')->user()->id}}>
                                        <span style="color:rgb(169,174,182);">
                                            {{ 
                                                '@'.$orig->username .". "
                                            }}
                                            {{
                                                Carbon\Carbon::create($orig->date_posted)->diffForHumans()
                                            }}
                                        </span>
                                    </div>
                                    <div class="caption">
                                        <p>{{ $tweet->tweet }}</p>
                                    </div>
                                </div>
                        </div>
                        @elseif($tweet->context === 'original')
    <div class="tweet-img">
        @if($tweet->profile_pic === null)
            <img src="{{ asset('img/no-pic.png') }}"alt=""> 
        @else 
            <img src="{{ asset('uploads/').'/'.$tweet->profile_pic }}"alt=""> 
        @endif
    </div>
    <div class="tweet-post">
        <div class="user-info">
                <b>{{ ucwords($tweet->name) }}</b>
                <input type="hidden" class="user_id" value={{ Auth::guard('user')->user()->id}}>
                <span style="color:rgb(169,174,182);">
                        {{ '@'.$tweet->username .". "}}{{Carbon\Carbon::create($tweet->date)->diffForHumans()}}
                </span>
        </div>
        <div class="caption">
                    <p>{{ $tweet->tweet }}</p>
        </div>
        <div class="action">
            <div class="c-div">
                <button data-toggle="modal" data-target="#tweet{{  $tweet->tweet_id }}">
                    <i class="far fa-comment {{ App\Comment::where('user_id',Auth::guard('user')->user()->id)->where('tweet_id',$tweet->tweet_id)->first() ? 'active' : '' }}"></i>
                        @php
                            $comments = App\Tweet::find($tweet->tweet_id)->numComment();
                        @endphp
                    <span class="num-comments {{ App\Comment::where('user_id',Auth::guard('user')->user()->id)->where('tweet_id',$tweet->tweet_id)->first() ? 'active' : '' }}">
                        {{ $comments > 0 ? $comments : '' }}
                    </span>
                </button>
            </div>
            <div class="rt-div">
                <button data-toggle="modal" data-target="#retweet{{  $tweet->tweet_id }}">
                    <i class="fas fa-retweet {{ App\Retweet::where('user_id',Auth::guard('user')->user()->id)->where('tweet_id',$tweet->tweet_id)->first() ? 'active' : '' }}"></i>
                        @php
                            $retweets = App\Tweet::find($tweet->tweet_id)->numRetweet();
                        @endphp
                    <span class="num-retweets {{ App\Retweet::where('user_id',Auth::guard('user')->user()->id)->where('tweet_id',$tweet->tweet_id)->first() ? 'active' : '' }}">
                        {{ $retweets > 0 ? $retweets : '' }}
                    </span>
                </button>
            </div>
            <div class="l-div">
               <button class="like-btn" id="{{ $tweet->tweet_id }}" data-poster="{{ $tweet->tweet_user_id }}">
                    <i class="far fa-heart {{App\Like::where('user_id',Auth::guard('user')->user()->id)->where('tweet_id',$tweet->tweet_id)->first() ? 'active' : ''}}"></i>
                </button>
                @php
                    $likes = App\Tweet::find($tweet->tweet_id)->getLikes();
                @endphp
                <span class="num-likes {{App\Like::where('user_id',Auth::guard('user')->user()->id)->where('tweet_id',$tweet->tweet_id)->first() ? 'active' : ''}}" id="{{ $tweet->tweet_id }}">
                    {{ $likes > 0 ? $likes : '' }}
                </span>
            </div>
                <div>
                    <a href="{{ route('view-thread',$tweet->tweet_id) }}">
                        <button title="View Thread">
                                <i class="far fa-eye"></i>
                        </button>
                    </a>
                </div>
            </div>
    </div>
                @endif
            </div>
        @endforeach
    @endif
</div>
@include('modals.edit-profile')
@section('other')
@include('layouts.others')
@endsection
@endsection
