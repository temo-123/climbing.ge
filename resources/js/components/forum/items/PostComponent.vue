<template>
  <div class="col-lg-8 col-md-offset-2 ">
    <div class="cardbox shadow-lg bg-white" v-for="post in posts" :key="post.id">
      <div class="cardbox-heading">
        <!-- <div class="dropdown float-right">
          <div class="dropdown-menu dropdown-scale dropdown-menu-right" role="menu" style="position: absolute; transform: translate3d(-136px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
            <a class="dropdown-item" href="#">Hide post</a>
            <a class="dropdown-item" href="#">Stop following</a>
            <a class="dropdown-item" href="#">Report</a>
          </div>
        </div> -->
        <div class="media m-0">
          <div class="d-flex mr-3">
            <a href="">
              <img class="img-fluid rounded-circle" v-if="post['user'].image" :src="'/public/images/user_img/user_demo_img.gif'" :alt="post['user'].name + ' ' + post['user'].surname">
              <img class="img-fluid rounded-circle" v-else :src="'/public/images/site_img/user_demo_img.gif'" :alt="post['user'].name + ' ' + post['user'].surname">
            </a>
          </div>
          <div class="media-body">
            <p class="m-0">{{post['user'].name + ' ' + post['user'].surname}}</p>
            <small><span v-if="post['user'].city || post['user'].country">{{post['user'].city + ', ' + post['user'].country}}</span></small>
            <small><span v-if="post.created_at" >{{ post['post'].created_at }}</span></small>
          </div>
        </div>
      </div>

      <div class="cardbox-item"  v-if="post['post'].text">
        <p style="background-color: #eee;text-align: center;font-size: 1.5em;">{{ post['post'].text }}</p>
      </div>
        
      <div class="cardbox-item" v-if="post['post'].image">
        <img class="img-fluid" src="http://www.themashabrand.com/templates/bootsnipp/post/assets/img/1.jpg" alt="Image">
      </div>

      <div class="cardbox-base">
        <ul class="m-0">
          <li><a class="like_button"><i class="fa fa-thumbs-up"></i></a></li>
          <li><a><span>242 Likes</span></a></li>
        </ul>			   
      </div>

      <div class="cardbox-comments">
        <span class="comment-avatar float-left">
          <a href="">
            <img class="rounded-circle" src="http://www.themashabrand.com/templates/bootsnipp/post/assets/img/users/6.jpg" alt="...">
          </a>                            
        </span>
        <!-- {{ this.$authUser }} -->
        <!-- {{this.$siteData.title}} -->
        <div class="search">
          <input placeholder="Write a comment" type="text">
        </div>
      </div>
    </div>

  </div>
</template>

