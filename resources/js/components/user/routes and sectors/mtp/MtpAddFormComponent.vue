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
                <option v-for="sector in sectors" :key="sector.id" v-if="sellected == sector.article_id" v-bind:value="sector.id">{{ sector.name }}</option>
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
          <label for="name" class='col-xs-2 control-label'> Bolter & height </label>
          <div class="col-xs-4">
            <input type="text" name="title" class="form-control" v-model="bolter" placeholder="Bolter"> 
          </div>
          <div class="col-xs-3">
            <input type="text" name="title" class="form-control" v-model="height" placeholder="Height"> 
          </div>
          <label for="name" class='col-xs-1 control-label'> M </label>
        </div>

        <div class="form-group clearfix">
          <label for="name" class='col-xs-2 control-label'>Firs Ascent </label>
          <div class="col-xs-8">
            <input type="text" name="title" class="form-control" v-model="first_ascent" placeholder="First ascent"> 
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
      sellected: '',
      route_type: '',

      regions: [],
      sectors: [],

      sector_id: "",
      title: "",
      text: "",
      last_carabin: "",
      height: "",
      bolter: "",
      first_ascent: "",

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

    add_global_article: function () {
      axios
      .post('/routes_and_sectors/mtp_add', {
          sector_id: this.sector_id,
          gread: this.gread,
          or_gread: this.or_gread,
          name: this.name,
          text: this.text,
          height: this.height,
          bolts: this.bolts,
          bolter: this.bolter,
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
      this.add_global_article()
      window.location.href = this.back_url;
    }

  }
}
</script>
