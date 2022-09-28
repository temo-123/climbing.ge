<template>
  <div class="col-md-12">

    <div class="row">
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

    <div class="row">
        <div class="form-group">  
            <button type="submit" class="btn btn-primary" @click="save_and_go_beck()" >Save</button>
            <p>Save and go to table page</p>
        </div>
    </div>

    <div class="row">
        <div class="form-group">  
            <button type="submit" class="btn btn-primary" @click="save_and_add_more_route()" >Save and add mode</button>
            <p>Save and add more route in this sector</p>
        </div>
    </div>

    <div class="wrapper container-fluid container">
      <form action="">
        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> Region </label>
          <div class="col-md-5">
            <select class="form-control"  v-model="sellected_region">
              <option v-for="region in regions" :key="region.id" v-bind:value="region.id">{{ region.url_title  }}</option>
            </select>
          </div>
          <div class="col-md-5">
            <select class="form-control" v-if="sellected_region != ''" v-model="data.sector_id">
              <!-- <option v-for="sector in sectors" :key="sector.id" v-if="sellected_region == sector.article_id" v-bind:value="sector.id">{{ sector.name }}</option> -->
              <option v-for="sector in sectors" :key="sector.id" v-bind:value="sector.id">{{ sector.name }}</option>
            </select>
          </div>
        </div>
        
        <!-- <div class="form-group clearfix row" v-if="sector_id != ''">
          <div class="col-md-12">
            <div class="row">
                <Editor />
            </div>
          </div>
        </div> -->

        <div class="form-group clearfix row" v-if="errors.sector_id">
            <div class="col-md-12">
              <div class="alert alert-danger" role="alert">
                {{ errors.sector_id[0] }}
              </div>
            </div>
        </div>

        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> Grade </label>
          <div class="col-md-5">
            <select class="form-control"  v-model="data.route_type">
              <option disabled>Please select route type</option>
              <option value="sport climbing">Stort climbing</option>
              <option value="bouldering">Bouldering</option>
              <option value="tred">Tred Climbing</option>
              <option value="top">Top Roupe</option>
            </select>
          </div>

          <div class="col-md-5" v-if="data.route_type != '' && data.route_type == 'sport climbing' || data.route_type == 'top'|| data.route_type == 'tred'">
            <div class="row">
              <div class="col-md-6">
                <select class="form-control" v-if="data.route_type != '' && data.route_type == 'sport climbing' || data.route_type == 'top'|| data.route_type == 'tred'" v-model="data.grade">
                  <option v-bind:value="NULL"> No grade </option>
                  <option>Project</option>
                  <option v-for="sport in sport_route_grade" :key="sport" v-bind:value="sport" :selected="true" >{{ sport }}</option>
                </select>
              </div>
              <div class="col-md-6">
                <select class="form-control" v-if="data.route_type != '' && data.route_type == 'sport climbing' || data.route_type == 'top'|| data.route_type == 'tred'" v-model="data.or_grade">
                  <option v-bind:value="NULL"> No grade </option>
                  <option>Project</option>
                  <option v-for="sport in sport_route_grade" :key="sport" v-bind:value="sport" :selected="true" >{{ sport }}</option>
                </select>
              </div>
            </div>
          </div>

          <div class="col-md-5" v-if="data.route_type != '' && data.route_type == 'bouldering'">
            <div class="row">
              <div class="col-md-6">
                <select class="form-control" v-if="data.route_type != '' && data.route_type == 'bouldering'" v-model="data.grade">
                  <option v-bind:value="NULL"> No grade </option>
                  <option>Project</option>
                  <option v-for="boulder in boulder_route_grade" :key="boulder" v-bind:value="boulder" :selected="true" >{{ boulder }}</option>
                </select>
              </div>
              <div class="col-md-6">
                <select class="form-control" v-if="data.route_type != '' && data.route_type == 'bouldering'" v-model="data.or_grade">
                  <option v-bind:value="NULL"> No grade </option>
                  <option>Project</option>
                  <option v-for="boulder in boulder_route_grade" :key="boulder" v-bind:value="boulder" :selected="true" >{{ boulder }}</option>
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
            <input type="text" name="name" v-model="data.name" class="form-control" placeholder="Route name.."> 
              <div class="alert alert-danger" role="alert" v-if="errors.name">
                {{ errors.name[0] }}
              </div>
          </div>
        </div>

        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> Description (Text) </label>
          <div class="col-md-10">
            <ckeditor v-model="data.text"></ckeditor>
          </div>
        </div>

        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> Bolts & height </label>

          <div class="col-md-1" v-if="data.route_type != '' && data.route_type == 'sport climbing'">
            <label for="name" class='col-md-1 control-label'> Bolts: </label>
          </div>
          <div class="col-md-2" v-if="data.route_type != '' && data.route_type == 'sport climbing'">
              <input type="text" name="title" v-model="data.bolts" class="form-control" placeholder="Bolts"> 
          </div>

          <div class="col-md-1">
            <label for="name" class='col-md-1 control-label'> Metrs: </label>
          </div>
          <div class="col-md-2">
            <input type="text" name="title" class="form-control" v-model="data.height" placeholder="Height"> 
          </div>

          <div class="col-md-1">
          </div>

          <div class="col-md-3" v-if="data.route_type != '' && data.route_type == 'sport climbing'">
            <select class="form-control" v-model="data.anchor_type">
              <option disabled>Anchor type</option>
              <option>Chain</option>
              <option>Chain with carabiner</option>
              <option>Chain with ring</option>
              <option>2 independent chains</option>
              <option>2 bolts with carabiner</option>
            </select>
          </div>
        </div>

        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> Author & Creation date </label>
          <div class="col-md-5">
            <input type="text" name="title" class="form-control" v-model="data.author" placeholder="Bolter"> 
          </div>
          <div class="col-md-5">
            <input type="text" name="title" class="form-control" v-model="data.creation_data" placeholder="Bolting Data"> 
          </div>
        </div>

        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'>Firs Ascent </label>
          <div class="col-md-5">
            <input type="text" name="title" class="form-control" v-model="data.first_ascent" placeholder="First ascent"> 
          </div>
        </div>

      </form>
    </div>
  </div>
