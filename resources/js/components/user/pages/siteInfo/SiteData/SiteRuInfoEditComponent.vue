<template>
    <div class="tabs"> 
        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>
        <div class="row">
            <div class="form-group">  
                <!-- <button type="submit" class="btn btn-primary" v-on:click="add_bisnes()" >Save updatid inof</button> -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col" >
                        <input type="radio" id="3" :value="3" v-model="tab_num">
                        
                        <label for="3" >Russion text</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row" v-show="tab_num == 3">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Site rusion version</h2>
                            <p class="lead">Site rusion version for site localisation.</p>
                        </div>
                    </div>
                    <localeInfoForm />
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import localeInfoForm from './forms/SiteLocaleDataEditComponent.vue'

export default {
    components: {
        localeInfoForm,
    },
    props: [
        'back_url',
    ],
    data(){
        return {
            tab_num: 3,
            site_ru_info: []
        }
    },
    mounted() {
        this.get_site_ru_data()
    },
    methods: {
        get_site_ru_data: function(){
            axios
            .get('../../api/siteData/get_site_ru_data')
            .then(response => {
                this.site_ru_info = response.data
            })
            .catch(
                error => console.log(error)
            );
        },
       

        go_back: function(back_action = false) {
            if(back_action == false){
                if(confirm('Are you sure, you want go back?')){
                    this.$router.go(-1)
                }
            }
            else{
                this.$router.go(-1)
            }
        },
    }
}
</script>