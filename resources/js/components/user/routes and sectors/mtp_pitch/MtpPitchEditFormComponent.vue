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
            <select class="form-control"  v-model="sellected_region">
              <option v-for="region in regions" :key="region.id" v-bind:value="region.id">{{ region.url_title  }}</option>
            </select>
          </div>
          <div class="col-xs-4">
            <div class="col-xs-12">
              <select class="form-control" v-if="sellected_region != ''" v-model="sellected_sector">
                <option v-for="sector in sectors" :key="sector.id" v-if="sellected_region == sector.article_id" v-bind:value="sector.id">{{ sector.name }}</option>
              </select>
            </div>
          </div>
        </div>

        <div class="form-group clearfix">
          <label for="name" class='col-xs-2 control-label'> Multy-pitch </label>
          <div class="col-xs-8">
            <select class="form-control" v-model="mtp_id">
              <option v-for="mtp in mtps" :key="mtp.id" v-bind:value="mtp.id">{{ mtp.name }}</option>
            </select>
            <div class="alert alert-danger" role="alert" v-if="errors.mtp_id">
              {{ errors.mtp_id[0] }}
            </div>
          </div>
        </div>

        <div class="form-group clearfix">
          <label for="name" class='col-xs-2 control-label'> grade </label>
          <div class="col-xs-4">
            <select class="form-control" v-model="grade">
              <option v-bind:value="NULL"> No grade </option>
              <option>Project</option>
              <option v-for="sport in sport_route_grade" :key="sport" v-bind:value="sport">{{ sport }}</option>
            </select>
          </div>
          <div class="col-xs-4">
            <select class="form-control" v-model="or_grade">
              <option v-bind:value="NULL"> No grade </option>
              <option v-for="sport in sport_route_grade" :key="sport" v-bind:value="sport">{{ sport }}</option>
            </select>
          </div>
        </div>
        
        <div class="form-group clearfix" v-if="errors.grade">
            <div class="col-xs-12">
              <div class="alert alert-danger" role="alert">
                {{ errors.grade[0] }}
              </div>
            </div>
        </div>

        <div class="form-group clearfix">
          <label for="name" class='col-xs-2 control-label'> Route name </label>
          <div class="col-xs-8">
            <input type="text" name="name" v-model="name" class="form-control" placeholder="Route name.."> 
            <div class="alert alert-danger" role="alert" v-if="errors.name">
              {{ errors.name[0] }}
            </div>
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
          <div class="col-xs-4">
              <input type="text" name="title" v-model="bolts" class="form-control" placeholder="Bolts"> 
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
    'editing_pitch_id'
  ],
  data() {
    return {
      sellected_region: '',
      sellected_sector: '',
      selected_mtp: '',

      errors: [],

      regions: [],
      sectors: [],
      mtps: [],

      mtp_id: "",
      grade: "",
      or_grade: "",
      name: "",
      text: "",
      last_carabin: "",
      height: "",
      bolts: "",
      bolter: "",
      first_ascent: "",

      editing_url: '/routes_and_sectors/get_mtp_pitch_editing_data/',

      sport_route_grade: [
        "4",
        "5a", "5b", "5c", "5c+",
        "6a", "6a+", "6b", "6b+", "6c", "6c+",
        "7a", "7a+", "7b", "7b+", "7c", "7c+",
        "8a", "8a+", "8b", "8b+", "8c", "8c+",
        "9a", "9a+", "9b", "9b+", "9c", "9c+",
      ],
    }
  },

  mounted() {
    this.get_region_data()
    this.get_sectors_data()
    this.get_mtp_data()
    this.get_mtp_pitch_editing_data()
  },

  methods: {
    get_region_data: function(){
      axios
      .get("../../routes_and_sectors/get_region_data")
      .then(response => {
        this.regions = response.data
      })
      .catch(
        error => console.log(error)
      );
    },
    get_sectors_data: function(){
      axios
      .get("../../routes_and_sectors/get_sector_data")
      .then(response => {
        this.sectors = response.data
      })
      .catch(
        error => console.log(error)
      );
    },
    get_mtp_data: function(){
      axios
      .get("../../routes_and_sectors/get_mtp_data")
      .then(response => {
        this.mtps = response.data
      })
      .catch(
        error => console.log(error)
      );
    },

    get_mtp_pitch_editing_data: function(){
        this.url = this.editing_url + this.editing_pitch_id
        axios
        .get(this.url)
        .then(response => {
            this.editing_data = response.data

            // send data in editing form value
            this.mtp_id = this.editing_data.mtp_pitch['mtp_id'],
            this.grade = this.editing_data.mtp_pitch['grade'],
            this.or_grade = this.editing_data.mtp_pitch['or_grade'],
            this.name = this.editing_data.mtp_pitch['name'],
            this.text = this.editing_data.mtp_pitch['text'],
            this.height = this.editing_data.mtp_pitch['height'],
            this.bolts = this.editing_data.mtp_pitch['bolts'],
            this.bolter = this.editing_data.mtp_pitch['bolter'],
            this.first_ascent = this.editing_data.mtp_pitch['first_ascent']
            
            this.value_sector(response.data)
        })
        .catch(
            error => console.log(error)
        );
    },


    value_region: function(editing_data){
      if (editing_data.route['sector_id'] != "" || editing_data.route['sector_id'] != NULL) {
        for (let index = 0; index < this.sectors.length; index++) {
          if (this.sectors[index]['id'] == this.sector_id) {
            this.sellected_regfion = this.sectors[index]['article_id']
          }
        }
      }
    },
    value_sector: function(editing_data){
      if (editing_data.mtp_pitch['mtp_id'] != "" || editing_data.mtp_pitch['mtp_id'] != NULL) {
console.log(editing_data.mtp_pitch);
        for (let index = 0; index < editing_data.mtp_pitch.length; index++) {
          if (this.mtp_pitch[index]['id'] == editing_data.mtp_pitch['mtp_id']) {
            this.sellected_sector = editing_data.mtp_pitch['mtp_id']
            console.log(editing_data.mtp_pitch['mtp_id']);
          }
        }
      }
    },


    edit_mtp_pitch: function () {
      axios
      .post('/routes_and_sectors/mtp_pitch_add', {
          mtp_id: this.mtp_id,
          name: this.name,
          grade: this.grade,
          or_grade: this.or_grade,
          text: this.text,
          height: this.height,
          bolter: this.bolter,
          bolts: this.bolts,
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

    save_all: function () {
      this.edit_mtp_pitch()
    }

  }
}
</script>
