<template>
  <div class="col-md-12">

    <div class="row justify-content-center" v-if="is_loading">
        <div class="col-md-4">
            <img :src="'/images/site_img/loading.gif'" alt="loading">
        </div>
    </div>

    <div class="row"  v-if="!is_loading">
        <div class="form-group">
            <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
        </div>
    </div>

    <div class="row" v-if="!is_loading">
        <div class="form-group">  
            <button form='route_edit_form' type="submit" class="btn btn-primary" @click="is_back_action_query = true" >Save and go back</button>
            <!-- <p>Save and go to route tab page</p> -->
        </div>
    </div>

    <div class="row" v-show="!is_loading" v-if="problem_status.length != 0">
        <validator_alerts_component
            :errors_prop="problem_status"
        />
    </div>

    <div class="wrapper container-fluid container" v-if="!is_loading">
      <form id="route_edit_form" @submit.prevent="save_route()">
        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> Region </label>
          <div class="col-md-5">
            <select class="form-control" v-model="article_id" @click="filter_sectors()" required>
              <option value="" disabled>Select outdoor article</option>
              <option v-for="region in regions" :key="region.id" v-bind:value="region.id">{{ region.url_title  }}</option>
            </select>
          </div>
          <div class="col-md-5">
            <select class="form-control" v-if="article_id != ''" v-model="data.sector_id" required>
              <option value="" disabled>Select sector</option>
              <option v-for="sector in sectors" :key="sector.id" v-bind:value="sector.id">{{ sector.name }}</option>
            </select>
          </div>
        </div>
        
        <route_editor_component
            v-if="data.sector_id != ''"
            ref="editorComponent"
            :sector_id_prop="data.sector_id"
            :route_json_prop="data.route_json"
            :show_alert_prop="show_alert_modal"
            :sector_image_id_prop="data.sector_image_id"
            :route_id_prop="$route.params.id"
            @update:route_json_prop="data.route_json = $event"
            @update:sector_image_id_prop="data.sector_image_id = $event"
        />

        <div class="form-group clearfix row" v-if="errors.sector_id">
            <div class="col-md-12">
              <div class="alert alert-danger" role="alert">
                {{ errors.sector_id[0] }}
              </div>
            </div>
        </div>

        <grades_form 
            :key="'grades-form-' + data.category"
            :category_type_prop="category_type"
            :category_prop="data.category"
            :grade_prop="data.grade"
            :or_grade_prop="data.or_grade"
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
            <big_editor v-model="data.text" />
          </div>
        </div>

        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> Bolts & height </label>

          <div class="col-md-2" v-if="data.category != '' && ['sport climbing', 'dry tooling', 'mix climbing'].includes(data.category)">
            <label for="name" class='col-md-12 control-label'> Bolts: </label>
          </div>
          <div class="col-md-3" v-if="data.category != '' && ['sport climbing', 'dry tooling', 'mix climbing'].includes(data.category)">
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
  import validator_alerts_component from '../../../items/form/validator_alerts_component.vue'
  import grades_form from './assets/gradingFormComponent.vue'
  import route_editor_component from './assets/CanvasRouteEditorComponent.vue'

  export default {
      mixins: [

      ],
      components: {
          validator_alerts_component,
          grades_form,
          route_editor_component
      },
    data() {
      return {
        description_editor: {},

        // routes_category: [],
        errors: [],

        regions: [],
        all_sectors: [],
        sectors: [],

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
        },

        is_loading: false,

        is_back_action_query: false,
        show_no_json_alert: false,
        show_alert_modal: false,
      }
    },
    
    computed: {
      category_type() {
        // Determine if category is outdoor or ice based on the category value
        const outdoorCategories = ['sport climbing', 'bouldering', 'tred', 'top'];
        const iceCategories = ['ice climbing', 'dry tooling', 'mix climbing'];
        
        if (outdoorCategories.includes(this.data.category)) {
          return 'outdoor';
        } else if (iceCategories.includes(this.data.category)) {
          return 'ice';
        }
        return 'outdoor'; // Default to outdoor
      }
    },

    mounted() {
      // this.get_region_data()
      this.get_route_editing_data()

      document.querySelector('body').style.marginLeft = '0';
      document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
    },
    watch: {
      'data.category': function() {
        this.$forceUpdate();
      }
    },

    beforeRouteLeave (to, from, next) {
        if(this.is_back_action_query == true){
            if (window.confirm('edited information will be deleted!!! Are you sure, you want go back?')) {
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
      get_region_data: function(active_article){
        axios
        .get("/get_article/get_category_articles/" + active_article.category)
        .then(response => {
          this.regions = response.data
          
          let sector = this.all_sectors.find(item => item.id === this.data.sector_id);
          let action_article = this.regions.find(item => item.id === sector.article_id);
          this.article_id = action_article.id;

          this.filter_sectors()
        })
        .catch(
          () => {}
        );
      },

      get_article_global_data(id){
        axios
        .get("/set_article/get_editing_data/" + id)
        .then(response => {
          this.get_region_data(response.data.global_article);
        })
        .catch(
          () => {}
        );
      },
      
      get_sectors_data: function(){
        axios
        .get("/get_sector/get_all_sectors/")
        .then(response => {
          this.all_sectors = response.data

          let sector = this.all_sectors.find(item => item.id === this.data.sector_id);
          
          this.get_article_global_data(sector.article_id)
        })
        .catch(
          () => {}
        );
      },

      get_route_editing_data: function(){
        this.is_loading = true

        axios
        .get("/set_route/get_route_editing_data/"+this.$route.params.id,)
        .then(response => {
          // Use Object.assign to merge response data while preserving reactivity
          Object.assign(this.data, response.data);

          // Load route JSON data if available
          if (response.data.json) {
            this.data.route_json = response.data.json;
            this.show_no_json_alert = false;
            this.show_alert_modal = false;
          } else {
            this.data.route_json = null;
            this.show_no_json_alert = false;
            this.show_alert_modal = true;
          }

          // Set sector_image_id if available from response
          if (response.data.sector_image_id) {
            this.data.sector_image_id = response.data.sector_image_id;
          }

          this.get_sectors_data()
        })
        .catch(
          () => {}
        )
        .finally(() => this.is_loading = false);
      },

      filter_sectors(){
        let vm = this;
        this.sectors = this.all_sectors.filter(function (item){
            return item.article_id == vm.article_id
        })
      },

      save_route: function () {
        this.is_loading = true

        // Ensure canvas data is captured before saving
        this.$nextTick(() => {
          try {
            if (this.$refs.editorComponent && typeof this.$refs.editorComponent.getAndEmitCanvasData === 'function') {
              this.$refs.editorComponent.getAndEmitCanvasData();
            }
          } catch (e) {}

          // Include canvas JSON data in the route data (explicitly list fields to avoid including unrelated properties)
          const routeData = {
            ...this.data,
            route_json: this.data.route_json,
            sector_image_id: this.data.sector_image_id
          };

          axios
          .post('/set_route/edit_route/' + this.$route.params.id, {
              data: routeData,
          })
          .then(response => {
            if(!this.is_back_action_query){
              alert('Saving completed')
              // Refresh data instead of clearing
              this.get_route_editing_data()
            }
            else{
              this.go_back(true)
            }
          })
          .catch(error =>{
              this.status = "error"
              alert('Error saving route: ' + (error.response && error.response.data && error.response.data.error ? error.response.data.error : 'Unknown error'));
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

          category: "",
          route_json: null,
        }
      },

      go_back(back_action = false) {
        this.is_back_action_query = this.$going.back(this, back_action)
      },

    }
  }
</script>
