<template>
    <div class="form-group clearfix row">
        <label for="published" class='col-md-2 control-label'> Publish </label>
        <div class="col-md-10">
            <select class="form-control" name="published" v-model="data"> 
                <option value="0">Not public</option> 
                <option value="1">Public</option> 
                <option value="2">Public url</option> 
            </select> 
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            // tabsComponent ,
        },
    
        data() {
            return {
                data: 0,
            }
        },

        mounted() {
            this.$emit('item_data', this.data)
        },
         
        methods: {

        }
    }
</script>