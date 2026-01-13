 
<template>
    <form class="width_100">
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> email </label>
            <div class="col-xs-8">
                <input type="email" name="email" v-model="global_editing_data.email" class="form-control"> 
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> Phone </label>
            <div class="col-xs-8">
                <input type="text" name="number" v-model="global_editing_data.number" class="form-control"> 
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> map </label>
            <div class="col-xs-8">
                <input type="text" name="map" v-model="global_editing_data.map" class="form-control"> 
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> AD block</label>
            <div class="col-xs-8">
                <input type="text" name="ad" v-model="global_editing_data.ad" class="form-control"> 
            </div>
        </div>

    </form>
</template>
    
<script>
    export default {
        props: [
            'global_data_prop',
        ],

        data(){
            return {
                global_editing_data: {
                    email: '',
                    number: '',
                    map: ''
                },
                data: []
            }
        },

        watch: {
            global_data_prop: function(newVal, oldVal) { 
                // console.log('Prop changed: ', newVal, ' | was: ', oldVal)
                this.global_editing_data = this.global_data_prop
            },
        },
    
        mounted() {
            // console.log(this.global_data_prop);
            // this.data = JSON.parse(this.global_data_prop)
        },
    }
</script>
    