<template>
  <div class="col-md-12">
    <div class="row">
        <div class="form-group">  
            <button type="submit" class="btn btn-primary" v-on:click="save_all()" >Save</button>
        </div>
    </div>
    <div class="wrapper container-fluid container">
      <form action="">
        
        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> Region </label>
          <div class="col-md-4">
            <select class="form-control"  v-model="sellected">
              <option disabled>Please select sector</option>
              <option v-for="region in regions" :key="region.id" v-bind:value="region.id">{{ region.url_title  }}</option>
            </select>
          </div>
          <div class="col-md-4">
            <div class="col-md-12">
              <select class="form-control" v-if="sellected != ''" v-model="sector_id">
                <option disabled>Please select sector</option>
                <option v-for="sector in sectors" :key="sector.id" v-if="sellected == sector.article_id" v-bind:value="sector.id">{{ sector.name }}</option>
              </select>
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

        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> Route name </label>
          <div class="col-md-8">
            <input type="text" name="name" v-model="name" class="form-control" placeholder="Route name.."> 
              <div class="alert alert-danger" role="alert" v-if="errors.name">
                {{ errors.name[0] }}
              </div>
          </div>
        </div>

        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> text </label>
          <div class="col-md-8">
            <!-- <textarea type="text"  name="text" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            <ckeditor v-model="text" :config="editorConfig"></ckeditor>
          </div>
        </div>

        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> Height </label>
          <div class="col-md-6">
            <input type="text" name="title" class="form-control" v-model="height" placeholder="Height"> 
          </div>
          <label for="name" class='col-md-2 control-label'> M </label>
        </div>

      </form>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      sellected: '',
      route_type: '',

      regions: [],
      sectors: [],

      errors: [],

      sector_id: "",
      title: "",
      text: "",
      last_carabin: "",
      height: "",
      bolter: "",
      first_ascent: "",

      published: "",
    }
  },

  mounted() {
    this.get_region_data()
    this.get_sectors_data()
  },

  methods: {
    get_region_data: function(){
      axios
      .get("../routes_and_sectors/get_region_data")
      .then(response => {
        this.regions = response.data
      })
      .catch(
        error => console.log(error)
      );
    },
    get_sectors_data: function(){
      axios
      .get("../routes_and_sectors/get_sector_data")
      .then(response => {
        this.sectors = response.data
      })
      .catch(
        error => console.log(error)
      );
    },

    add_mtp: function () {
      axios
      .post('/routes_and_sectors/mtp_add', {
          sector_id: this.sector_id,
          name: this.name,
          text: this.text,
          height: this.height,
      })
      .then(function (response) {
          window.location.href = '/routes_and_sectors';
      })
      .catch(error =>{
          if (error.response.status == 422) {
            this.errors = error.response.data.errors
          }
      })
    },

    save_all: function () {
      this.add_mtp()
    }

  }
}
</script>
