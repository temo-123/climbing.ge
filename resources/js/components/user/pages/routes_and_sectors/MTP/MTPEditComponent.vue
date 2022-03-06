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
          <label for="name" class='col-md-2 control-label'> Region / Sector </label>
          <div class="col-md-4">
            <select class="form-control"  v-model="sellected_region">
              <option v-for="region in regions" :key="region.id" v-bind:value="region.id">{{ region.url_title  }}</option>
            </select>
          </div>
          <div class="col-md-4">
            <div class="col-md-12">
              <select class="form-control" v-if="sellected_region != ''" v-model="sector_id">
                <option v-for="sector in sectors" :key="sector.id" v-if="sellected_region == sector.article_id" v-bind:value="sector.id">{{ sector.name }}</option>
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

      </form>
    </div>
  </div>
</template>

<script>
export default {
  props: [
      "editing_mtp_id",
  ],
  data() {
    return {
      sellected_region: '',
      route_type: '',

      regions: [],
      sectors: [],

      errors: [],

      sector_id: "",
      name: "",
      text: "",
      height: "",

        editing_url: '/routes_and_sectors/get_mtp_editing_data/',
        url: '',
    }
  },

  mounted() {
    this.get_region_data()
    this.get_sectors_data()
    this.get_mtp_editing_data()
  },

  methods: {
    get_region_data: function(){
      axios
      .get("/routes_and_sectors/get_region_data")
      .then(response => {
        this.regions = response.data
      })
      .catch(
        error => console.log(error)
      );
    },
    get_sectors_data: function(){
      axios
      .get("/routes_and_sectors/get_sector_data")
      .then(response => {
        this.sectors = response.data
      })
      .catch(
        error => console.log(error)
      );
    },

    get_mtp_editing_data() {
      this.url = this.editing_url + this.editing_mtp_id
      axios
      .get(this.url)
      .then(response => {
          this.editing_data = response.data

        console.log(this.editing_data);
          // send data in editing form value
          this.sector_id = this.editing_data.mtp['sector_id'],
          this.name = this.editing_data.mtp['name'],
          this.text = this.editing_data.mtp['text'],
          this.height = this.editing_data.mtp['height']

          this.value_sector_region(this.editing_data)
      })
      .catch(
          error => console.log(error)
      );
    },

    value_sector_region: function(editing_data){
      if (editing_data.mtp['sector_id'] != "" || editing_data.mtp['sector_id'] != NULL) {
        for (let index = 0; index < this.sectors.length; index++) {
          if (this.sectors[index]['id'] == this.sector_id) {
            this.sellected_region = this.sectors[index]['article_id']
          }
        }
      }
    },

    edit_global_article: function () {
      axios
      .post('/routes_and_sectors/mtp_edit/' + this.editing_mtp_id, {
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
      this.edit_global_article()
    }

  }
}
</script>