<script>
    export default {
        components: {
            // 
        },
        data(){
            return {
                posts: [],
                post_user_name: '',
            }
        },
        mounted() {
            this.get_postss()
        },
        methods: {
            get_postss(){
                axios
                .get("../api/post/")
                .then(response => {
                    this.posts = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
        }
    }
</script>

<style>
/*
*
* ===========================================================
*     HERO SECTION  https://bootsnipp.com/snippets/VgkqV
* ===========================================================
*
*/
.hero {
  padding: 6.25rem 0px !important;
  margin: 0px !important;
}
.cardbox {
  border-radius: 3px;
  margin-bottom: 20px;
  padding: 0px !important;
}

/* ------------------------------- */
/* Cardbox Heading
---------------------------------- */
.cardbox .cardbox-heading {
  padding: 16px 16px 16px 0;
  margin: 0;
}
.cardbox .btn-flat.btn-flat-icon {
 border-radius: 50%;
 font-size: 24px;
 height: 32px;
 width: 32px;
 padding: 0;
 overflow: hidden;
 color: #fff !important;
 background: #b5b6b6;
 
display: flex;
flex-direction: column;
justify-content: center;
align-items: center;
text-align: center;
}
.cardbox .float-right .dropdown-menu{
  position: relative;
  left: 13px !important;  
}
.cardbox .float-right a:hover{
  background: #f4f4f4 !important;	
}
.cardbox .float-right a.dropdown-item {
  display: block;
  width: 100%;
  padding: 4px 0px 4px 10px;
  clear: both;
  font-weight: 400;
  font-family: 'Abhaya Libre', serif;
  font-size: 14px !important;
  color: #848484;
  text-align: inherit;
  white-space: nowrap;
  background: 0 0;
  border: 0;
}

/* ------------------------------- */
/* Media Section
---------------------------------- */
.media {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-align: start;
  align-items: flex-start;
}
.d-flex {
  display: -ms-flexbox !important;
  display: flex !important;
}
.media .mr-3{
  margin-right: 1rem !important;
}
.media img{
  width: 48px !important;
  height: 48px !important;
  padding: 2px;
  border: 2px solid #f4f4f4;
} 
.media-body {
  -ms-flex: 1;
  flex: 1;
  padding: .4rem !important;
}
.media-body p{
  font-family: 'Rokkitt', serif;	
  font-weight: 500 !important;
  font-size: 14px;
  color: #88898a;
}
.media-body small span{
  font-family: 'Rokkitt', serif;	
  font-size: 12px;
  color: #aaa;
  margin-right: 10px;
}


/* ------------------------------- */
/* Cardbox Item
---------------------------------- */
.cardbox .cardbox-item {
    position: relative;
    display: block;
}
/* .cardbox .cardbox-item img{
} */
.img-responsive{
    display: block;
    max-width: 100%;
    height: auto;
}	
.fw {
    width: 100% !important;
	height: auto;
}


/* ------------------------------- */
/* Cardbox Base
---------------------------------- */
.cardbox-base{
 border-bottom: 2px solid #f4f4f4;
}	
.cardbox-base ul{
 /* margin: 10px 0px 10px 15px!important;  */
 padding: 10px !important;
 font-size: 0px;	
  display: inline-block;
}
.cardbox-base li {
  list-style: none;
  margin: 0px 0px 0px -8px !important;
  padding: 0px 0px 0px 0px !important;
  display: inline-block;
}

.cardbox-base li a{
  margin: 0px !important;
  padding: 0px !important;
}
.cardbox-base li a i{
 position: relative;
 top: 4px; 
 font-size: 26px;
 color: #8d8d8d;
 margin-right: 15px;
}
.cardbox-base li a i :hover{
    color: #00C4CF;
    cursor: pointer;
}
.cardbox-base li a span{
 font-family: 'Rokkitt', serif;
 font-size: 14px;
 color: #8d8d8d;
 /* margin-left: 20px; */
 position: relative;
 /* top: 5px;  */
}
.cardbox-base li a em{
 font-family: 'Rokkitt', serif;
 font-size: 14px;
 color: #8d8d8d;
 position: relative;
 top: 3px; 
}
.cardbox-base li a img{
  width: 25px;
  height: 25px;
  margin: 0px !important;
  border: 2px solid #fff;
}


/* ------------------------------- */
/* Cardbox Comments
---------------------------------- */
.cardbox-comments{
  padding: 10px 0 0 0 !important;
  font-size: 0px;	
  text-align: center;
  display: inline-block;
}
.cardbox-comments .comment-avatar img{
  margin-top: 1px;
  margin-right: 10px;
  position: relative;
  display: inline-block;
  text-align: center;
  width: 40px;
  height: 40px;
  /* float: left; */
}
.cardbox-comments .comment-body {
  overflow: auto;
}
.search {
 /* position: relative;
 right: -60px;
 top: -40px;
 margin-bottom: -40px;
 border: 2px solid #f4f4f4;	
 width: 100%;
 overflow: hidden; */
    position: relative;
    right: -45px;
    top: -40px;
    margin-bottom: -34px;
    border: 4px solid #f4f4f4;
    width: 100%;
    overflow: hidden;
    margin-right: 68px;
}
.search input[type="text"] {
 background-color: #fff;
 line-height: 10px;
 padding: 10px 60px 8px 10px;
 border: none;
 border-radius: 4px;
 width: 350px;
 font-family: 'Rokkitt', serif;
 font-size: 14px;
 color: #8d8d8d;
 height: inherit;
 font-weight: 700;
}
.search button {
 position: absolute;
 right: 0;
 top: 0px;
 border: none;
 background-color: transparent;
 color: #bbbbbb;
 padding: 15px 25px;
 cursor: pointer;
 
 display: flex;
flex-direction: column;
justify-content: center;
align-items: center;
text-align: center;
}
.search button i {
 font-size: 20px;
 line-height: 30px;
 display: block;
}
.m-0{
    margin: 0% !important;
}


/* ------------------------------- */
/* Author
---------------------------------- */
.author a{
 font-family: 'Rokkitt', serif;
 font-size: 16px;
 color: #00C4CF;
}
.author p{
 font-family: 'Rokkitt', serif;
 font-size: 16px;
 color: #8d8d8d;
}
</style>