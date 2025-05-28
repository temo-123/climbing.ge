<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="error-template" v-if="loading">
                    <img :src="'../../../public/images/site_img/loading.gif'" alt="loading">
                </div>

                <div class="error-template" v-if="!loading">
                    <h1> {{ $t("unfollow.title") }} </h1>
                    <div class="error-details">
                        {{ $t("unfollow.description") }}
                    </div>
                    <div class="error-actions">
                        <router-link :to="'/'" exact class="btn btn-primary btn-lg">
                            <span class="glyphicon glyphicon-home"></span>
                            {{ $t("unfollow.go_home") }}
                        </router-link>
                    </div>
                    <h2>By Climbing.ge</h2>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import servicesListComponent from '../../global_components/ServicesListComponent.vue'
    import messageComponent from '../../global_components/MessageComponent'
    // import metaData from '../items/MetaDataComponent'
    export default {
        components: {
            // metaData,
            messageComponent,
            servicesListComponent
        },
        data() {
            return {
                message: [],
                loading: false,
            }
        },
        mounted() { 
            this.unfollow()
        },
        methods: {
            unfollow(){
                this.loading = true
                if(confirm('Are you sure, you want unfollow?')){
                    axios
                    .post('/follow/del_follower/'+this.$route.params.id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        alert(Response.data)
                    })
                    .catch((error) => {
                        // console.log(error)
                    })
                    .finally(() => (this.loading = false));
                }
                else{
                    this.loading = false
                    this.$router.push({ name: 'index' });
                }
            }
        }
    }
</script>

<style>
/*  */
</style>