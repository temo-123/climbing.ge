<template>
  <div class="col-md-12">
    <div class="row">
      <div class="form-group">
        <button type="submit" class="btn btn-primary" v-on:click="save_all()">Save</button>
      </div>
    </div>
  	<form action="#" id="js_form" class="contact-form" method="POST" enctype="multipart/form-data">
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

      <uploader
        v-model="fileList"
        :url="'/routes_and_sectors/upload_sector_image'"
        @on-change="onChange"
        @on-cancel="onCancel"
        @on-success="onSuccess"
        @on-error="onError"
        @on-delete="onDelete"
      ></uploader>

		</form>
  </div>
</template>
<script>
  import Uploader from "vux-uploader-component";

  export default {
    components: {
      Uploader
    },
    data() {
      return {
        fileList: [], //https://github.com/eJayYoung/vux-uploader-component
        regions: '',

        article_id: '',
        name: '',
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
      };
    },
    mounted() {
      this.get_region_data()
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
        .post('/routes_and_sectors/sector_add', {
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
            console.log("sector added sucsesfule")
        })
        .catch(function (response){
            console.log("sector added is not sucsesfule!!!")
        })
      },
      save_all: function () {
        this.add_sector()
      }
    }
  };
</script>