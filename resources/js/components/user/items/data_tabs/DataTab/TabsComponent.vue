<template>
    <div class="tabs">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div
                        class="col"
                        v-for="but_data in table_data"
                        :key="but_data.id"
                    >
                        <input
                            type="radio"
                            :id="but_data.id"
                            :value="but_data.id"
                            v-model="tab_num"
                        />

                        <label :for="but_data.id">{{
                            but_data.table_name
                        }}</label>
                    </div>
                </div>

                <div class="row">
                    <div
                        class="col-md-12"
                        v-for="filtr_data in table_data"
                        :key="filtr_data.id"
                        v-if="tab_num == filtr_data.id"
                    >
                        <div class="row">
                            <div class="col-md-12">
                                <tableFilter
                                    :table_name="filtr_data.table_name"
                                    @filtred="filtr"
                                />
                            </div>
                        </div>

                        <div
                            class="row"
                            v-if="
                                filtr_data.table_name != 'Regions' &&
                                filtr_data.table_name != 'Sectors' &&
                                filtr_data.table_name != 'Routes' &&
                                filtr_data.table_name != 'Multi-pitchs' &&
                                filtr_data.table_name != 'Pitches' &&
                                filtr_data.table_name != 'Images category' &&
                                filtr_data.table_name != 'Orders' &&
                                filtr_data.table_name != 'comments' &&
                                filtr_data.table_name != 'Posts' &&
                                filtr_data.table_name != 'Topics' &&
                                filtr_data.table_name != 'Users' &&
                                filtr_data.table_name != 'Folowing users' &&
                                filtr_data.table_name != 'my comments' &&
                                filtr_data.table_name != 'My Posts' &&
                                filtr_data.table_name != 'Roles' &&
                                filtr_data.table_name != 'Categories' &&
                                filtr_data.table_name != 'Parmissions' &&
                                filtr_data.table_name == 'My comments' &&
                                filtr_data.table_name == 'Comments' &&
                                filtr_data.table_name != 'Mounts'
                            "
                        >
                            <div class="col-md-12">
                                <button class="btn btn-primary pull-left">
                                    Go to {{ filtr_data.table_name }} page
                                </button>
                            </div>
                        </div>

                        <div class="row"><hr /></div>

                        <div
                            class="row"
                            v-if="filtr_data.table_name != 'Parmissions'"
                        >
                            <div class="col-md-12">
                                <div class="add_buttom float-left">
                                    <!-- <a :href="table_edit_url" class="btn btn-primary pull-left" type="submit">New</a> -->
                                    <!-- <a class="btn btn-primary" type="submit">New {{filtr_table_name}}</a> -->
                                    <!-- :href="table_1_add_url"  -->
                                    <!-- <router-link class="btn btn-primary pull-left" :to="'../article/add/'+filtr_table_name" exact>New {{filtr_table_name}}</router-link> -->

                                    <span
                                        v-if="
                                            filtr_data.table_name == 'My comments' &&
                                            filtr_data.table_name == 'Comments' &&
                                            filtr_data.table_category != null
                                        "
                                    >
                                        <router-link
                                            class="btn btn-primary pull-left"
                                            :to="{
                                                name: filtr_data.table_add_url,
                                                params: {
                                                    article_category:
                                                        filtr_data.table_category,
                                                },
                                            }"
                                            >New
                                            {{ filtr_data.name }}</router-link
                                        >
                                    </span>
                                    <span
                                        v-else-if="
                                            filtr_data.table_name == 'Shiped countries' &&
                                            filtr_data.table_category !== null
                                        "
                                    >
                                        <button class="btn btn-primary pull-left" @click="country_add_model">New Shiped countries</button>
                                    </span>
                                    <span
                                        v-else-if="
                                            filtr_data.table_name == 'Films tags' &&
                                            filtr_data.table_category !== null
                                        "
                                    >
                                        <button class="btn btn-primary pull-left" @click="tag_modal('add')">New tag</button>
                                    </span>
                                    <span
                                        v-else-if="
                                            filtr_data.table_name == 'Sale codes' &&
                                            filtr_data.table_category !== null
                                        "
                                    >
                                        <button class="btn btn-primary pull-left" @click="sale_code_modal('add')">New sale code</button>
                                    </span>
                                    <!-- <span
                                        v-else-if="
                                            filtr_data.table_name == 'Sectors local images' &&
                                            filtr_data.table_category !== null
                                        "
                                    >
                                        <button class="btn btn-primary pull-left">New local image</button>
                                    </span> -->
                                    <span v-else>
                                        <span v-if="filtr_data.table_add_url">
                                            <router-link
                                                class="btn btn-primary pull-left"
                                                :to="{
                                                    name: filtr_data.table_add_url,
                                                }"
                                                >New
                                                {{ filtr_data.name }}
                                            </router-link
                                            >
                                        </span>
                                    </span>
                                </div>

                                <div class="form-groupe float-right">
                                    <button
                                        class="btn btn-success"
                                        @click="update(filtr_data.id)"
                                    >
                                        refresh
                                    </button>

                                    <!-- <button class="btn main-btn" >Refresh ({{2}})</button>
                                    <span class="badge badge-primare mb-1">Updating...</span> -->
                                </div>
                            </div>
                        </div>
                        <div
                            class="row"
                            v-if="filtr_data.table_name != 'Parmissions'"
                        >
                            <div class="col-md-12">
                                <div class="form-groupe float-right">
                                    <button class="btn btn-danger" disabled>
                                        Del selected items
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table
                            class="table table-hover"
                            id="dev-table"
                            v-for="data in table_data"
                            :key="data.id"

                            v-if="tab_num == data.id"
                        >

                        
                            <thead v-if="data.table_name == 'Orders' || data.table_name == 'My orders' ">
                                <orderTabHeader 
                                    :table_name="data.table_name"
                                />
                            </thead>
                            <thead v-if="data.table_name == 'Films tags' || data.table_name == 'Film categories'">
                                <filmTagsTabHeader 
                                    :table_name="data.table_name"
                                />
                            </thead>
                            <thead v-if="data.table_name == 'Products'">
                                <productTagsTabHeader 
                                    :table_name="data.table_name"
                                />
                            </thead>
                            <thead v-if="data.table_name == 'Sectors local images'">
                                <sectorLocalImageTabHeader 
                                    :table_name="data.table_name"
                                />
                            </thead>
                            <thead v-else-if="
                                        data.table_name == 'outdoor' || 
                                        data.table_name == 'indoor'  || 
                                        data.table_name == 'ice'  || 
                                        data.table_name == 'news'  || 
                                        data.table_name == 'other'  || 
                                        data.table_name == 'tech_tip'  || 
                                        data.table_name == 'partners'  || 
                                        data.table_name == 'events' || 
                                        data.table_name == 'Films' || 
                                        data.table_name == 'services' 
                                    ">
                                <articleTabHeader
                                    :table_name="data.table_name"
                                />
                            </thead>
                            <thead v-else>
                                <tabHeader :data="data"/>
                            </thead>



                            <tbody v-if="data.table_name == 'Mounts'">
                                <mountTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Products'">
                                <productsTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Sectors local images'">
                                <sectorLocalImageTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Comments' || data.table_name == 'My comments'">
                                <commentsTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"

                                    :comments_tab_name="data.table_name"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Regions'">
                                <regionsTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Categories'">
                                <categoryTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Sectors'">
                                <sectorTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    @show_sector_modal="sector_modal"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Shiped countries'">
                                <qountryTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"

                                    ref="add_country"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Films tags'">
                                <filmTagsTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"

                                    ref="control_tag"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name =='Sale codes'">
                                <saleCodesTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"

                                    ref="control_sale_code"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name === 'Routes'">
                                <routeTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Multi-pitchs'">
                                <mtpTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Pitches'">
                                <pitchTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Users'">
                                <userTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Roles'">
                                <rolesTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Parmissions'">
                                <parmissionsTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Orders' || data.table_name == 'My orders' ">
                                <orderTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Posts'">
                                <postTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Post topics'">
                                <postTopicTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                />
                            </tbody>
                            <tbody v-else>
                                <articlesTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                />
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>

        <sectorModal
            :show_sector_modal="show_sector_modal"
            v-if="table_data[tab_num - 1].table_name == 'Sectors'"
        />
        <mtpModel v-if="table_data[tab_num - 1].table_name == 'Multi-pitchs'" />

        <roleModal v-if="table_data[tab_num - 1].table_name == 'Roles'" />

        <!-- <orderDetalModal
            v-if="table_data[tab_num - 1].table_name == 'Orders'"
        />
        <editOrderStatusModal
            v-if="table_data[tab_num - 1].table_name == 'Orders'"
        /> -->

        <articleQuickViewModal
            v-if="table_data[tab_num - 1].table_name == 'Orders'"
        />
    </div>
