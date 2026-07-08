<template>
    <div class="form-group clearfix row">
        <label for="published" class='col-md-2 control-label'> {{ $t('admin.common.publish') }} </label>
        <div class="col-md-10">
            <select class="form-control" name="published" v-model="data" @change="change()">
                <option value="0">{{ $t('admin.common.not_public') }}</option>
                <option value="1">{{ $t('admin.common.public') }}</option>
                <option value="2">{{ $t('admin.common.public_url') }}</option>
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