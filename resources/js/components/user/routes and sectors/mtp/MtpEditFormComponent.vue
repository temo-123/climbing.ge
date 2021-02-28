<template>
  <div class="col_md_12">
    <div class="row">
        <div class="form-group">  
            <button type="submit" class="btn btn-primary" v-on:click="save_all()" >Save</button>
        </div>
    </div>
    <div class="wrapper container-fluid container">
      <form action="">
        
        <div class="form-group clearfix">
          <label for="name" class='col-xs-2 control-label'> Region </label>
          <div class="col-xs-4">
            <select class="form-control"  v-model="sellected">
              <option disabled>Please select sector</option>
              <option v-for="region in regions" :key="region.id" v-bind:value="region.id">{{ region.url_title  }}</option>
            </select>
          </div>
          <div class="col-xs-4">
            <div class="col-xs-12">
              <select class="form-control" v-if="sellected != ''" v-model="sector_id">
                <option disabled>Please select sector</option>
                <option v-for="sector in sectors" :key="sector.id" :if="sellected == sector.article_id" v-bind:value="sector.id">{{ sector.name }}</option>
              </select>
            </div>
          </div>
        </div>

        <div class="form-group clearfix">
          <label for="name" class='col-xs-2 control-label'> Route name </label>
          <div class="col-xs-8">
            <input type="text" name="name" v-model="name" class="form-control" placeholder="Route name.."> 
          </div>
        </div>

        <div class="form-group clearfix">
          <label for="name" class='col-xs-2 control-label'> text </label>
          <div class="col-xs-8">
            <!-- <textarea type="text"  name="text" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            <ckeditor v-model="text" :config="editorConfig"></ckeditor>
          </div>
        </div>

        <div class="form-group clearfix">
          <label for="name" class='col-xs-2 control-label'> Height </label>
          <div class="col-xs-6">
            <input type="text" name="title" class="form-control" v-model="height" placeholder="Height"> 
          </div>
          <label for="name" class='col-xs-1 control-label'> M </label>
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
      sellected: '',
      route_type: '',

      regions: [],
      sectors: [],

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
    this.get_route_editing_data()
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
    get_route_editing_data() {
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
              
              for (let index = 0; index < this.sectors.length; index++) {
                if (this.sectors[index]['id'] == this.sector_id) {
                  this.select_region = this.sectors[index]['article_id']
                }
              }
              console.log(this.select_region);

          })
          .catch(
              error => console.log(error)
          );
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
          console.log("route added sucsesfule")
      })
      .catch(function (response){
          console.log("route added is not sucsesfule!!!")
      })
    },

    save_all: function () {
      this.edit_global_article()
      window.location.href = '/routes_and_sectors';
    }

  }
}
</script>
