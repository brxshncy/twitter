<div class="modal fade" id="retweet{{ $tweet->tweet_id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
      <div class="modal-content comment-modal">
        <div class="modal-header" style="border-bottom:1px solid rgb(61,84,102);">
          <h5 class="modal-title" id="exampleModalLabel">{{ ucwords($tweet->fullname)."'s Tweet" }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" style="color:#fff;">&times;</span>
          </button>
        </div>
    <form action="{{ route('retweet') }}" method="post">
        @csrf
        @php
            $poster = DB::table('tweets as t')
                      ->select(DB::raw('CONCAT(u.fname," ",u.lname) as name'),'u.username as username','t.tweet as tweet','u.profile_pic as profile_pic','t.created_at as created_at')
                      ->leftJoin('users as u','u.id','=','t.user_id')
                      ->where('t.id',$tweet->tweet_id)
                      ->first();
        @endphp
        <div class="modal-body">
            <div class="comment-thread">
                <div class="tweet-img" style="margin-right:10px;">
                    @if($poster->profile_pic === null)
                         <img src="{{ asset('img/no-pic.png') }}"alt=""> 
                     @else 
                         <img src="{{ asset('uploads/')."/".$tweet->profile_pic }}" alt="">
                    @endif
             </div>
            <div class="info-tweet">
                    <div class="user-info" >
                            <b>{{ ucwords($poster->name) }}</b>
                            <span style="color:rgb(169,174,182);">
                                {{ 
                                    '@'.$poster->username ." . "
                                }}
                                {{
                                 ( Carbon\Carbon::create($poster->created_at)->diffForHumans())
                                }}
                            </span>
                    </div>
                        <div class="caption">
                            <p>{{ $poster->tweet }}</p>
                        </div>
                        <div class="reply-to">
                            <span>Retweeting tweet from</span> <a href="">{{ '@'.$poster->username }}</a>
                        </div>
                </div>

            </div>
            <div class="reply-div">
                <div class="tweet-img">
                    @if(Auth::guard('user')->user()->profile_pic === null)
                        <img src="{{ asset('img/no-pic.png') }}"alt=""> 
                    @else 
                        <img src="{{ asset('uploads/')."/".Auth::guard('user')->user()->profile_pic }}" alt="">
                    @endif
                </div>
                <div class="reply-input">
                    <textarea name="caption"  placeholder="Write your caption"></textarea>
                    <input type="hidden" name="user_id" value={{ Auth::guard('user')->user()->id}}>
                    <input type="hidden"   name="poster_id" value={{ $tweet->tweet_user_id  }}>
                    <input type="hidden" name="tweet_id" value={{ $tweet->tweet_id }}>
                </div>
            </div>
        </div>
        <div class="modal-footer" >
          <button type="submit" class="btn btn-primary">Reply</button>
        </div>
      </div>
    </form>
    </div>
  </div>