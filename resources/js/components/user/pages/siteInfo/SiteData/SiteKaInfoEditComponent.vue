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
                        <input type="radio" id="4" :value="4" v-model="tab_num">
                        
                        <label for="4" >Georgian text</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row" v-show="tab_num == 4">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Site georgian version</h2>
                            <p class="lead">Site georgian version for site localisation.</p>
                        </div>
                    </div>
                    <localeInfoForm :locale_data_prop=site_ka_info />
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
            tab_num: 4,

            site_ka_info: []
        }
    },
    mounted() {
        this.get_site_ka_data()
        
        document.querySelector('body').style.marginLeft = '0';
        
        document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
    },
    methods: {
        update(){
            axios
            .post('../../api/siteData/edit_site_ka_data',{
                site_ka_info: this.site_ka_info
            })
            .then(response => {
                // this.site_us_info = response.data
                this.go_back(true)
            })
            .catch(
                error => console.log(error)
            );
        },

        get_site_ka_data: function(){
            axios
            .get('../../api/siteData/get_site_ka_data')
            .then(response => {
                this.site_ka_info = response.data
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