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
          <label for="name" class='col-xs-2 control-label'> Multy-pitch </label>
          <div class="col-xs-8">
            <select class="form-control" v-model="mtp_id">
              <option v-for="mtp in mtps" :key="mtp.id" v-bind:value="mtp.id">{{ mtp.name }}</option>
            </select>
          </div>
        </div>

        <div class="form-group clearfix">
          <label for="name" class='col-xs-2 control-label'> Gread </label>
          <div class="col-xs-8">
            <div class="col-xs-6">
              <select class="form-control" v-model="gread">
                <option v-for="sport in sport_route_gread" :key="sport" v-bind:value="sport">{{ sport }}</option>
              </select>
            </div>
            <div class="col-xs-6">
              <select class="form-control" v-model="or_gread">
                <option selected>No</option>
                <option v-for="sport in sport_route_gread" :key="sport" v-bind:value="sport">{{ sport }}</option>
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

      regions: [],
      sectors: [],
      mtps: [],

      mtp_id: "",
      gread: "",
      or_gread: "",
      name: "",
      text: "",
      last_carabin: "",
      height: "",
      bolts: "",
      bolter: "",
      first_ascent: "",

      editing_url: '/routes_and_sectors/get_mtp_pitch_editing_data/',

      sport_route_gread: [
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
    this.get_mtp_data()
    this.get_mtp_pitch_editing_data()
  },

  methods: {
    get_mtp_data: function(){
      axios
      .get("../get_mtp_data")
      .then(response => {
        this.mtps = response.data
        console.log(this.mtps);
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
            this.gread = this.editing_data.mtp_pitch['gread'],
            this.or_gread = this.editing_data.mtp_pitch['or_gread'],
            this.name = this.editing_data.mtp_pitch['name'],
            this.text = this.editing_data.mtp_pitch['text'],
            this.height = this.editing_data.mtp_pitch['height'],
            this.bolts = this.editing_data.mtp_pitch['bolts'],
            this.bolter = this.editing_data.mtp_pitch['bolter'],
            this.first_ascent = this.editing_data.mtp_pitch['first_ascent']
            
            for (let index = 0; index < this.sectors.length; index++) {
                if (this.sectors[index]['id'] == this.sector_id) {
                    this.select_region = this.sectors[index]['article_id']
                }
            }

        })
        .catch(
            error => console.log(error)
        );
    },

    edit_mtp_pitch: function () {
      axios
      .post('/routes_and_sectors/mtp_pitch_add', {
          mtp_id: this.mtp_id,
          name: this.name,
          gread: this.gread,
          or_gread: this.or_gread,
          text: this.text,
          height: this.height,
          bolter: this.bolter,
          bolts: this.bolts,
          first_ascent: this.first_ascent,
      })
      .then(function (response) {
          console.log("route added sucsesfule")
      })
      .catch(function (response){
          console.log("route added is not sucsesfule!!!")
      })
    },

    save_all: function () {
      this.edit_mtp_pitch()
      window.location.href = '/routes_and_sectors';
    }

  }
}
</script>
