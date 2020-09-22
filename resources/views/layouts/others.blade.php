<div class="others">
    <div class="search-bar">
        <input type="text" placeholder="Search Twitter">
    </div>
    <div class="see-follow">
        <header>
            <h4>Who to follow</h4>
        </header>
    @if(count($people) > 0)
        @foreach($people as $person)
            <div class="people">
                <div class="people-img">
                    @if($person->profile_pic === NULL)
                        <a href="{{ route('profile',$person->userId) }}">
                            <img src="{{ asset('img/no-pic.png') }}" alt="">
                       
                    @else
                        <a href="{{ route('profile',$person->userId) }}">
                         <img src="{{ asset('uploads/')."/".$person->profile_pic }}" alt="">
                        </a>
                    @endif
                </div>
                <div class="people-info">
                    <div class="name">
                        <a href="{{ route('profile',$person->userId) }}" style="color:#fff;"> <p>{{$person->fullName}}</p> </a>
                        <span>{{ "@".$person->username }}</span>
                        <input type="hidden" class="currUserId" id="currUserId" value="{{ Auth::guard('user')->user()->id }}">
                        <input type="hidden" class="following_user_id" id="{{ $person->userId }}"  value="{{ $person->userId }}">
                        <br>
                        <small style="color:rgb(136,153,166);">
                            @php
                                    $follower = DB::table('followings as f')
                                                    ->select('f.user_id','f.following_user_id',DB::raw('concat(u.fname," ",u.lname) as name'))
                                                    ->leftJoin('users as u','u.id','=','f.user_id')
                                                    ->where('f.following_user_id',Auth::guard('user')->user()->id)
                                                    ->where('f.user_id',$person->userId)
                                                    ->first(); 
                                    echo $follower ? 'follows you' : '';
                             @endphp
                        </small>
                    </div>
                    <div class="f-btn">
                        <button class="follow" id="{{$person->userId }}">Follow</button>
                    </div>
                </div>
            </div>
        @endforeach
    @else 
        <div class="people">
            <div class="people-info"> 
                <h5 style="text-align:center;">No person to follow.</h5>
            </div>
        </div>
    @endif
    </div>
</div>