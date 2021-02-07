<template>
  <div class="col-md-12">
    <div class="row">
      <div class="form-group">
        <button type="submit" class="btn btn-primary" v-on:click="save_all()">Save</button>
      </div>
    </div>
  <!-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button> -->
<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  	<form @submit.prevent="edit_sector" id="js_form" class="contact-form" method="POST" enctype="multipart/form-data">
			<div class="form-group clearfix">
				<label for="name" class='col-md-2 control-label'> Sector name </label>
				<div class="col-md-8">
						<input type="text" v-model="name" name="name" placeholder="Name" class="form-control textarea">
				</div>
			</div>
	
			<div class="form-group clearfix">
				<label class='col-md-2 control-label'> Region </label>
				<div class="col-md-8">
					<select class="form-control" name="article_id" v-model="article_id"> 
						<option v-for="region in regions" :key="region" v-bind:value="region.id">{{ region.url_title }}</option>
					</select> 
				</div>
			</div>
	
			<div class="form-group clearfix">
				<label class='col-md-2 control-label'> Text </label>
				<div class="col-md-8">
          <ckeditor v-model="text" :config="editorConfig"></ckeditor>
				</div>
			</div>	

      <hr>

			<div class="form-group clearfix">
				<label class='col-md-2 control-label'> Shadow time: </label>
				<div class="col-md-8">
					<div class="col-md-4">
							<label for="vehicle1" class="sector_checbox_title"> all_day_in_shade</label><br>
							<input type="checkbox" v-model="all_day_in_shade" name="all_day" value="1" class="largerCheckbox">
					</div>
					<div class="col-md-4">
							<label for="vehicle1"> all_day_in_sun</label><br>
							<input type="checkbox" v-model="all_day_in_sun" name="no_sun" value="1"  class="largerCheckbox">
					</div>
					<div class="col-md-4">
							<label for="vehicle1"> in_the_shade_afternoon</label><br>
							<input type="checkbox" v-model="in_the_shade_afternoon" name="afternoon" value="1"  class="largerCheckbox">
					</div>
        </div>
				<div class="col-md-8  offset-md-2">
					<div class="col-md-4">
							<label for="vehicle1"> in_the_shade_befornoon</label><br>
							<input type="checkbox" v-model="in_the_shade_befornoon" name="beforenoon" value="1"  class="largerCheckbox">
					</div>
					<div class="col-md-4">
							<label for="vehicle1"> in_shade_after_10</label><br>
							<input type="checkbox" v-model="in_shade_after_10" name="beforenoon" value="1"  class="largerCheckbox">
					</div>
					<div class="col-md-4">
							<label for="vehicle1"> in_shade_after_15</label><br>
							<input type="checkbox" v-model="in_shade_after_15" name="beforenoon" value="1"  class="largerCheckbox">
					</div>
				</div>
			</div>

      <hr>

			<div class="form-group clearfix">
				<label class='col-md-2 control-label'> Sector relief: </label>
				<div class="col-md-8">
					<div class="col-md-4">
							<label for="vehicle1"> slabby</label><br>
							<input type="checkbox" v-model="slabby" name="no_sun" value="1"  class="largerCheckbox">
					</div>
					<div class="col-md-4">
							<label for="vehicle1"> vertical</label><br>
							<input type="checkbox" v-model="vertical" name="afternoon" value="1"  class="largerCheckbox">
					</div>
					<div class="col-md-4">
							<label for="vehicle1"> overhang</label><br>
							<input type="checkbox" v-model="overhang" name="beforenoon" value="1"  class="largerCheckbox">
					</div>
				</div>
			</div>

      <hr>
		</form>
      <div class="container">
        <div class="row">
            <div class="card"  v-if='myModal'>
                <div class="card-header">
                    edit image
                </div>
                <div class="card-body">
                  <div class="row">
                      <div class="col-md-6">
                        <div class="form-group clearfix">
                          <button class="btn btn-primary" @click="add_sector_image()">seve image</button>
                        </div>
                      </div>
                      <div class="col-md-6">    
                        <form @submit="edit_sector_image()"  ref="myForm">
                          <div class="form-group clearfix">
                            <input type="file" name="profile_pic" id="profile_pic" >
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
            </div>
        </div>

        <div class="container">
          <div class="row">
            <div class="form-groupe">
              <button class="btn btn-primary mb-4" @click="showModal()">Add image</button>
            </div>
            <div class="form-groupe">
                <button @click="get_sector_image" class="btn main-btn pull-right" v-if="!image_is_refresh">Refresh ({{image_reset_id}})</button>
                <span class="badge badge-primare mb-1 pull-right" v-if="image_is_refresh">Updating...</span>
            </div>
          </div>
        </div>

        
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-3" v-for="image in images" :key="image.id">
              <div class="row">
                <div class="col-md-12">
                  <img :alt="'sector image - (' + image.image + ')'" class="sector_img" :src="'/public/images/sector_img/' + image.image">
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <button class="btn btn-secondary pull-left" @click="edit_image()">Edit</button>
                </div>
                <div class="col-md-6">
                  <button class="btn btn-danger pull-right" @click="del_sector_image(image.id)">Del</button>
                </div>
              </div>
            </div>
        </div>
    </div>
  </div>
