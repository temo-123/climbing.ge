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
          <label for="name" class='col-md-2 control-label'> Grade </label>
          <div class="col-md-4">
            <select class="form-control" v-model="route_type">
              <option disabled>Please select route type</option>

              <option value="sport climbing" v-if='category == "sport climibng"'>Stort climbing</option>
              <option value="sport climbing" v-else>Stort climbing</option>

              <option value="top" v-if='category == "top"'>Top rope</option>
              <option value="top" v-else>Top rope</option>

              <option value="tred" v-if='category == "tred"'>Tred climibng</option>
              <option value="tred" v-else>Tred climibng</option>

              <option value="bouldering" v-if='category == "bouldering"'>Bouldering</option>
              <option value="bouldering" v-else>Bouldering</option>

            </select>
          </div>
          <div class="col-md-4" v-if="route_type != ''">
            <div class="col-md-6">
              <select class="form-control" v-if="route_type == 'sport climbing' || route_type == 'top' || route_type == 'tred'" v-model="grade">
                <option v-bind:value="NULL"> No grade </option>
                <option v-for="sport in sport_route_grade" :key="sport" v-bind:value="sport" :selected="true" >{{ sport }} </option>
              </select>
            </div>
            <div class="col-md-6">
              <select class="form-control" v-if="route_type == 'sport climbing' || route_type == 'top' || route_type == 'tred'" v-model="or_grade">
                <option v-bind:value="NULL"> No grade </option>
                <option>Project</option>
                <option v-for="sport in sport_route_grade" :key="sport" v-bind:value="sport" :selected="true" >{{ sport }}</option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="col-md-6">
              <select class="form-control" v-if="route_type == 'bouldering'" v-model="grade">
                <option v-bind:value="NULL"> No grade </option>
                <option v-for="boulder in boulder_route_grade" :key="boulder" v-bind:value="boulder" :selected="true" >{{ boulder }}</option>
              </select>
            </div>
            <div class="col-md-6">
              <select class="form-control" v-if="route_type == 'bouldering'" v-model="or_grade">
                <option v-bind:value="NULL"> No grade </option>
                <option>Project</option>
                <option v-for="boulder in boulder_route_grade" :key="boulder" v-bind:value="boulder" :selected="true" >{{ boulder }}</option>
              </select>
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
          <div class="col-md-8">
            <input type="text" name="name" v-model="name" class="form-control" placeholder="Route name"> 
              <div class="alert alert-danger" role="alert" v-if="errors.name">
                {{ errors.name[0] }}
              </div>
          </div>
        </div>

        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> text </label>
          <div class="col-md-8">
            <ckeditor v-model="text" :config="editorConfig"></ckeditor>
          </div>
        </div>

        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> Bolts & height </label>
          <div class="col-md-2" v-if="route_type != '' && route_type == 'sport climbing'">
              <input type="text" name="title" v-model="bolts" class="form-control" placeholder="Bolts"> 
          </div>
          <label for="name" class='col-md-1 control-label' v-if="route_type != '' && route_type == 'sport climbing'"> Last carabin </label>
          <div class="col-md-1" v-if="route_type != '' && route_type == 'sport climbing'">
              <input type="checkbox" v-model="last_carabin" >
          </div>
          <div class="col-md-3">
            <input type="text" name="title" class="form-control" v-model="height" placeholder="Height"> 
          </div>
          <label for="name" class='col-md-1 control-label'> M </label>
        </div>

        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> Bolter & Bolting data </label>
          <div class="col-md-4">
            <input type="text" name="title" class="form-control" v-model="bolter" placeholder="Bolter"> 
          </div>
          <div class="col-md-4">
            <input type="text" name="title" class="form-control" v-model="bolting_data" placeholder="Bolting Data"> 
          </div>
        </div>

        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'>Firs Ascent </label>
          <div class="col-md-8">
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
        sellected_region:"",
        route_type: '',
        errors: [],

        regions: [],
        sectors: [],
        sector_id: "",

        article_id: "",
        category: "",
        grade: "",
        or_grade: "",
        name: "",
        text: "",
        last_carabin: "",
        height: "",
        bolts: "",
        bolter: "",
        bolting_data: "",
        first_ascent: "",

        editing_data: "",

        editing_url: '/routes_and_sectors/get_route_editing_data/',
        url: '',

        sport_route_grade: [
          "4",
          "5a", "5b", "5c", "5c+",
          "6a", "6a+", "6b", "6b+", "6c", "6c+",
          "7a", "7a+", "7b", "7b+", "7c", "7c+",
          "8a", "8a+", "8b", "8b+", "8c", "8c+",
          "9a", "9a+", "9b", "9b+", "9c", "9c+",
        ],
        boulder_route_grade: [
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
              this.grade = this.editing_data.route['grade'],
              this.or_grade = this.editing_data.route['or_grade'],
              this.name = this.editing_data.route['name'],
              this.text = this.editing_data.route['text'],
              this.last_carabin = this.editing_data.route['last_carabin'],
              this.height = this.editing_data.route['height'],
              this.bolts = this.editing_data.route['bolts'],
              this.bolter = this.editing_data.route['bolter'],
              this.bolting_data = this.editing_data.route['bolting_data'],
              this.first_ascent = this.editing_data.route['first_ascent']

              this.value_sector_region(this.editing_data)
              this.value_route_grade()
          })
          .catch(
              error => console.log(error)
          );
      },

      value_sector_region: function(editing_data){
        if (editing_data.route['sector_id'] != "" || editing_data.route['sector_id'] != NULL) {
          for (let index = 0; index < this.sectors.length; index++) {
            if (this.sectors[index]['id'] == this.sector_id) {
              this.sellected_region = this.sectors[index]['article_id']
            }
          }
        }
      },
      value_route_grade: function(){
        if (this.route_type == 1) {
          this.route_type = 'sport climbing'

        }
        if (this.route_type == 2) {
          this.route_type = 'top'
          
        }
        if (this.route_type == 3) {
          this.route_type = 'tred'
        }
        if (this.route_type == 4) {
          this.route_type = 'bouldering'
        }
      },


      edit_route: function () {
        this.validate_form_info(this.route_type, this.grade, this.or_grade)
        axios
        .post('/routes_and_sectors/route_edit/' + this.editing_roure_id, {
            sector_id: this.sector_id,
            route_type: this.route_type,
            name: this.name,
            text: this.text,
            grade: this.grade,
            or_grade: this.or_grade,
            last_carabin: this.last_carabin,
            height: this.height,
            bolts: this.bolts,
            bolter: this.bolter,
            bolting_data: this.bolting_data,
            first_ascent: this.first_ascent,
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


      validate_form_info: function (route_type, grade, or_grade) {
        if (route_type == "bouldering") {
          this.bolts = ''
          this.last_carabin = ''
        }
        if (route_type == "bouldering") {
          this.sport_route_grade.forEach(sport_grade => {
            if (grade == sport_grade) {
              this.grade = ''
            }
            if (or_grade == sport_grade) {
              this.or_grade = ''
            }
          });
        }
        if (route_type == "sport climbing") {
          this.boulder_route_grade.forEach(boulder_grade => {
            if (grade == boulder_grade) {
              this.grade = ''
            }
            if (or_grade == boulder_grade) {
              this.or_grade = ''
            }
          });
        }
      },

      save_all: function () {
        this.edit_route()
      }
    }
  }
</script>  
