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
                        :global_data_prop=site_global_info
                    />
                        <!-- :global_data_prop='site_global_info' -->
                        <!-- :global_data_prop="JSON.stringify(site_global_info)" -->
                        <!-- :global_data_prop="site_global_info" -->
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import globalInfoForm from './forms/SiteGlobalDataEditComponent.vue'

export default {
    components: {
        globalInfoForm,
    },
    props: [
        // 'back_url',
    ],
    data(){
        return {
            tab_num: 1,
            site_global_info: []
        }
    },
    mounted() {
        this.get_site_global_data()
        
        document.querySelector('body').style.marginLeft = '0';
        document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
    },
    methods: {
        // onFileChange(event, item_id){
        //     let image = event.target.files[0]
        //     let id = item_id - 1 
        //     this.bisnes_images[id]['image'] = image
        // },
        
        update(){
            axios
            .post('/get_site_data/edit_site_global_data',{
                site_global_info: this.site_global_info
            })
            .then(response => {
                // this.site_ru_info = response.data
                this.go_back()
            })
            .catch(
                error => console.log(error)
            );
        },
       
        get_site_global_data: function(){
            axios
            .get('/get_site_data/get_site_global_data')
            .then(response => {
                this.site_global_info = response.data
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