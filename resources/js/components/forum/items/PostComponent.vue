<template>
  <div class="col-lg-12">
    <div v-if="posts.length">
      <div class="row" style="margin-bottom: 2%;">
        <div class="col-md-2 col-md-offset-1">
          <select class="form-control">
            <option>topics</option>
          </select>
        </div>
        <div class="col-md-2">
          <select class="form-control">
              <option>climbing regions</option>
          </select></div>
        <div class="col-md-2">
          <select class="form-control">
              <option>sector</option>
          </select></div>
        <div class="col-md-2">
          <select class="form-control">
              <option>route</option>
          </select></div>
        <div class="col-md-2">
          <select class="form-control">
              <option>mtp</option>
          </select></div>
        </div>
    </div>
    <div v-if="posts.length">
      <div class="cardbox shadow-lg bg-white" v-for="post in posts" :key="post.id">
        <div class="cardbox-heading">
          <div class="media m-0">

            <div class="d-flex mr-3">
              <router-link style="font-size: 1.5em;" :to="'user/'+post['user'].id" exact class="site_title">
                <img class="img-fluid rounded-circle" v-if="post['user'].image" :src="'/public/images/user_img/user_demo_img.gif'" :alt="post['user'].name + ' ' + post['user'].surname">
                <img class="img-fluid rounded-circle" v-else :src="'/public/images/site_img/user_demo_img.gif'" :alt="post['user'].name + ' ' + post['user'].surname">
              </router-link>
            </div>

            <div class="media-body">

              <div class="row m-0">
                <router-link style="font-size: 1.5em;" :to="'user/'+post['user'].id" exact class="site_title">
                  <p class="m-0">{{post['user'].name + ' ' + post['user'].surname}}</p>
                </router-link>
              </div>
              
              <div class="row m-0">
                <small><span v-if="post['post'].created_at" >{{ post['post'].created_at }}</span></small>
              </div>

            </div>

          </div>
        </div>

        <div class="cardbox-item container"  v-if="post['post'].text">
          <p style="background-color: #eee;text-align: center;font-size: 1.5em;">{{ post['post'].text }}</p>
        </div>
          
        <div class="cardbox-item" v-if="post['post'].image">
          <forum-img :img_class="'img-fluid'" :src="'images/forum_post_img/'+post['post'].image" :alt="'Image'" />
        </div>

        <div class="cardbox-base">
          <ul class="m-0">
            <li><a class="like_button" @click="add_like(post['post'].id)"><i class="fa fa-thumbs-up"></i></a></li>
            <li><a><span>{{ get_post_likes(post['post'].id) }} Likes</span></a></li>
          </ul>			   
        </div>

        <div class="cardbox-comments">
          <div class="row">

            <span class="comment-avatar float-left" style="float: left;">
              <a href="">
                <img class="rounded-circle" src="http://www.themashabrand.com/templates/bootsnipp/post/assets/img/users/6.jpg" alt="...">
              </a>                            
            </span>

            <div class="post_comment_porm">
              <!-- <input placeholder="Write a comment" type="text" v-model="comment_text"> -->
              <textarea id="w3review" name="w3review" class="comment_text_area" rows="3" cols="50"></textarea>
              <button @click="add_comment()">Add</button>
            </div>

            <!-- {{ this.$authUser }} -->
            <!-- {{this.$siteData.title}} -->

            <!-- <div class="media m-0">
              <div class="d-flex mr-3">
              <a href="">
                <img class="img-fluid rounded-circle" :src="'/public/images/user_img/user_demo_img.gif'" :alt="post['user'].name + ' ' + post['user'].surname">
              </a>
              </div>
              <div class="media-body">
                <div class="post_comment_porm">
                  <input placeholder="Write a comment" type="text">
                </div>
              </div>
            </div> -->
            
          </div>
          <div class="row">
            <div class="media m-0">

              <div class="d-flex mr-3">
                <a href="">
                  <img class="img-fluid rounded-circle" v-if="post['user'].image" :src="'/public/images/user_img/user_demo_img.gif'" :alt="post['user'].name + ' ' + post['user'].surname">
                  <img class="img-fluid rounded-circle" v-else :src="'/public/images/site_img/user_demo_img.gif'" :alt="post['user'].name + ' ' + post['user'].surname">
                </a>
              </div>

              <div class="media-body">
                <div class="row  m-0">
                  <p class="m-0">{{post['user'].name + ' ' + post['user'].surname}}</p>
                </div>
                <div class="row comments_list m-0 comment_text">
                  <p>your comment here</p>
                </div>
              </div>

            </div>
          </div>

        </div>
      </div>
    </div>

    <div v-else class="cardbox shadow-lg bg-white">
      <h2>no posts</h2>
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
                likes: 0,
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

            get_post_likes(id){
                // var likes = axios.get("../api/posts/get_likes/"+id)
                return(axios.get("../api/posts/get_likes/"+id).data);
            },
            get_post_comments(id){
                axios
                .get("../api/post/get_comments/"+id)
                .then(response => {
                    this.comments = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },

            add_like(id){
              alert(id)
            },
            add_comment(){
              alert('add')
            },

            del_comment(){

            },
            del_like(){

            }
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
.comment_text p{
  color: #000000 !important
}
.hero {
  padding: 6.25rem 0px !important;
  margin: 0px !important;
}
.cardbox {
  border-radius: 3px;
  margin-bottom: 20px;
  padding: 0px !important;
  border: 1px;
  border-style: solid;
  border-color: rgb(186 186 186);
  border-radius: 1em;
}
.comment_text_area{
  float: left;
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

  float: left;
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
  /* font-size: 0px;	 */
  text-align: center;
  display: inline-block;
  margin-left: 2em;
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
.post_comment_porm {
  position: relative;
  right: -45px;
  top: -40px;
  margin-bottom: -34px;
  border: 4px solid #f4f4f4;
  width: 100%;
  overflow: hidden;
  /* margin-right: 68px; */
}
.post_comment_porm input[type="text"] {
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
  float: left;
  }
.post_comment_porm button {
  position: absolute;
  right: 0;
  top: 0px;
  border: none;
  background-color: transparent;
  color: #bbbbbb;
  padding: 7px 7px;
  cursor: pointer;
  
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
}
.post_comment_porm button i {
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