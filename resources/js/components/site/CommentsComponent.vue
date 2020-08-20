<template>
    <div class="wrap">
        <button @click="update" class="but but-default" v-if="!is_refresh">Refresh  {{id}}</button>
        <span class="badge badge-primare mb-1" v-if="is_refresh">Updating...</span>
        <ul>
            {{article_id}}
            <li v-for="comment in comments" :key="comment.id">
                <div class="row" v-if="comment.article_id == article_id">
                    <hr>
                    <div class="col-md-2"><img :src="'../../public/images/site_img/demo/user.png'" /></div>
                    <div class="col-md-10">
                        <h2><strong>{{comment.name}} {{comment.surname}}</strong> [ {{comment.email}} ]</h2>
                        <div>
                            <p>{{comment.text}}</p>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: [
            "article_id"
        ],
        data: function() {
            return{
                comments: [],
                is_refresh: false,
                id: 0
            }
        },
        mounted() {
            article_id = this.article_id
            this.update()
        },
        methods: {
            update: function(){
                this.is_refresh = true
                axios.get('/get_comments').then(response => {
                    console.log(response)
                    this.comments = response.data
                    this.is_refresh = false
                    this.id++
                });
                    
            }
        },
    }
</script>

<style type="text/css">
    /* div.wrap {
        width: 90%;
        margin: 0 auto 1em auto;
        position: relative;
    }

    div.wrap:first-child {
        margin-top: 1em;
    }

    div.comment {
        font-size: 1em;
        position: relative;
        margin-left: 60px;
        border-radius: 0.75em 0.75em 0.75em 0.75em;
        background-color: #eaeaea;
        line-height: 1.4em;
        font-family: Helvetica;
    }

    div.comment::before {
        content: attr(data-owner);
        border-radius: 0.75em 0.75em 0 0;
        background-color: #ccc;
        display: block;
        text-indent: 10%;
        border-bottom: 3px solid #999;
    }

    div.comment::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        border: 10px solid transparent;
        border-right: 10px solid #ccc;
        margin: -10px 0 0 -20px;
    }

    div.comment p {
        width: 80%;
        margin: 0 auto 1em auto;
    }

    img {
        position: absolute;
        top: 50%;
        width: 50px;
        float: left;
        border-radius: 10px;
        margin-top: -25px;
    }

    p + ol.postscript {
        width: 80%;
        font-size: 0.8em;
        margin: -0.5em auto 0 auto;
    }
    ol.postscript::after {
        content: '';
        height: 0.5em;
        display: block;
        clear: both;
    }
    ol.postscript li {
        float: left;
        margin-right: 0.5em;
    }
    ol.postscript li.date {
        float: right;
        margin-right: 0;
    }

    .wrap a:link,
    .wrap a:visited {
        color: #333;
        text-decoration: none;
        border-bottom: 1px solid #333;
    }

    .wrap a:hover,
    .wrap a:active,
    .wrap a:focus {
        color: #f00;
        border-bottom: 1px solid #f00;
    } */
</style>
