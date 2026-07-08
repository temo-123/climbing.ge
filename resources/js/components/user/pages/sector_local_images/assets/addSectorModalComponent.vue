<template>
    <!-- <div> -->
<StackModal
            :show="add_sector_modal"
            :title="$t('admin.articles.add_sector_title')"
            @close="close_add_sector_modal"
            @save="add_sector_from_modal"
            :saveButton="{ visible: true, title: $t('admin.articles.add_sector_title') }"
            :cancelButton="{ visible: true, title: $t('common.close') }"
        >
        <div>
            <select class="form-control" v-model="selected_outdoor_area" name="comment delete cause" @click="get_region_sectors()">
                <option value="Select outdoor area" disabled>{{ $t('admin.articles.select_outdoor_area_placeholder') }}</option>
                <option value="All sectors">{{ $t('admin.articles.show_all_sectors_option') }}</option>
                <option v-for="outdoor_area in outdoor_areas" :key="outdoor_area.id" :value="outdoor_area.id">{{ outdoor_area.url_title }}</option>
            </select>

            <select class="form-control" v-model="selected_sector" name="comment delete cause" v-if="!sectors_loading && sectors.length != 0">
                <option value="Select sector" disabled>{{ $t('admin.articles.select_sector_placeholder') }}</option>
                <option v-for="sector in filtred_sectors" :key="sector.id" :value="{'id': sector.id, 'name': sector.name}">{{ sector.name }}</option>
            </select>

            <img :src="'/images/site_img/loading.gif'" alt="loading" v-if="sectors_loading">
        </div>
    </StackModal>
    <!-- </div> -->
</template>

<script>
// import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort - removed as not used

export default {
    components: {
        // StackModal registered globally in app.js
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
