$(document).ready(function(){
// VARIABLES   
    const checkDiv = $('.succ-msg');
    const checkDivErr = $('.err-msg');
    const follow = $('.follow');
    const userId = $('.user_id').val();

    $('.loader-container').hide()
    $('.loader').hide();
    const fadeIn = 500;
    const fadeOut = 500;

    const notifId = 0;

    checkSessonDiv();
    checkErrDiv();


    follow.on('click',function(){
        const currUserId = parseInt($('.currUserId').val());
        let followingUserId = $(this).attr('id');
        $.ajax({
            url:'/follow',
            method:'post',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data:{
                user_id:currUserId,
                following_user_id:followingUserId,
            },
            beforeSend:function(){
               setTimeout(function(){
                $('.loader-container').show()
                $('.loader').show();
               },500);
            },
            success:function(data){
                console.log(data);
            },
            error:function(err){
                console.log(err);
            },
            complete:function(){
                setTimeout(function(){
                    $('.loader-container').hide()
                    $('.loader').hide();
                   },1500);
                location.reload();
            }
        })
    });


   $('.like-btn').on('click',function(){
       let tweetId = $(this).attr('id');
       let posterId = $(this).attr('data-poster');
       $.ajax({
           url:'/like-tweet',
           method:'post',
           data:{
               user_id:userId,
               tweet_id:tweetId,
               posterId:posterId
            },
           headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
           success:function(data){
              
              // $('.num-likes').html(data.num_likes);
              checkSpan(data.tweet_id,data.num_likes);
            
          
           },
           error:function(err){
               console.log(err);
           }
       })
   })
   

// FUNCTIONS
    function checkSessonDiv(){
        if(checkDiv.is(':visible')){
            checkDiv.fadeIn(500);
            setInterval(function(){
                checkDiv.fadeOut(500);
            },5000);
        }
    }

    function checkSpan(tweet_id,num_likes){
        const num_span = document.querySelectorAll('.num-likes');
        num_span.forEach(x =>{
            let spanId = $(x).attr('id');
            if(spanId == tweet_id){
               x.innerHTML = num_likes == 0 ? '' : num_likes;
               x.classList.add('active');
               x.previousElementSibling.childNodes[0].classList.add('active');
            }
        })
    }

    function checkErrDiv(){
        if(checkDivErr.is(':visible')){
            checkDivErr.fadeIn(500);
            setInterval(function(){
                checkDivErr.fadeOut(500);
            },5000);
        }
    }
  
  
})