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
        </div>

        <div class="form-group clearfix">
          <label for="name" class='col-xs-2 control-label'> Name </label>
          <div class="col-xs-8">
            <input type="text" name="title" class="form-control"> 
          </div>
        </div>

        <div class="form-group clearfix">
          <label for="name" class='col-xs-2 control-label'> text </label>
          <div class="col-xs-8">
            <textarea type="text"  name="text" rows="15" class="form-cotrol md-textarea form-control"></textarea>
          </div>
        </div>

        <div class="form-group clearfix">
          <label for="name" class='col-xs-2 control-label'> Relief </label>
          <div class="col-xs-8">
            <div class="col-xs-4">
              <img class="sun_svg" src="/public/images/svg/overhang.svg" alt="">
              <input type="checkbox">
              <!-- <input type="checkbox" checked> -->
            </div>
            <div class="col-xs-4">
              <img class="sun_svg" src="/public/images/svg/slabby.svg" alt="">
              <input type="checkbox">
            </div>
            <div class="col-xs-4">
              <img class="sun_svg" src="/public/images/svg/vertical.svg" alt="">
              <input type="checkbox">
            </div>
          </div>
        </div>

        <div class="form-group clearfix">
          <label for="name" class='col-xs-2 control-label'> Sun </label>

          <div class="col-xs-8">
            <div class="col-xs-2">
              <input type="checkbox">
              <img class="sun_svg" src="/public/images/svg/in shade after 10.00 am.svg" alt="">
            </div>
            <div class="col-xs-2">
              <input type="checkbox">
              <img class="sun_svg" src="/public/images/svg/in shade after 15.00 pm.svg" alt="">
            </div>
            <div class="col-xs-2">
              <input type="checkbox">
              <img class="sun_svg" src="/public/images/svg/in shade befor noon.svg" alt="">
            </div>
            <div class="col-xs-2">
              <input type="checkbox">
              <img class="sun_svg" src="/public/images/svg/in shade in the afternoon.svg" alt="">
            </div>
            <div class="col-xs-2">
              <input type="checkbox">
              <img class="sun_svg" src="/public/images/svg/in the shade whole day.svg" alt="">
            </div>
            <div class="col-xs-2">
              <input type="checkbox">
              <img class="sun_svg" src="/public/images/svg/in the sun the whole day.svg" alt="">
            </div>
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

    add_sector: function () {
      axios
      .post('/sector_add' + this.category, {
          published: this.published,
          completed: this.completed,
          map: this.map,
          weather: this.weather,
      })
      .then(Response => {
          console.log(response)
      })
      .catch(
          error => console.log(error)
      )
    },

  }
}
</script>
