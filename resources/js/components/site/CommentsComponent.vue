<template>
    <div class="wrap">
        <button @click="update" class="but but-default" v-if="!is_refresh">Refresh  {{id}}</button>
        <span class="badge badge-primare mb-1" v-if="is_refresh">Updating...</span>
        <ul>
            <li v-for="comment in comments" :key="comment.id">
                <div class="row" v-if="comment.article_id == article_id_for_filtr">
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
            "article_id_for_filtr"
        ],
        data: function() {
            return{
                comments: [],
                is_refresh: false,
                id: 0,
            }
        },
        mounted() {
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
