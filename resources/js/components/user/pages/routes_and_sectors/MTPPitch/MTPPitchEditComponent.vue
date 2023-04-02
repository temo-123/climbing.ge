<template>
  <div class="col-md-12">
  
      <div class="row">
          <div class="form-group">
              <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
          </div>
      </div>
  
      <div class="form-group clearfix row" v-if="problem_status != ''">
          <div class="col-md-12">
              <div class="alert alert-danger" role="alert">
                  {{ problem_status }}
              </div>
          </div>
      </div>
  
      <div class="row" v-if="!is_geting_data_isset">
          <div class="form-group">  
              <button form='mtp_add_form' type="submit" class="btn btn-primary" @click="go_back_action = true" >Save and go back</button>
              <p>Save and go to pitch tab page</p>
          </div>
      </div>
  
      <div class="wrapper container-fluid container"  v-if="!is_geting_data_isset">
          <form id="mtp_add_form" @submit.prevent="save_editing_pitch()">
          <div class="form-group clearfix row">
              <label for="name" class='col-md-2 control-label'> Region </label>
              <div class="col-md-5">
                  <select class="form-control" v-model="data.article_id" @click="filter_sectors(), data.sector_id = ''" required>
                      <option value="" disabled>Select outdoor article</option>
                      <option v-for="region in regions" :key="region.id" v-bind:value="region.id">{{ region.url_title  }}</option>
                  </select>
              </div>
              <div class="col-md-5">
                  <select class="form-control" v-if="data.article_id != ''" v-model="data.sector_id" @click="filter_mtp(), data.mtp_id = ''" required>
                      <option value="" disabled>Select sector</option>
                      <option v-for="sector in sectors" :key="sector.id" v-bind:value="sector.id">{{ sector.name }}</option>
                  </select>
              </div>
          </div>
          <div class="form-group clearfix row">
              <label for="name" class='col-md-2 control-label'> Multipitch </label>
              <div class="col-md-5" v-if="data.sector_id != ''">
                  <select class="form-control" v-model="data.mtp_id" required>
                      <option value="" disabled>Select multipich</option>
                      <option v-for="mtp in mtps" :key="mtp.id" v-bind:value="mtp.id">{{ mtp.name }}</option>
                  </select>
              </div>
          </div>
          
          <!-- <div class="form-group clearfix row" v-if="sector_id != ''">
              <div class="col-md-12">
              <div class="row">
                  <Editor />
              </div>
              </div>
          </div> -->
  
          <div class="form-group clearfix row" v-if="errors.sector_id">
              <div class="col-md-12">
                  <div class="alert alert-danger" role="alert">
                  {{ errors.sector_id[0] }}
                  </div>
              </div>
          </div>
  
          <div class="form-group clearfix row">
              <label for="name" class='col-md-2 control-label'> Grade </label>
              <div class="col-md-5">
                  <select class="form-control"  v-model="data.category" required>
                      <option value="" disabled>Please select mtp type</option>
                      <option value="sport climbing">Stort climbing</option>
                      <option value="tred">Tred Climbing</option>
                      <!-- <option value="aid">Aid climbing</option> -->
                  </select>
              </div>
  
              <div class="col-md-5" v-if="data.category != '' && data.category == 'sport climbing' || data.category == 'top'|| data.category == 'tred'">
                  <div class="row">
                      <div class="col-md-6">
                          <select class="form-control" v-if="data.category != '' && data.category == 'sport climbing' || data.category == 'top'|| data.category == 'tred'" v-model="data.grade" required>
                              <option value=""> No grade </option>
                              <option value="Project">Project</option>
                              <option v-for="sport in sport_route_grade" :key="sport" v-bind:value="sport" :selected="true" >{{ sport }}</option>
                          </select>
                      </div>
                      <div class="col-md-6">
                          <select class="form-control" v-if="data.category != '' && data.category == 'sport climbing' || data.category == 'top'|| data.category == 'tred'" v-model="data.or_grade">
                              <option value=""> No grade </option>
                              <option value="Project">Project</option>
                              <option v-for="sport in sport_route_grade" :key="sport" v-bind:value="sport" :selected="true" >{{ sport }}</option>
                          </select>
                      </div>
                  </div>
              </div>
          </div>
  
          <div class="form-group clearfix row" v-if="errors.grade">
              <div class="col-md-12">
                  <div class="alert alert-danger" role="alert">
                  {{ errors.grade[0] }}
                  </div>
              </div>
          </div>
  
          <div class="form-group clearfix row">
              <label for="name" class='col-md-2 control-label'> Route name </label>
              <div class="col-md-10">
              <input type="text" name="name" v-model="data.name" class="form-control" placeholder="Route name.." required> 
                  <div class="alert alert-danger" role="alert" v-if="errors.name">
                  {{ errors.name[0] }}
                  </div>
              </div>
          </div>
  
          <div class="form-group clearfix row">
              <label for="name" class='col-md-2 control-label'> Description (Text) </label>
              <div class="col-md-10">
                  <ckeditor v-model="data.text" :config="description_editor"></ckeditor>
              </div>
          </div>
  
          <div class="form-group clearfix row">
              <label for="name" class='col-md-2 control-label'> Bolts & height </label>
  
              <div class="col-md-1" v-if="data.category != '' && data.category == 'sport climbing'">
                  <label for="name" class='col-md-1 control-label'> Bolts: </label>
              </div>
              <div class="col-md-2" v-if="data.category != '' && data.category == 'sport climbing'">
                  <input type="number" name="title" v-model="data.bolts" class="form-control" placeholder="Bolts"> 
              </div>
  
              <div class="col-md-1">
                  <label for="name" class='col-md-1 control-label'> Metrs: </label>
              </div>
              <div class="col-md-2">
                  <input type="number" name="title" class="form-control" v-model="data.height" placeholder="Height"> 
              </div>
  
          </div>
  
          <div class="form-group clearfix row">
              <label for="name" class='col-md-2 control-label'> Author & Creation date </label>
              <div class="col-md-5">
                  <input type="text" name="title" class="form-control" v-model="data.author" placeholder="Bolter"> 
              </div>
              <div class="col-md-5">
                  <input type="date" name="title" class="form-control" v-model="data.creation_data" placeholder="Bolting Data"> 
              </div>
          </div>
  
          <div class="form-group clearfix row">
              <label for="name" class='col-md-2 control-label'>Firs Ascent </label>
              <div class="col-md-5">
                  <input type="text" name="title" class="form-control" v-model="data.first_ascent" placeholder="First ascent"> 
              </div>
          </div>
  
          </form>
      </div>

      <div class="row justify-content-center" v-if="is_geting_data_isset">
          <div class="col-md-4">
              <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
          </div>
      </div>
  </div>
