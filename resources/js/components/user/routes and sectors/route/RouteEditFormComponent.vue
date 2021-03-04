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
            <select class="form-control"  v-model="sellect_region">
              <option disabled>Please select sector</option>
              <option v-for="region in regions" :key="region.id" v-bind:value="region.id">{{ region.url_title  }}</option>
            </select>
          </div>
          <div class="col-xs-4">
            <div class="col-xs-12">
              <select class="form-control" v-model="sector_id">
                <option disabled>Please select sector</option>
                <option v-for="sector in sectors" :key="sector.id"  v-bind:value="sector.id">{{ sector.name }}</option>
              </select>
            </div>
          </div>
        </div>

        <div class="form-group clearfix">
          <label for="name" class='col-xs-2 control-label'> Gread </label>
          <div class="col-xs-4">
            <select class="form-control" v-model="route_type">
              <option disabled>Please select route type</option>

              <option value="sport climbing" v-if='category == "sport climibng"'>Stort climbing</option>
              <option value="sport climbing" v-else>Stort climbing</option>

              <option value="bouldering" v-if='category == "bouldering"'>Bouldering</option>
              <option value="bouldering" v-else>Bouldering</option>

            </select>
          </div>
          <div class="col-xs-4" v-if="route_type != ''">
            <div class="col-xs-6">
              <select class="form-control" v-if="route_type == 'sport climbing'" v-model="gread">
                <option v-for="sport in sport_route_gread" :key="sport" v-bind:value="sport" :selected="true" >{{ sport }} </option>
              </select>
            </div>
            <div class="col-xs-6">
              <select class="form-control" v-if="route_type == 'sport climbing'" v-model="or_gread">
                <option>Project</option>
                <option v-for="sport in sport_route_gread" :key="sport" v-bind:value="sport" :selected="true" >{{ sport }}</option>
              </select>
            </div>
          </div>
          <div class="col-xs-4">
            <div class="col-xs-6">
              <select class="form-control" v-if="route_type == 'bouldering'" v-model="gread">
                <option v-for="boulder in boulder_route_gread" :key="boulder" v-bind:value="boulder" :selected="true" >{{ boulder }}</option>
              </select>
            </div>
            <div class="col-xs-6">
              <select class="form-control" v-if="route_type == 'bouldering'" v-model="or_gread">
                <option>Project</option>
                <option v-for="boulder in boulder_route_gread" :key="boulder" v-bind:value="boulder" :selected="true" >{{ boulder }}</option>
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
            <ckeditor v-model="text" :config="editorConfig"></ckeditor>
          </div>
        </div>

        <div class="form-group clearfix">
          <label for="name" class='col-xs-2 control-label'> Bolts & height </label>
          <div class="col-xs-2">
              <input type="text" name="title" v-model="bolts" class="form-control" placeholder="Bolts"> 
          </div>
          <label for="name" class='col-xs-1 control-label'> Last carabin </label>
          <div class="col-xs-1">
              <input type="checkbox" v-model="last_carabin" >
          </div>
          <div class="col-xs-3">
            <input type="text" name="title" class="form-control" v-model="height" placeholder="Height"> 
          </div>
          <label for="name" class='col-xs-1 control-label'> M </label>
        </div>

        <div class="form-group clearfix">
          <label for="name" class='col-xs-2 control-label'> Bolter & Firs Ascent </label>
          <div class="col-xs-4">
            <input type="text" name="title" class="form-control" v-model="bolter" placeholder="Bolter"> 
          </div>
          <div class="col-xs-4">
            <input type="text" name="title" class="form-control" v-model="first_ascent" placeholder="First ascent"> 
          </div>
        </div>

      </form>
    </div>
  </div>
</template>

<script>
  export default {
    props: [
        "editing_roure_id",
    ],
    data() {
      return {
        // sellect_region:"",
        route_type: '',

        regions: [],
        sectors: [],

        article_id: "",
        category: "",
        gread: "",
        or_gread: "",
        name: "",
        text: "",
        last_carabin: "",
        height: "",
        bolts: "",
        bolter: "",
        first_ascent: "",

        editing_data: "",

        editing_url: '/routes_and_sectors/get_route_editing_data/',
        url: '',

        sport_route_gread: [
          "4",
          "5a", "5b", "5c", "5c+",
          "6a", "6a+", "6b", "6b+", "6c", "6c+",
          "7a", "7a+", "7b", "7b+", "7c", "7c+",
          "8a", "8a+", "8b", "8b+", "8c", "8c+",
          "9a", "9a+", "9b", "9b+", "9c", "9c+",
        ],
        boulder_route_gread: [
          "V1", "V1+",
          "V2", "V2+",
          "V3", "V3+",
          "V4", "V4+",
          "V5", "V5+",
          "V6", "V6+",
          "V7", "V7+",
          "V8", "V8+",
          "V9", "V9+",
          "V10", "V10+",
          "V11", "V11+",
          "V12", "V12+",
          "V13", "V13+",
          "V14", "V14+",
          "V15", "V15+",
          "V16", "V16+",
          "V17", "V17+",
          "V18", "V18+",
        ],

        published: "",
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
        .get("../get_region_data")
        .then(response => {
          this.regions = response.data
        })
        .catch(
          error => console.log(error)
        );
      },
      get_sectors_data: function(){
        axios
        .get("../get_sector_data")
        .then(response => {
          this.sectors = response.data
          // if (this.sector_id == ) {
            
          // }
        })
        .catch(
          error => console.log(error)
        );
      },
      get_route_editing_data() {
          this.url = this.editing_url + this.editing_roure_id
          axios
          .get(this.url)
          .then(response => {
              this.editing_data = response.data

              // send data in editing form value
              this.sector_id = this.editing_data.route['sector_id'],
              this.route_type = this.editing_data.route['category'],
              this.gread = this.editing_data.route['gread'],
              this.or_gread = this.editing_data.route['or_gread'],
              this.name = this.editing_data.route['name'],
              this.text = this.editing_data.route['text'],
              this.last_carabin = this.editing_data.route['last_carabin'],
              this.height = this.editing_data.route['height'],
              this.bolts = this.editing_data.route['bolts'],
              this.bolter = this.editing_data.route['bolter'],
              this.first_ascent = this.editing_data.route['first_ascent']
              
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

      edit_route: function () {
        axios
        .post('/routes_and_sectors/route_edit/' + this.editing_roure_id, {
            sector_id: this.sector_id,
            name: this.name,
            text: this.text,
            gread: this.gread,
            or_gread: this.or_gread,
            last_carabin: this.last_carabin,
            height: this.height,
            bolts: this.bolts,
            bolter: this.bolter,
            first_ascent: this.first_ascent,
        })
        .then(function (response) {
            console.log("route edited sucsesfule")
        })
        .catch(function (response){
            console.log("route edited is not sucsesfule!!!")
        })
      },

      save_all: function () {
        this.edit_route()
        window.location.href = '/routes_and_sectors';
      }
    }
  }
</script>  
