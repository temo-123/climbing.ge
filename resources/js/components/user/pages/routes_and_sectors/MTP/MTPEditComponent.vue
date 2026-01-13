<template>
  <div class="col-md-12">

    <div class="row justify-content-center" v-if="is_loading">
        <div class="col-md-4">
            <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
        </div>
    </div>

    <div class="row" v-if="!is_loading">
        <div class="form-group">
            <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
        </div>
    </div>

    <div class="row" v-if="!is_loading">
        <div class="form-group">  
            <button form='route_edit_form' type="submit" class="btn btn-primary">Save and go back</button>
            <p>Save and go to route tab page</p>
        </div>
    </div>

    <div class="wrapper container-fluid container" v-if="!is_loading">
      <form id="route_edit_form" @submit.prevent="edit_mtp()">
        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> Region </label>
          <div class="col-md-5">
            <select class="form-control" v-model="data.article_id" @click="filter_sectors()" required>
              <option value="" disabled>Select outdoor article</option>
              <option v-for="region in regions" :key="region.id" v-bind:value="region.id">{{ region.url_title  }}</option>
            </select>
          </div>
          <div class="col-md-5">
            <select class="form-control" v-if="data.article_id != ''" v-model="data.sector_id" required>
              <option value="" disabled>Select sector</option>
              <option v-for="sector in sectors" :key="sector.id" v-bind:value="sector.id">{{ sector.name }}</option>
            </select>
          </div>
        </div>
        
        <div class="form-group clearfix row" v-if="errors.sector_id">
            <div class="col-md-12">
              <div class="alert alert-danger" role="alert">
                {{ errors.sector_id[0] }}
              </div>
            </div>
        </div>

        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> Multy pitch name </label>
          <div class="col-md-10">
            <input type="text" name="name" v-model="data.name" class="form-control" placeholder="Name" required> 
              <div class="alert alert-danger" role="alert" v-if="errors.name">
                {{ errors.name[0] }}
              </div>
          </div>
        </div>

        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> text </label>
          <div class="col-md-10">
            <!-- <textarea type="text"  name="text" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            <ckeditor v-model="data.text" :config="description_editor"></ckeditor>
          </div>
        </div>

        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> Height </label>
          <div class="col-md-9">
            <input type="number" name="title" class="form-control" v-model="data.height" placeholder="Height"> 
          </div>
          <label for="name" class='col-md-1 control-label'> M </label>
        </div>

        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'>First ascent / Auther</label>
          <div class="col-md-5">
            <input type="text" name="first_ascent" v-model="data.first_ascent" class="form-control" placeholder="First ascent"> 
          </div>
          <div class="col-md-5">
            <input type="text" name="author" v-model="data.author" class="form-control" placeholder="Auther"> 
          </div>
        </div>

      </form>

      <pitch_list :mtp_id_prop="data.id" />
    </div>

    <div class="row justify-content-center" v-if="is_loading">
        <div class="col-md-4">
            <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
        </div>
    </div>
  </div>
</template>

<script>
  import { editor_config } from '../../../../../mixins/editor/editor_config_mixin.js'
  import pitch_list from './pitchs/PitchListComponent'

  export default {
    mixins: [
        editor_config,
    ],
    components: {
      pitch_list
    },
    data() {
      return {
        description_editor: editor_config.get_small_editor_config(),

        regions: [],
        sectors: [],

        errors: [],

        data: {
          article_id: "",
          sector_id: "",
          name: "",
          text: "",
          height: "",
          first_ascent: "",
          author: '',
        },

        is_loading: false,
        is_back_action_query: true,

        go_back_action: false,
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
        .get("/get_article/get_category_articles/outdoor")
        .then(response => {
          this.regions = response.data
          this.get_sectors_data()
        })
        .catch(
          error => console.log(error)
        )
        .finally(() => this.is_loading = false);
      },

      get_sectors_data: function(){
        axios
        .get("/get_sector/get_all_sectors/")
        .then(response => {
          this.all_sectors = response.data
          this.get_mtp_editing_data()
        })
        .catch(
          error => console.log(error)
        )
        .finally(() => this.is_loading = false);
      },

      get_mtp_editing_data: function(){
        this.is_loading = true
        axios
        .get("/set_mtp/get_editing_mtp/"+this.$route.params.id)
        .then(response => {
          this.data = response.data
          let sector = this.all_sectors.find(item => item.id === this.data.sector_id);
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
      },

      edit_mtp: function () {
        axios
        .post('/set_mtp/mtp_edit/'+this.$route.params.id, {
            data: this.data,
        })
        .then(response => {
          this.go_back(true)
        })
        .catch(error =>{
            if (error.response.status == 422) {
              this.errors = error.response.data.errors
            }
        })
        .finally(() => this.is_loading = false);
      },

      go_back(back_action = false) {
        this.is_back_action_query = this.$going.back(this, back_action)
      },

    }
  }
</script>
