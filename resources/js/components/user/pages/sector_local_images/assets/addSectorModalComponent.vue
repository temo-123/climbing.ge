<template>
    <!-- <div> -->
    <stack-modal
            :show="add_sector_modal"
            :title="'Add sector'" 
            @close="close_add_sector_modal"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <form v-on:submit.prevent="add_sector_from_modal" id="add_new_sector" class="form">
                <!-- <select class="form-control" v-model="selected_outdoor_area" name="comment delete cause" v-on:input="get_region_sectors()">  -->
                <select class="form-control" v-model="selected_outdoor_area" name="comment delete cause" @click="get_region_sectors()"> 
                    <option value="Select outdoor area" disabled>Select outdoor area</option>
                    <option value="All sectors">Show all sectors</option>
                    <option v-for="outdoor_area in outdoor_areas" :key="outdoor_area.id" :value="outdoor_area.id" >{{ outdoor_area.url_title }}</option>
                </select>

                <select class="form-control" v-model="selected_sector" name="comment delete cause" v-if="!sectors_loading && sectors.length != 0"> 
                    <option value="Select sector" disabled>Select sector</option>
                    <option v-for="sector in filtred_sectors" :key="sector.id" :value="{'id': sector.id, 'name': sector.name}">{{ sector.name }}</option>
                </select>

                <img :src="'../../../public/images/site_img/loading.gif'" alt="loading" v-if="sectors_loading">
            </form>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button type="submit" form="add_new_sector" class="btn btn-primary">Add new sector</button>
            </div>
        </div>
    </stack-modal>
    <!-- </div> -->
</template>

<script>
import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

export default {
    components: {
        StackModal,
        SlickItem,
        SlickList,
    },
    props: [
        // 'add_sector_modal_prop',
    ],
    data(){
        return {
            sectors: [],
            filtred_sectors: [],

            outdoor_areas: [],

            sectors_loading: false,
            add_sector_modal: false,
            selected_sector: 'Select sector',
            selected_outdoor_area: 'Select outdoor area',

            image_sectors: [],

            data: {
                title: '',
                image: ''
            },
        }
    },

    mounted() {
        this.get_all_outdoor_areas()
        this.get_all_sectors()
    },

    methods: {
        get_all_outdoor_areas(){
            this.sectors_loading = true
            axios
            .get("/get_article/get_category_articles/outdoor")
            .then(response => {
                this.outdoor_areas = response.data
            })
            .catch(
                error => console.log(error)
            )
            .finally(() => this.sectors_loading = false);
        },
        get_all_sectors(){
            axios
            .get("/get_sector/get_all_sectors")
            .then(response => {
                this.sectors = response.data
            })
            .catch(
                error => console.log(error)
            );
        },
        get_region_sectors(){
            if(this.selected_outdoor_area != 'Select outdoor area' && this.selected_outdoor_area != 'All sectors'){
                const vm = this
                this.filtred_sectors = this.sectors.filter(function (item){
                    return item.article_id == vm.selected_outdoor_area
                })
            }
            else if (this.selected_outdoor_area != 'Select outdoor area' && this.selected_outdoor_area == 'All sectors'){
                this.filtred_sectors = this.sectors
            }
            else{
                this.filtred_sectors = this.sectors
            }
        },

        close_add_sector_modal(){
            this.selected_sector = 'Select sector'
            this.add_sector_modal = false
        },
        add_new_sector_model(){
            this.add_sector_modal = true
        },
        add_sector_from_modal(event){
            this.$emit('sector', this.selected_sector)

            this.close_add_sector_modal()
        }
    }
}
</script>