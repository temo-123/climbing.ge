<template>
  <div class="col-md-12">

    <div class="row justify-content-center" v-if="is_loading">
        <div class="col-md-4">
            <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
        </div>
    </div>

    <div class="row"  v-if="!is_loading">
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

    <div class="row" v-if="!is_loading">
        <div class="form-group">  
            <button form='route_add_form' type="submit" class="btn btn-primary" @click="is_back_action_query = true" >Save and go back</button>
            <p>Save and go to route tab page</p>
        </div>
    </div>

    <div class="wrapper container-fluid container" v-if="!is_loading">
      <form id="route_add_form" @submit.prevent="save_new_route()">
        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> Region </label>
          <div class="col-md-5">
            <select class="form-control" v-model="data.article_id" @click="filter_sectors()" required>
              <option value="" disabled>Select outdoor article</option>
              <option v-for="region in regions" :key="region.id" v-bind:value="region.id">{{ region.url_title  }}</option>
            </select>
          </div>
          <div class="col-md-5">
            <select class="form-control" v-if="data.article_id != ''" v-model="data.ice_sector_id" required>
              <option value="" disabled>Select sector</option>
              <option v-for="sector in sectors" :key="sector.id" v-bind:value="sector.id">{{ sector.name }}</option>
            </select>
          </div>
        </div>
        
        <!-- <div class="form-group clearfix row" v-if="ice_sector_id != ''">
          <div class="col-md-12">
            <div class="row">
                <Editor />
            </div>
          </div>
        </div> -->

        <div class="form-group clearfix row" v-if="errors.ice_sector_id">
            <div class="col-md-12">
              <div class="alert alert-danger" role="alert">
                {{ errors.ice_sector_id[0] }}
              </div>
            </div>
        </div>

        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> Grade </label>
          <div class="col-md-5">
            <select class="form-control"  v-model="data.category" required>
              <option value="" disabled>Please select route type</option>
              <option value="ice">Ice climbing</option>
              <option value="dry">Dry tooling</option>
            </select>
          </div>

          <div class="col-md-5" v-if="data.category != '' && data.category == 'ice'">
            <div class="row">
              <div class="col-md-6">
                <select class="form-control" v-if="data.category != '' && data.category == 'ice'" v-model="data.grade" required>
                  <option value=""> No grade </option>
                  <option value="Project">Project</option>
                  <option v-for="sport in ice_climbing" :key="sport" v-bind:value="sport" :selected="true" >{{ sport }}</option>
                </select>
              </div>
            </div>
          </div>

          <div class="col-md-5" v-if="data.category != '' && data.category == 'dry'">
            <div class="row">
              <div class="col-md-6">
                <select class="form-control" v-if="data.category != '' && data.category == 'dry'" v-model="data.grade" required>
                  <option value=""> No grade </option>
                  <option value="Project">Project</option>
                  <option v-for="boulder in dry_tooling" :key="boulder" v-bind:value="boulder" :selected="true" >{{ boulder }}</option>
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

          <div class="col-md-1" v-if="data.category == 'dry'">
            <label for="name" class='col-md-1 control-label'> Bolts: </label>
          </div>
          <div class="col-md-2" v-if="data.category == 'dry'">
              <input type="number" name="title" v-model="data.bolts" class="form-control" placeholder="Bolts"> 
          </div>

          <div class="col-md-1">
            <label for="name" class='col-md-1 control-label'> Metrs: </label>
          </div>
          <div class="col-md-2">
            <input type="number" name="title" class="form-control" v-model="data.height" placeholder="Height"> 
          </div>

          <div class="col-md-1" >
            <label for="name" class='col-md-1 control-label'> Anchor: </label>
          </div>

          <div class="col-md-3" >
            <select class="form-control" v-model="data.anchor_type">
              <option value="" disabled>Anchor type</option>
              <option value="Chain">Chain</option>
              <option value="Chain with carabiner">Chain with carabiner</option>
              <option value="Chain with ring">Chain with ring</option>
              <option value="2 independent chains">2 independent chains</option>
              <option value="2 bolts with carabiner">2 bolts with carabiner</option>
              <option value="2 bolts only">2 bolts only</option>
              <option value="Anchor on tree">Anchor on tree</option>
              <option value="Wthitout Anchor">Wthitout Anchor</option>
            </select>
          </div>
        </div>

        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> Author & Creation date </label>
          <div class="col-md-5">
            <input type="text" name="auther" class="form-control" v-model="data.author" placeholder="Bolter"> 
          </div>
          <div class="col-md-5">
            <input type="date" name="creating_data" class="form-control" v-model="data.creation_data" placeholder="Bolting Data"> 
          </div>
        </div>

        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'>Firs Ascent </label>
          <div class="col-md-5">
            <input type="text" name="first_ascent" class="form-control" v-model="data.first_ascent" placeholder="First ascent"> 
          </div>
        </div>

      </form>
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

        status: "",
        problem_status: "",


        data: {
          ice_sector_id: "",
          article_id: "",

          grade: "",
          // or_grade: "",

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

        is_loading: false,

        is_back_action_query: false,

        ice_climbing: [
          "WI1", "WI2", "WI3", "WI4", "WI5", "WI6",
        ],
        dry_tooling: [
          "M1","M2","M3","M4","M5","M6","M7","M8","M9","M10","M11","M12",
        ],
      }
    },

    mounted() {
      this.get_region_data()
      this.get_sectors_data()
      this.get_route_editing_data()
        
      document.querySelector('body').style.marginLeft = '0';
      document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
    },

    beforeRouteLeave (to, from, next) {
        if(this.is_back_action_query == true){
            if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
                this.is_back_action_query = false;
                next()
            } else {
                next(false)
            }
        }else {
            next()
        }
    },
    methods: {
      get_route_editing_data(){
        this.is_loading = true

        axios
        .get("/ice_routes/get_route_editing_data/"+this.$route.params.id,)
        .then(response => {
          this.data = response.data

          let sector = this.all_sectors.find(item => item.id === this.data.ice_sector_id);
          let action_article = this.regions.find(item => item.id === sector.article_id);
          this.data.article_id = action_article.id;

          this.filter_sectors()
        })
        .catch(
          error => console.log(error)
        )
        .finally(() => this.is_loading = false);
      },

      filter_sectors(){
        let vm = this;
        this.sectors = this.all_sectors.filter(function (item){
            return item.article_id == vm.data.article_id
        })
        // this.is_loading = false
      },

      get_region_data: function(){
        axios
        .post("/article/",{category: 'ice'})
        .then(response => {
          this.regions = response.data
        })
        .catch(
          error => console.log(error)
        );
      },
      
      get_sectors_data: function(){
        axios
        .get("/ice_sectors/get_all_sectors")
        .then(response => {
          this.all_sectors = response.data
        })
        .catch(
          error => console.log(error)
        );
      },

      filter_sectors(){
        let vm = this;
        this.sectors = this.all_sectors.filter(function (item){
            return item.article_id == vm.data.article_id
        })
      },

      save_new_route: function () {
        this.is_loading = true

        axios
        .post("/ice_routes/edit_route/"+this.$route.params.id, {
            data: this.data,
        })
        .then(response => {
          if(!this.is_back_action_query){
            alert('Saving completed')
            this.clear_form()
          }
          else{
            this.go_back(true)
          }
        })
        .catch(error =>{
            this.status = "error"
        })
        .finally(() => this.is_loading = false);
      },

      clear_form(){
        this.data = {
          article_id: this.data.article_id,
          ice_sector_id: this.data.ice_sector_id,
          category: this.data.category,

          grade: "",

          name: "",
          text: "",
          
          height: "",
          bolts: "",

          author: "",
          creation_data: "",
          first_ascent: "",

          anchor_type: "",
        }
      },

        go_back(back_action = false) {
          this.is_back_action_query = this.$going.back(this, back_action)
        },
    }
  }
</script>