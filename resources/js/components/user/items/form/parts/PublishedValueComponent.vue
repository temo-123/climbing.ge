<template>
    <div class="form-group clearfix row">
        <label for="published" class='col-md-2 control-label'> Publish </label>
        <div class="col-md-10">
            <select class="form-control" name="published" v-model="data" @change="change()"> 
                <option value="0">Not public</option> 
                <option value="1">Public</option> 
                <option value="2">Public url</option> 
            </select> 
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'published_prop'
        ],

        components: {
            // tabsComponent ,
        },
        watch: {
            published_prop: function(newVal, oldVal) { 
                // console.log('Prop changed: ', newVal, ' | was: ', oldVal)
                this.data = this.published_prop
            },
        },
    
        data() {
            return {
                data: 0,
            }
        },

        mounted() {
            // this.$emit('item_data', this.data)
        },
         
        methods: {
            change(){
                this.$emit('item_data', this.data)
            }
        }
    }
</script>