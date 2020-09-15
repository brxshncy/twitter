<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://kit.fontawesome.com/54939ff6a9.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Twitter</title>
</head>
<body>
@if(session('succ'))
    <div class="succ-msg">
        <div class="succ-icon">
            <i class="fas fa-check-square"></i>
        </div>
        <div class="msg">
            <p>{{ session('succ') }}</p>
        </div>
    </div>
@endif
@if(session('err'))
    <div class="err-msg">
        <div class="succ-icon">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="msg">
        <p>{{ session('err') }}</p>
        </div>
    </div>
@endif
<div class="home">
    <div class="showcase">
        <div>
            <ul>
                <li><i class="fas fa-search"></i>Follow your interests.</li>
                <li><i class="fas fa-user-friends"></i>Hear what people are talking about.</li>
                <li><i class="fas fa-user-friends"></i>Join the conversation.</li>
            </ul>
        </div>
       
    </div>
    <div class="login-box">
        <div class="login">
        <form action="{{ route('login') }}" method='post'>
                @csrf
                <div class="form-input">
                    <input type="text" class="form-text" name="username">
                    <span></span>
                    <label for="">Username or Phone Number or Email</label>
                </div>
                <div class="form-input">
                    <input type="password" class="form-text" name="password">
                    <span></span>
                    <label for="">Password</label>
                </div>
                <div class="btn-container">
                    <button>Log in</button>
                </div>  
            </form>
        </div>
        <div class="head-line">
            <div>
                <i class="fab fa-twitter"></i>
                <h1>See what’s happening in the world right now</h1>
                <h5>Join Twitter today.</h5>
                <a href="{{ route('signup') }}">
                    <button>Sign up</button>
                 </a>
                <button class="btn-login">Log in</button>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/home.js') }}" type="text/javascript"></script>
</body>
</html>