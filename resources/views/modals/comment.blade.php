<div class="modal fade" id="tweet{{ $tweet->tweet_id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
      <div class="modal-content comment-modal">
        <div class="modal-header" style="border-bottom:1px solid rgb(61,84,102);">
          <h5 class="modal-title" id="exampleModalLabel">{{ ucwords($tweet->fullname)."'s Tweet" }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" style="color:#fff;">&times;</span>
          </button>
        </div>
    <form action="{{ route('comment') }}" method="post">
        @csrf
        <div class="modal-body">
            <div class="comment-thread">
                <div class="tweet-img" style="margin-right:10px;">
                    @if($tweet->profile_pic === null)
                         <img src="{{ asset('img/no-pic.png') }}"alt=""> 
                     @else 
                         <img src="{{ asset('uploads/')."/".$tweet->profile_pic }}" alt="">
                    @endif
             </div>
            <div class="info-tweet">
                    <div class="user-info" >
                            <b>{{ ucwords($tweet->fullname) }}</b>
                            <span style="color:rgb(169,174,182);">
                                {{ 
                                    '@'.$tweet->username ." . "
                                }}
                                {{
                                    Carbon\Carbon::create($tweet->created_at)->diffForHumans()
                                }}
                            </span>
                    </div>
                        <div class="caption">
                            <p>{{ $tweet->tweet }}</p>
                        </div>
                        <div class="reply-to">
                            <span>Replying to</span> <a href="">{{ '@'.$tweet->username }}</a>
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
                    <textarea name="caption"  placeholder="Tweet your reply"></textarea>
                    <input type="hidden" name="user_id" value={{ Auth::guard('user')->user()->id}}>
                    @php
                        $poster = App\Tweet::where('id',$tweet->tweet_id)->first();
                    @endphp
                    <input type="hidden" name="poster_id" value={{ $poster->user_id  }}>
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