</template>

<script>
// import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
// import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

// import editor from '../../../items/canvas/EditorComponent.vue'
import functionalBattoms from "./tabs_components/TabFunctionalBottomsComponent.vue";
import tabHeader from "./tabs_components/TabHeaderComponent.vue";

import tableFilter from "../../filters/FiltersComponent.vue";

import orderTabHeader from "./tab_header/OrderTabHeaderComponent.vue"
import articleTabHeader from "./tab_header/ArticleTabHeaderComponent.vue"
import filmTagsTabHeader from "./tab_header/FilmTagsTabHeaderComponenr.vue"
import productTagsTabHeader from "./tab_header/ProductTabHeaderComponent.vue"
import sectorLocalImageTabHeader from "./tab_header/SectorLocalImageTabHeaderComponent.vue"

import sectorLocalImageTab from "./tabs/SectorLocalImageTabComponent.vue";
import routeTab from "./tabs/RouteTabComponent.vue";
import sectorTab from "./tabs/SectorTabComponent.vue";
import articlesTab from "./tabs/ArticlesTabComponent.vue";
import categoriesTab from "./tabs/CategoriesTabComponent.vue";
import mountTab from "./tabs/MountTabComponent.vue";
import mtpTab from "./tabs/MTPTabComponent.vue";
import pitchsTab from "./tabs/PitchsTabComponents.vue";
import rolesTab from "./tabs/RolesTabComponent.vue";
import parmissionsTab from "./tabs/ParmissionsTabComponent.vue";
import regionsTab from "./tabs/RegionsTabComponent.vue";
import productsTab from "./tabs/ProductsTabComponent.vue";
import userTab from "./tabs/UsersTabComponent.vue";
import commentsTab from './tabs/CommentsTabComponent.vue'
import filmTagsTab from './tabs/FilmTagsTabComponent.vue'
import saleCodesTab from './tabs/SaleCodesTabComponent.vue'
import orderTab from './tabs/OrderTabComponent.vue'
import qountryTab from './tabs/QountryTabComponent.vue'
import categoryTab from "./tabs/CategoriesTabComponent.vue";
import postTopicTab from "./tabs/PostTopicTabComponent.vue";
import postTab from "./tabs/PostTabComponent.vue";