</template>

<script>
import Editor from '../../../items/canvas/EditorComponent.vue'
export default {
        components: {
            Editor,
        },
  data() {
    return {
      sellected_region: '',
      // data.route_type: '',
      errors: [],

      regions: [],
      sectors: [],

      status: "",
      problem_status: "",

      data: {
        article_id: "",
        sector_id: "",

        grade: "",
        or_grade: "",

        name: "",
        text: "",

        // last_carabin: "",
        height: "",
        bolts: "",

        author: "",
        creation_data: "",
        first_ascent: "",

        anchor_type: "",
        route_type: "",
      },

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
  },

  beforeRouteLeave (to, from, next) {
      if(this.is_back_action = true){
          if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
              this.is_back_action = false;
              next()
          } else {
              next(false)
          }
      }
  },

  methods: {
    get_region_data: function(){
      axios
      .post("../api/article/",{category: 'outdoor'})
      .then(response => {
        this.regions = response.data
      })
      .catch(
        error => console.log(error)
      );
    },
    
    get_sectors_data: function(){
      axios
      .get("../api/sector/")
      .then(response => {
        this.sectors = response.data
      })
      .catch(
        error => console.log(error)
      );
    },

    add_route: function () {
      // this.validate_form_info(this.data.route_type, this.data.grade, this.data.or_grade)
      // var status = ''

      axios
      .post('../../api/add_route/', {
          data: this.data,
      })
      .then(function (response) {
        // this.$router.go(-1)
        this.status =  "complate"
      })
      .catch(error =>{
          // console.log(error)
          // if (error.response.status == 422) {
          //   this.errors = error.response.data.errors
          // }
          this.status = "error"
      })

      // return status
    },


      // validate_form_info: function (route_type, grade, or_grade) {
      //   if (route_type == "bouldering") {
      //     this.bolts = ''
      //     this.last_carabin = ''
      //   }
      //   if (route_type == "bouldering") {
      //     this.sport_route_grade.forEach(sport_grade => {
      //       if (grade == sport_grade) {
      //         this.grade = ''
      //       }
      //       if (or_grade == sport_grade) {
      //         this.or_grade = ''
      //       }
      //     });
      //   }
      //   if (route_type == "sport climbing") {
      //     this.boulder_route_grade.forEach(boulder_grade => {
      //       if (grade == boulder_grade) {
      //         this.grade = ''
      //       }
      //       if (or_grade == boulder_grade) {
      //         this.or_grade = ''
      //       }
      //     });
      //   }
      // },

      save_and_add_more_route: function(){
        this.problem_status = ""
        this.status = ""
        
        this.add_route()

        if(this.status == 'complate'){
          this.data.grade = ''
          this.data.or_grade = ''
          this.data.name = ''
          this.data.text = ''
          this.data.height = ''
          this.data.bolts = ''
          this.data.author = ''
          this.data.creation_data = ''
          this.data.first_ascent = ''
          this.data.anchor_type = ''
          this.data.route_type = ''
        }
        if(this.status == 'error'){
          this.problem_status = "You have some problem"
        }
      },

      save_and_go_beck: function(){
        this.problem_status = ""
        this.status = ""

        this.add_route()

        if(this.status == 'complate'){
          go_back()
        }
        if(this.status == 'error'){
          this.problem_status = "You have some problem"
        }
      },

    go_back: function() {
      this.$router.go(-1)
    },
  }
}
</script>

// sport climbing 1
// top 2
// tred 3
// bouldering 4