</template>
<script>
  import Uploader from "vux-uploader-component";

  export default {
    components: {
      Uploader,
    },
    props:[
        'editing_sector_id'
    ],
    data() {
      return {
        fileList: [], //https://github.com/eJayYoung/vux-uploader-component
        regions: '',

        myModal: false,

        name: '',
        article_id: '',
        text: '',

        all_day_in_shade: '',
        all_day_in_sun: '',
        in_the_shade_afternoon: '',
        in_the_shade_befornoon: '',
        in_shade_after_10: '',
        in_shade_after_15: '',

        slabby: '',
        vertical: '',
        overhang: '',

        images: '',

        image_is_refresh: false,
        image_reset_id: 0,
      };
    },
    mounted() {
      this.get_region_data()
      this.get_sector_data()
      this.get_sector_image()

      console.log(this.images);
    },
    methods: {
      showModal(){
          this.myModal = !this.myModal
      },
      
      get_region_data: function(){
        axios
        .get("/routes_and_sectors/get_region_data/")
        .then(response => {
          this.regions = response.data
        })
        .catch(
          error => console.log(error)
        );
      },
      get_sector_data: function(){
        axios
        .get("/routes_and_sectors/get_sector_editing_data/" + this.editing_sector_id)
        .then(response => {
            this.editing_data = response.data

            // send data in editing form value
            this.name = this.editing_data.sector['name']
            this.article_id = this.editing_data.sector['article_id']
            this.text = this.editing_data.sector['text']

            this.all_day_in_shade = this.editing_data.sector['all_day_in_shade']
            this.all_day_in_sun = this.editing_data.sector['all_day_in_sun']
            this.in_the_shade_afternoon = this.editing_data.sector['in_the_shade_afternoon']
            this.in_the_shade_befornoon = this.editing_data.sector['in_the_shade_befornoon']
            this.in_shade_after_10 = this.editing_data.sector['in_shade_after_10']
            this.in_shade_after_15 = this.editing_data.sector['in_shade_after_15']

            this.slabby = this.editing_data.sector['slabby']
            this.vertical = this.editing_data.sector['vertical']
            this.overhang = this.editing_data.sector['overhang']
        })
        .catch(
          error => console.log(error)
        );
      },
      get_sector_image: function () {
        this.table_1_is_refresh = true
        axios
        .get("../../routes_and_sectors/get_sector_image/" + this.editing_sector_id)
        .then(response => {
          this.sector_images = response.data
          this.images = this.sector_images.sector_images

          this.image_is_refresh = false
          this.image_reset_id++
        })
        .catch(
          error => console.log(error)
        );
      },

      edit_sector: function () {
        axios
        .post('/routes_and_sectors/sector_edit', {
            article_id: this.article_id,
            name: this.name,
            text: this.text,
            all_day_in_shade: this.all_day_in_shade,
            all_day_in_sun: this.all_day_in_sun,
            in_the_shade_afternoon: this.in_the_shade_afternoon,
            in_the_shade_befornoon: this.in_the_shade_befornoon,
            in_shade_after_10: this.in_shade_after_10,
            in_shade_after_15: this.in_shade_after_15,
            slabby: this.slabby,
            vertical: this.vertical,
            overhang: this.overhang,
        })
        .then(function (response) {
            console.log("sector edited sucsesfule")
        })
        .catch(function (response){
            console.log("sector edited is not sucsesfule!!!")
        })
      },

      add_sector_image: function (e) {
        var myFormData = new FormData(this.$refs.myForm)
        axios({
            method: 'post',
            url: '/routes_and_sectors/upload_sector_image/' + this.editing_sector_id,
            data: myFormData,
            config: { 
                headers: {'Content-Type': 'multipart/form-data' },
            },
        })
        .then((response)=>  {
            // this.is_image_succes = 1;
            // alert(response.data.message);
        });
        this.showModal()
        e.preventDefault();

      },
      edit_sector_image: function (e) {
        var myFormData = new FormData(this.$refs.myForm)
        axios({
            method: 'post',
            url: '/routes_and_sectors/update_sector_image/' + this.editing_sector_id,
            data: myFormData,
            config: { 
                headers: {'Content-Type': 'multipart/form-data' },
            },
        })
        .then((response)=>  {
            // this.is_image_succes = 1;
            // alert(response.data.message);
        });
        this.showModal()
        e.preventDefault();

      },
      del_sector_image(imageId) {
          axios
          .post("../../routes_and_sectors/delete_sector_image/" + imageId, {
              id: imageId,
          })
          .then(Response => {
              console.log(response)
              this.get_data_in_table_1()
          })
          .catch(error => console.log(error))
      },

      save_all: function () {
        this.edit_sector()
      }
    }
  };
</script>

<style scoped>
.sector_img{
  border-radius: 5%;
  border: 3px solid #a7a7a7;
}
</style>