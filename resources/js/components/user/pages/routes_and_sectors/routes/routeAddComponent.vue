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

    <div class="row" v-if="!is_loading">
        <div class="form-group">
            <button form='route_add_form' type="submit" class="btn btn-primary" @click="is_back_action_query = true" >Save and go back</button>
            <!-- <p>Save and go to route tab page</p> -->
        </div>
    </div>

    <div class="row" v-if="!is_loading">
        <div class="form-group">
            <button form='route_add_form' type="submit" class="btn btn-primary" @click="is_back_action_query = false" >Save and add more reoute</button>
            <!-- <p>Save and add more route</p> -->
        </div>
    </div>

    <div class="row" v-show="!is_loading" v-if="problem_status.length != 0">
        <validator_alerts_component
            :errors_prop="problem_status"
        />
    </div>

    <div class="wrapper container-fluid container" v-if="!is_loading">
      <form id="route_add_form" @submit.prevent="save_new_route()">
        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> Region </label>
          <div class="col-md-5">
            <select class="form-control" v-model="article_id" @click="filter_sectors()" required>
              <option value="" disabled>Select outdoor article</option>
              <option v-for="region in regions" :key="region.id" v-bind:value="region.id">{{ region.url_title  }}</option>
            </select>
          </div>
          <div class="col-md-5">
            <select class="form-control" v-if="article_id != ''" v-model="data.sector_id" @change="get_sector_images(data.sector_id)" required>
              <option value="" disabled>Select sector</option>
              <option v-for="sector in sectors" :key="sector.id" v-bind:value="sector.id">{{ sector.name }}</option>
            </select>
          </div>
        </div>

        <div class="tabs form-group clearfix">
            <div class="row">
              <div class="col" v-for="(image, index) in sector_images" :key="'col-' + image.id + '-' + index" >
                  <input
                      type="radio"
                      :id="'input-' + image.id"
                      :value="image.id"
                      v-model="images_tab_num"
                      @change="updateSectorImageId"
                  />
                  <label :for="'input-' + image.id">
                      Image ID -> {{ image.id }}
                  </label>
              </div>
            </div>

            <div class="row">
                <div class="col-md-12" v-for="image in sector_images" :key="image.id" 
                    v-if="images_tab_num == image.id" >
                <Editor
                  ref="editorComponent"
                  :image_prop="'/public/images/sector_img/' + image.image"
                  :related_jsons="related_jsons"
                  @canvas_data="handleCanvasData"
                />
                </div>
            </div>    
        </div>

        <div class="form-group clearfix row" v-if="errors.sector_id">
            <div class="col-md-12">
              <div class="alert alert-danger" role="alert">
                {{ errors.sector_id[0] }}
              </div>
            </div>
        </div>

        <grades_form 
            :category_prop="this.$route.params.category"
            @update:data="data = Object.assign({}, data, $event)"
        />

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

          <div class="col-md-2" v-if="data.category != '' && data.category == 'sport climbing'">
            <label for="name" class='col-md-12 control-label'> Bolts: </label>
          </div>
          <div class="col-md-3" v-if="data.category != '' && data.category == 'sport climbing'">
              <input type="number" name="title" v-model="data.bolts" class="form-control" placeholder="Bolts"> 
          </div>

          <div class="col-md-2">
            <label for="name" class='col-md-12 control-label'> Metrs: </label>
          </div>
          <div class="col-md-3">
            <input type="number" name="title" class="form-control" v-model="data.height" placeholder="Height"> 
          </div>
        </div>

        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> Ancher and bolt type: </label>

          <div class="col-md-2" v-if="data.category != '' && data.category != 'bouldering'">
            <label for="name" class='col-md-12 control-label'> Anchor type: </label>
          </div>

          <div class="col-md-3" v-if="data.category != '' && data.category != 'bouldering'">
            <select class="form-control" v-model="data.anchor_type">
              <option value="">Anchor type</option>
              <option :value=null>?</option>
              <option value="Chain">Chain</option>
              <option value="Chain with carabiner">Chain with carabiner</option>
              <option value="Chain with ring">Chain with ring</option>
              <option value="2 independent chains">2 independent chains</option>
              <option value="2 bolts with carabiner">2 bolts with carabiner</option>
              <option value="2 bolts only">2 bolts only</option>
            </select>
          </div>

          <div class="col-md-2" v-if="data.category != '' && data.category != 'bouldering'">
            <label for="name" class='col-md-12 control-label'> Bolts type: </label>
          </div>

          <div class="col-md-3" v-if="data.category != '' && data.category != 'bouldering'">
            <select class="form-control" v-model="data.bolts_type">
              <option value="">Bolts type</option>
              <option :value=null>?</option>
              <option value="glued">Glued-in bolts</option>
              <option value="hangerr">Hangerr bolts</option>
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
  import validator_alerts_component from '../../../items/validator_alerts_component.vue'
  import grades_form from './assets/gradingFormComponent.vue'

  export default {
      mixins: [
          editor_config,
      ],
      components: {
          Editor,
          validator_alerts_component,
          grades_form
      },
    data() {
      return {
        description_editor: editor_config.get_small_editor_config(),

        images_tab_num: 0,
        errors: [],

        regions: [],
        all_sectors: [],
        sectors: [],
        sector_images: [],

        status: "",
        problem_status: "",

        article_id: "",
        
        data: {
          sector_id: "",
          sector_image_id: "",

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
          bolts_type: "",

          category: "",
          route_json: null, // Add canvas JSON data
          related_jsons: [], // Add related routes JSONs
        },

        is_loading: false,

        is_back_action_query: false,
      }
    },

    mounted() {
      this.get_region_data()
        
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
      get_region_data: function(){
        axios
        .get("/get_article/get_category_articles/" + this.$route.params.category)
        .then(response => {
          this.regions = response.data
          this.get_sectors_data()
        })
        .catch(
          error => console.log(error)
        );
      },
      
      get_sectors_data: function(){
        axios
        .get("/get_sector/get_all_sectors/")
        .then(response => {
          this.all_sectors = response.data
        })
        .catch(
          error => console.log(error)
        );
      },

      get_sector_images(id){
        axios
        .get("/get_sector/get_sector_images/" + id)
        .then(response => {
          this.sector_images = response.data
          if (this.sector_images.length > 0) {
            this.data.sector_image_id = this.sector_images[0].id;
            this.images_tab_num = this.sector_images[0].id;
            // Fetch related routes JSONs for the selected sector image
            this.get_related_routes_jsons(this.data.sector_image_id);
          }
        })
        .catch(
          error => console.log(error)
        );
      },

      filter_sectors(){
        let vm = this;
        this.sectors = this.all_sectors.filter(function (item){
            return item.article_id == vm.article_id
        })
      },

      save_new_route: function () {
        this.is_loading = true

        // Ensure canvas data is captured before saving
        this.$nextTick(() => {
          try {
            if (this.$refs.editorComponent && typeof this.$refs.editorComponent.getAndEmitCanvasData === 'function') {
              this.$refs.editorComponent.getAndEmitCanvasData();
            }
          } catch (error) {
            console.log('Canvas data emission failed:', error);
          }

          // Include canvas JSON data in the route data
          const routeData = {
              ...this.data,
              route_json: this.data.route_json,
              sector_image_id: this.data.sector_image_id
          };

          axios
          .post('/set_route/add_route/', {
              data: routeData,
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
              console.log('Save route error:', error);
          })
          .finally(() => this.is_loading = false);
        });
      },

      clear_form(){
        this.data = {
          sector_id: "",
          sector_image_id: "",

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
          bolts_type: "",
          route_json: null,
        }
      },


      go_back(back_action = false) {
        this.is_back_action_query = this.$going.back(this, back_action)
      },

      // Handle canvas data from Editor component
      handleCanvasData(canvasData) {
        console.log('Canvas data received:', canvasData);
        this.data.route_json = canvasData;
        console.log('Canvas data stored in route_json:', this.data.route_json);
      },

      updateSectorImageId() {
        this.data.sector_image_id = this.images_tab_num;
        // Fetch related routes JSONs when sector image changes
        this.get_related_routes_jsons(this.data.sector_image_id);
      },

      get_related_routes_jsons(sectorImageId) {
        axios
        .get("/get_route/get_related_routes_jsons", {
          params: { sector_image_id: sectorImageId }
        })
        .then(response => {
          this.related_jsons = response.data;
          console.log('Related routes JSONs loaded:', this.related_jsons);
        })
        .catch(
          error => console.log('Error fetching related routes JSONs:', error)
        );
      },
    }
  }
</script>
