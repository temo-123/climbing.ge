<template>
    <div class="tabs"> 
        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>
        <div class="row">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="update()" >Save updatid info</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col" >
                        <input type="radio" id="1" :value="1" v-model="tab_num">
                        
                        <label for="1" >Global info</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="2" :value="2" v-model="tab_num">
                        
                        <label for="2" >English text</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="3" :value="3" v-model="tab_num">
                        
                        <label for="3" >Georgian text</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="4" :value="4" v-model="tab_num">
                        
                        <label for="4" >Russion text</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row width_100" v-show="tab_num == 1">
                    <div class="jumbotron width_100">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="display-4"><span>Site global information</span></h2>
                                <p class="lead">Site global information.</p>
                            </div>
                        </div>
                    </div>
                    <globalInfoForm 
                        :global_data_prop="site_global_info"
                    />
                </div>
                <div class="row" v-show="tab_num == 2">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Site english version</h2>
                            <p class="lead">Site english version for site localisation.</p>
                        </div>
                    </div>
                    <localeInfoForm 
                        :locale_data_prop="site_us_info"
                    />
                </div>
                <div class="row" v-show="tab_num == 3">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Site rusion version</h2>
                            <p class="lead">Site rusion version for site localisation.</p>
                        </div>
                    </div>
                    <localeInfoForm 
                        :locale_data_prop="site_ru_info"
                    />
                </div>
                <div class="row" v-show="tab_num == 4">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Site georgian version</h2>
                            <p class="lead">Site georgian version for site localisation.</p>
                        </div>
                    </div>
                    <localeInfoForm 
                        :locale_data_prop="site_ka_info"
                    />
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import globalInfoForm from './forms/SiteGlobalDataEditComponent.vue'
import localeInfoForm from './forms/SiteLocaleDataEditComponent.vue'

export default {
    components: {
        globalInfoForm,
        localeInfoForm,
    },
    data(){
        return {
            tab_num: 1,
            site_global_info: [],
            site_ka_info: [],
            site_ru_info: [],
            site_us_info: [],
        }
    },
    mounted() {
        this.site_data()
    },
    methods: {
        update(){
            axios
            .post('../../api/siteData/edit_site_data',{
                // site_us_info: this.site_us_info
                site_global_info: this.site_global_info,
                site_ka_info: this.site_ka_info,
                site_ru_info: this.site_ru_info,
                site_us_info: this.site_us_info,
            })
            .then(response => {
                // this.site_us_info = response.data
                this.go_back()
            })
            .catch(
                error => console.log(error)
            );
        },

        site_data(){
            this.get_site_global_data()
            this.get_site_ka_data()
            this.get_site_ru_data()
            this.get_site_us_data()
        },
       
        get_site_global_data: function(){
            axios
            .get('../../../api/siteData/get_site_global_data')
            .then(response => {
                this.site_global_info = response.data
            })
            .catch(
                error => console.log(error)
            );
        },
        get_site_ka_data: function(){
            axios
            .get('../../../api/siteData/get_site_ka_data')
            .then(response => {
                this.site_ka_info = response.data
            })
            .catch(
                error => console.log(error)
            );
        },
        get_site_ru_data: function(){
            axios
            .get('../../../api/siteData/get_site_ru_data')
            .then(response => {
                this.site_ru_info = response.data
            })
            .catch(
                error => console.log(error)
            );
        },
        get_site_us_data: function(){
            axios
            .get('../../../api/siteData/get_site_us_data')
            .then(response => {
                this.site_us_info = response.data
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