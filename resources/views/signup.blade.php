<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://kit.fontawesome.com/54939ff6a9.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/signup.css') }}">
    <title>Twitter / Signup</title>
</head>
<body>
    <div class="signup-container">
        <div class="box">
        <form action="{{ route('post-signup') }}" method="post">
                @csrf
                <div class="row justify-content-center">
                    <div class="col">
                        <i class="fab fa-twitter"></i>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h3>Create your account </h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">First Name</label>
                            <input type="text" class="form-control" name="fname">
                            @error('fname')
                                 <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="">Last Name</label>
                            <input type="text" class="form-control" name="lname">
                            @error('lname')
                            <small class="text-danger">{{ $message }}</small>
                       @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Address</label>
                            <input type="text" class="form-control" name="address">
                            @error('address')
                                 <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Phone Number</label>
                            <input type="text" class="form-control" name="contact">
                            @error('contact')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Birth Date</label>
                            <input type="date" class="form-control" name="bday">
                            @error('bday')
                              <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-md-6">
                        <div class="form-group">
                            <label for="">Gender</label>
                            <select name="gender" id="" class="form-control">
                                <option value="">Select</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                            @error('gender')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col col-md-6">
                        <div class="form-group">
                            <label for="">Username</label>
                           <input type="text" name="username" class="form-control">
                            @error('username')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-md-6">
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="text" class="form-control" name="email">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col col-md-6">
                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" class="form-control" name="password">
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col col-md-6">
                        <button class="btn-info btn btn-block" type="submit">Signup</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>