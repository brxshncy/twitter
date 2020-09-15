<template>
<li class="notification"> 
    <span class="notif"  data-count="">      
        {{ unreadNotification.length }}
    </span>       
    <i class="far fa-bell"></i>
    <a v-on:click="clearNotif" :href="route" >Notifications</a>
</li>
</template>
<script>
export default {
    props:['unread','user_id','route'],
    data(){
        return{
            unreadNotification : this.unread,
            liker_id:'',
            liker_name:'',
            liker_username:'',
            tweetId:'',
        }
    },
    methods:{
        clearNotif(){
            this.unreadNotification =[];
        }
    },
    mounted(){
        Echo.private(`App.User.${this.user_id}`)
            .notification((notification) => { 
            console.log(notification);     
            let newUnreadNotification =
             { data:
                {
                    liker_name:notification.name,
                    liker_username:notification.username,
                    tweetId: notification.tweet_id,
                    liker_id:notification.user_id
                }
            }
            this.unread.push(newUnreadNotification)
        })
    },
  
}
</script>