import sectorModal from "./tab_modals/SectorsModalComponent.vue";
// import editOrderStatusModal from "./tab_modals/EditOrderStatusსModalComponent.vue";
import mtpModel from "./tab_modals/MTPModalComponent.vue";
// import orderDetalModal from "./tab_modals/OrderDetalsModalComponent.vue";
import roleModal from "./tab_modals/RolesModalComponent.vue";
import articleQuickViewModal from "./tab_modals/ArticleQuickViewModalComponen.vue";

export default {
    components: {
        tabHeader,
        functionalBattoms,

        tableFilter,

        orderTabHeader,
        articleTabHeader,
        filmTagsTabHeader,
        productTagsTabHeader,
        sectorLocalImageTabHeader,

        sectorLocalImageTab,
        routeTab,
        sectorTab,
        articlesTab,
        categoriesTab,
        mountTab,
        mtpTab,
        pitchsTab,
        rolesTab,
        parmissionsTab,
        productsTab,
        userTab,
        regionsTab,
        categoryTab,
        postTopicTab,
        postTab,
        commentsTab,
        orderTab,
        qountryTab,
        filmTagsTab,
        saleCodesTab,

        sectorModal,
        // editOrderStatusModal,
        mtpModel,
        // orderDetalModal,
        roleModal,
        articleQuickViewModal,
    },

    props: [
        "table_data"
    ],

    data() {
        return {
            tab_num: 1,
            show_sector_modal: false,
        };
    },

    mounted() {
        this.tab_num = 1;
    },

    methods: {
        country_add_model(){
            this.$refs.add_country[0].add_country_model_open()
        },
        tag_modal(action){
            this.$refs.control_tag[0].tag_control_modal(action)
        },
        sale_code_modal(action){
            this.$refs.control_sale_code[0].sale_code_control_modal(action)
        },

        filtr(event) {
            this.$emit("filtr", event);
        },
        update(id) {
            this.$emit("update-data", [id]);
        },

        // sector_modal(event) {
        //     console.log(event);
            // this.show_sector_modal = event
        // },
    },
};
</script>

<style scoped>
.tabs {
    /* display: flex; */
    flex-wrap: wrap;
}
.tabs label {
    order: 1;
    display: block;
    padding: 1rem 2rem;
    margin-right: 0.2rem;
    cursor: pointer;
    background: #ccced0;
    font-weight: bold;
    transition: background ease 0.2s;
}
.tabs .tab {
    order: 99;
    flex-grow: 1;
    width: 100%;
    display: none;
    padding: 1rem;
    background: #fff;
    /* border: 1px solid #ccc!important; */
}
.tabs input[type="radio"] {
    display: none;
}
.tabs input[type="radio"]:checked + label {
    background: #fff;
    border: 1px solid #ccc !important;
}
.tabs input[type="radio"]:checked + label + .tab {
    display: block;
}

@media (max-width: 45em) {
    .tabs .tab,
    .tabs label {
        order: initial;
    }
    .tabs label {
        width: 100%;
        margin-right: 0;
        margin-top: 0.2rem;
    }
}
</style>
