<template>
  <div class="col-md-12">

    <div class="row">
        <div class="form-group">
            <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
        </div>
    </div>

    <div class="row">
        <div class="form-group">  
            <button form='route_add_form' type="submit" class="btn btn-primary" @click="go_back_action = true" >Save and go back</button>
            <p>Save and go to route tab page</p>
        </div>
    </div>

    <div class="row">
        <div class="form-group">  
            <button form='route_add_form' type="submit" class="btn btn-primary" @click="go_back_action = false" >Save and add more reoute</button>
            <p>Save and add more route</p>
        </div>
    </div>

    <div class="wrapper container-fluid container">
      <form id="route_add_form" @submit.prevent="add_mtp()">
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
            <ckeditor v-model="data.text"></ckeditor>
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
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      // sellected: '',
      // route_type: '',

      regions: [],
      sectors: [],

      errors: [],

      // sector_id: "",
      // title: "",
      // text: "",
      // height: "",
      // height: "",

      data: {
        article_id: "",
        sector_id: "",
        name: "",
        text: "",
        height: "",
        first_ascent: "",
        author: '',
      },

      // published: "",

      go_back_action: false,
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

    add_mtp: function () {
      axios
      .post('../api/mtp/mtp_add', {
          data: this.data,
      })
      .then(response => {
        if(!this.go_back_action){
          alert('Saving completed')
          this.clear_form()
        }
        else{
          this.go_back(true)
        }
      })
      .catch(error =>{
          if (error.response.status == 422) {
            this.errors = error.response.data.errors
          }
      })
    },

    clear_form(){
      this.data = {
        article_id: this.data.article_id,
        sector_id: this.data.sector_id,
        name: "",
        text: "",
        height: "",
        first_ascent: "",
        author: '',
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
