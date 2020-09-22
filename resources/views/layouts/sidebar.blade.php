<div class="side-bar">
    <nav class="main-nav">
        <header>
            <a href="{{ route('home') }}">
                <i class="fab fa-twitter"></i>
            </a>
           
        </header>
        <ul>
        <li class="<?php  echo $title === 'home' ? 'active' : '' ?>"> <a href="{{ route('home') }}" ><i class="fas fa-home"></i>Home</a> </li>
            <li class="<?php  echo $title === 'message' ? 'active' : '' ?>"> <a href="{{ route('message') }}"> <i class="far fa-envelope"></i><a href="">Messages</a></li>
            <li class="<?php  echo $title === 'profile' ? 'active' : '' ?>"> 
                <a href="{{ route('profile',Auth::guard('user')->user()->id) }}"><i class="far fa-user"></i>
                    Profile 
                </a>
            </li>
             <notification route="{{ route('notifications') }}" :user_id="{{ Auth::guard('user')->user()->id }}" :unread="{{ Auth::guard('user')->user()->unreadNotifications }}"></notification>
            <li> 
                <a href="{{ route('logout') }}"><i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </li>
        </ul>
    </nav>
</div>