</template>
  
<script>
    import Editor from '../../../items/canvas/EditorComponent.vue'
    import { editor_config } from '../../../../../mixins/editor/editor_config_mixin.js'

    export default {
        mixins: [
            editor_config
        ],
        components: {
            Editor,
        },
        data() {
            return {
                description_editor: editor_config.get_small_editor_config(),
                errors: [],
        
                regions: [],

                all_sectors: [],
                sectors: [],
                
                all_mtps: [],
                mtps: [],
        
                status: "",
                problem_status: "",
        
                data: {
                    article_id: "",
                    sector_id: "",
                    mtp_id: "",
            
                    grade: "",
                    or_grade: "",
            
                    name: "",
                    text: "",
                    
                    height: "",
                    bolts: "",
            
                    author: "",
                    creation_data: "",
                    first_ascent: "",
            
                    anchor_type: "",
                    category: "",
                },
        
                is_geting_data_isset: true,

                sport_route_grade: [
                    "4",
                    "5a", "5b", "5c", "5c+",
                    "6a", "6a+", "6b", "6b+", "6c", "6c+",
                    "7a", "7a+", "7b", "7b+", "7c", "7c+",
                    "8a", "8a+", "8b", "8b+", "8c", "8c+",
                    "9a", "9a+", "9b", "9b+", "9c", "9c+",
                ],
            }
        },
    
        mounted() {
            this.get_region_data()
            this.get_sectors_data()
        },
    
        methods: {
            get_region_data: function(){
                axios
                .post("../../api/article/",{category: 'outdoor'})
                .then(response => {
                    this.regions = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            
            get_sectors_data: function(){
                axios
                .get("../../api/sector/")
                .then(response => {
                    this.all_sectors = response.data
                    this.get_mtp_data()
                })
                .catch(
                    error => console.log(error)
                );
            },

            get_mtp_data: function(){
                axios
                .get("../../api/mtp/")
                .then(response => {
                    this.all_mtps = response.data
                    this.get_editing_mtp_data()
                })
                .catch(
                    error => console.log(error)
                );
            },

            get_editing_mtp_data: function(){
                axios
                .get("../../api/mtp/mtp_pitch/get_editin_pitch/"+this.$route.params.id)
                .then(response => {
                    this.data = response.data
                    let mtp = this.all_mtps.find(item => item.id === this.data.mtp_id);
                    
                    let sector = this.all_sectors.find(item => item.id === mtp.sector_id);
                    this.data.sector_id = sector.id;

                    let action_article = this.regions.find(item => item.id === sector.article_id);
                    this.data.article_id = action_article.id;

                    this.filter_sectors()
                    this.filter_mtp()
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.is_geting_data_isset = false);
            },
        
            filter_sectors(){
                let vm = this;
                // this.data.sector_id = ''
                this.sectors = this.all_sectors.filter(function (item){
                    return item.article_id == vm.data.article_id
                })
            },
        
            filter_mtp(){
                let vm = this;
                // this.data.mtp_id = ''
                this.mtps = this.all_mtps.filter(function (item){
                    return item.sector_id == vm.data.sector_id
                })
            },
        
            save_editing_pitch: function (go_back_action) {
                axios
                .post('../../api/mtp/mtp_pitch/mtp_pitch_edit/'+this.$route.params.id, {
                    data: this.data,
                })
                .then(response => {
                        this.go_back(true)
                })
                .catch(error =>{
                    this.status = "error"
                })
            },
        
            clear_form(){
                this.data = {
                article_id: this.data.article_id,
                sector_id: this.data.sector_id,
                mtp_id: this.data.mtp_id,
                category: this.data.category,
        
                grade: "",
                or_grade: "",
        
                name: "",
                text: "",
                
                height: "",
                bolts: "",
        
                author: "",
                creation_data: "",
                first_ascent: "",
                }
            },
    
            go_back(back_action = false) {
                if(back_action == false){
                    if(confirm('Are you sure, you want go back?')){
                        this.$router.push({ name: 'routeAndSectorList' })
                    }
                }
                else{
                    this.$router.push({ name: 'routeAndSectorList' })
                }
            },
        }
    }
</script>
  
  // sport climbing 1
  // top 2
  // tred 3
  // bouldering 4