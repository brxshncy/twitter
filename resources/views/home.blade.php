<!-- In this view, I have made some filtering queries by using Query Builder -->
@extends('layouts.main')
@section('title')
Home / Twitter
    @php
        $title = "home";
    @endphp
@endsection
@section('sidebar')
    @include('layouts.sidebar')
@endsection
@section('center')
<div class="feed">
    <div class="feed-header">
        <h5>Home</h5>
    </div>
    <div class="post-container">
        <div class="user-img">
          @if(Auth::guard('user')->user()->profile_pic === null)
            <img src="{{ asset('img/no-pic.png') }}"alt=""> 
            @else 
            <img src="{{ asset('uploads/').'/'.Auth::guard('user')->user()->profile_pic }}"alt=""> 
          @endif
        </div>
        <div class="user-post">
            <form action="{{ route('post-tweet') }}" method="post">
                @csrf
                <div>
                    <textarea name="tweet"  cols="30" rows="4" placeholder="Whats happening?"></textarea>
                    <input type="hidden" name="user_id" value="{{ Auth::guard('user')->user()->id }}" id="user_id">
                </div>
                <div class="btn-list">
                    <button>Tweet</button>
                </div>
            </form>
        </div>
    </div>


@if(count($tweets) > 0) <!-- loop through each tweets that is being passed in from HomeController -->
    @foreach($tweets as $tweet)
<div class="feed-tweet  <?php echo $tweet->context === 'retweet' ? 'nocaption' : ''  ?>">
<!---  My query retrieves all the tweets and then I filtered out if the tweet is an original tweet or a retweet.
       I filtered it out because I have dedicated css for both original tweets and retweeted tweets.
       Just like the current Twitter App.
--->
@if($tweet->context ==='retweet')
<!-- 
    I  retrieve the user of the tweet that is being retweeted.
-->
            @php
                $orig = DB::table('tweets as t')
                       ->select('u.profile_pic as profile_pic',DB::raw('CONCAT(u.fname," ",u.lname) as name'),'u.username as username','rt.created_at as date_posted')
                       ->leftJoin('users as u','u.id','=','t.user_id')
                       ->leftJoin('retweets as rt','rt.tweet_id','=','t.id')
                       ->where('rt.tweet_id','=',$tweet->tweet_id)
                       ->first();
            @endphp
<div class="user-info" style="padding:0 30px 5px">
    <!--  Check if this specific retweet is being retweeted by the current logged in user -->
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
            <small style="color:#fff">{{ ucwords($tweet->fullname) }}</small>
            <input type="hidden" class="user_id" value={{ Auth::guard('user')->user()->id}}>
        @endif
</div>
    <div class="feed-tweet-x">
            <div class="tweet-img">
                <!--  I have dedicated Image for users who don't upload their  picture just like you see on modern Social Media Apps -->
                @if($orig->profile_pic === null)
                    <img src="{{ asset('img/no-pic.png') }}"alt=""> 
                @else 
                    <img src="{{ asset('uploads/').'/'.$orig->profile_pic }}"alt=""> 
                @endif
            </div>
            <div class="tweet-post">
                <div class="user-info">
                    <!-- I display the original user info from the tweet that is being retweeted !-->
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
<!-- If the tweet is orignal then display the user info of the tweet -->
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
            <a href="{{ route('profile',$tweet->tweet_user_id)  }}" style="color:#fff; text-decoration:none;"><b>{{ ucwords($tweet->fullname) }}</b></a>
                <input type="hidden" class="user_id" value={{ Auth::guard('user')->user()->id}}>
                <span style="color:rgb(169,174,182);">
                        {{ '@'.$tweet->username .". "}}{{Carbon\Carbon::create($tweet->created_at)->diffForHumans()}}
                </span>
        </div>
        <div class="caption">
                    <p>{{ $tweet->tweet }}</p>
        </div>
        <div class="action">
            <!--  My project has comment,retweet, like functionalities just like the modern twitter-app  -->
            <!--  In frontend I have dedicated CSS if the current user liked, commented, or retweeted the tweet that is being displayed -->
            <div class="c-div">
                <button data-toggle="modal" data-target="#tweet{{  $tweet->tweet_id }}">
                    <i class="far fa-comment {{ App\Comment::where('user_id',Auth::guard('user')->user()->id)->where('tweet_id',$tweet->tweet_id)->first() ? 'active' : '' }}"></i>
                        @php
                            $comments = App\Tweet::find($tweet->tweet_id)->numComment();
                        @endphp
                    <span class="num-comments {{ App\Comment::where('user_id',Auth::guard('user')->user()->id)->where('tweet_id',$tweet->tweet_id)->first() ? 'active' : '' }}">
                        {{ $comments > 0 ? $comments : '' }} <!-- display the total comments of the specific tweet !-->
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
                        {{ $retweets > 0 ? $retweets : '' }} <!-- display the total retweets of the specific tweet !-->
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
                    {{ $likes > 0 ? $likes : '' }} <!-- display the total likes of the specific tweet !-->
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
        <!-- including some modals -->
        <!-- Modal -->
            @include('modals.comment')
            @include('modals.retweet')
        <!-- Modal -->
    </div>
    @endforeach
@endif
</div>




@endsection
@section('other')
@include('layouts.others')
@endsection