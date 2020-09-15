<div class="feed-tweet nocaption">
    <div class="user-info" style="padding:0 30px 5px">
        <span style="color:rgb(169,174,182); font-size:small;">   
            <i class="fas fa-retweet"></i> retweeted
        </span>
        <small style="color:#fff">{{ ucwords($tweet->fullname) }}</small>
        <input type="hidden" class="user_id" value={{ Auth::guard('user')->user()->id}}>
    </div>
    <div class="feed-tweet-x">
        <div class="tweet-img">
            @if($tweet->profile_pic === null)
                <img src="{{ asset('img/no-pic.png') }}"alt=""> 
            @else 
                <img src="{{ asset('uploads/').'/'.$tweet->profile_pic }}"alt=""> 
            @endif
        </div>
        <div class="tweet-post">
            <div class="user-info">
                <b>{{ ucwords($tweet->fullname) }}</b>
                <input type="hidden" class="user_id" value={{ Auth::guard('user')->user()->id}}>
                <span style="color:rgb(169,174,182);">
                    {{ 
                        '@'.$tweet->username .". "
                    }}
                    {{
                        Carbon\Carbon::create($tweet->created_at)->diffForHumans()
                    }}
                </span>
            </div>
            <div class="caption">
                <p>{{ $tweet->tweet }}</p>
            </div>
            <div class="action">
                <button data-toggle="modal" data-target="#tweet{{  $tweet->tweet_id }}">
                    <i class="far fa-comment {{ App\Comment::where('user_id',Auth::guard('user')->user()->id)->where('tweet_id',$tweet->tweet_id)->first() ? 'active' : '' }}"></i>
                    @php
                        $comments = App\Tweet::find($tweet->tweet_id)->numComment();
                    @endphp
                    <span class="num-comments {{ App\Comment::where('user_id',Auth::guard('user')->user()->id)->where('tweet_id',$tweet->tweet_id)->first() ? 'active' : '' }}">
                        {{ $comments > 0 ? $comments : '' }}
                    </span>
                </button>
                <button data-toggle="modal" data-target="#retweet{{  $tweet->tweet_id }}">
                        <i class="fas fa-retweet"></i>
                </button>
                <div class="">
                    <button class="like-btn" id="{{ $tweet->tweet_id }}">
                        <i class="far fa-heart {{   App\Like::where('user_id',Auth::guard('user')->user()->id)->where('tweet_id',$tweet->tweet_id)->first() ? 'active' : ''}}"></i>
                    </button>
                    @php
                            $likes = App\Tweet::find($tweet->tweet_id)->getLikes();
                    @endphp
                    <span class="num-likes {{   App\Like::where('user_id',Auth::guard('user')->user()->id)->where('tweet_id',$tweet->tweet_id)->first() ? 'active' : ''}}"" id="{{ $tweet->tweet_id }}">
                            {{ $likes > 0 ? $likes : '' }}
                    </span>
                </div>
                <a href="{{ route('view-thread',$tweet->tweet_id) }}">
                    <button title="View Thread">
                        <i class="far fa-eye"></i>
                    </button>
                </a>
            </div>
        </div>
    </div>
<!-- Modal -->
@include('modals.comment')
@include('modals.retweet')
</div>