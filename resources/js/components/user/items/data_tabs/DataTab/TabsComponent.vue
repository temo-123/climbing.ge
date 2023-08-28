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
                                    @filtred="filtring"
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
                        >
                            <div class="col-md-12">
                                <div class="add_buttom float-left">
                                    <span
                                        v-if="
                                            filtr_data.table_name == 'Shiped countries' &&
                                            filtr_data.table_category !== null
                                        "
                                    >
                                        <button class="btn btn-primary pull-left" @click="country_add_model" v-if="$can('add', 'shiping_country')">New Shiped countries</button>
                                    </span>
                                    <span
                                        v-else-if="
                                            filtr_data.table_name == 'Films tags' &&
                                            filtr_data.table_category !== null
                                        "
                                    >
                                        <button class="btn btn-primary pull-left" @click="tag_modal('add')" v-if="$can('add', 'film_teg')">New tag</button>
                                    </span>
                                    <span
                                        v-else-if="
                                            filtr_data.table_name == 'Sale codes' &&
                                            filtr_data.table_category !== null
                                        "
                                    >
                                        <button class="btn btn-primary pull-left" @click="sale_code_modal('add')" v-if="$can('add', 'sale_code')">New sale code</button>
                                    </span>
                                    <span
                                        v-else-if="
                                            filtr_data.table_name == 'Roles'
                                        "
                                    >
                                        <button class="btn btn-primary pull-left" @click="add_role()" v-if="$can('add', 'role')">Add new role</button>
                                    </span>
                                    <span
                                        v-else-if="
                                            filtr_data.table_name == 'Users'
                                        "
                                    >
                                        <button class="btn btn-primary pull-left" @click="add_user()" v-if="$can('add', 'user')">Add new user</button>
                                    </span>
                                    <span
                                        v-else-if="
                                            filtr_data.table_name == 'Products' &&
                                            filtr_data.table_category !== null
                                        "
                                    >
                                        <router-link
                                            class="btn btn-primary pull-left"
                                            :to="{
                                                name: filtr_data.table_add_url,
                                            }"
                                            >New
                                        </router-link>
                                    </span>
                                    <span v-else>
                                        <span v-if="filtr_data.table_add_url">
                                            <router-link
                                                class="btn btn-primary pull-left" 
                                                v-if="$can('add', 'article')"
                                                :to="{
                                                    name: filtr_data.table_add_url,
                                                    params: {
                                                        article_category:
                                                            filtr_data.table_category,
                                                    },
                                                }"
                                                >New
                                            </router-link>
                                        </span>
                                    </span>
                                </div>

                                <div class="form-groupe float-right">
                                    <button
                                        class="btn btn-success"
                                        @click="update()"
                                    >
                                        Refresh
                                    </button>
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
                            <thead  v-else-if="data.table_name == 'Films tags' || data.table_name == 'Film categories'">
                                <filmTagsTabHeader 
                                    :table_name="data.table_name"
                                />
                            </thead>
                            <thead  v-else-if="data.table_name == 'Products'">
                                <productTagsTabHeader 
                                    :table_name="data.table_name"
                                />
                            </thead>
                            <thead  v-else-if="data.table_name == 'Sectors local images'">
                                <sectorLocalImageTabHeader 
                                    :table_name="data.table_name"
                                />
                            </thead>
                            <thead  v-else-if="data.table_name == 'Mount vasives'">
                                <mountMassiveTabHeader 
                                    :table_name="data.table_name"
                                />
                            </thead>
                            <thead  v-else-if="data.table_name == 'Events'">
                                <eventTabHeader 
                                    :table_name="data.table_name"
                                />
                            </thead>
                            <thead v-else-if="data.table_name == 'Regions'">
                                <regionTabHeader 
                                    :table_name="data.table_name"
                                />
                            </thead>
                            <thead v-else-if="data.table_name == 'Site Followers'">
                                <SiteFollowersTabHeader 
                                    :table_name="data.table_name"
                                />
                            </thead>
                            <thead v-else-if="data.table_name == 'Routes' || data.table_name == 'Ice routes'">
                                <routeTabHeader 
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
                                        data.table_name == 'Mountaineering routes' || 
                                        data.table_name == 'services' 
                                    ">
                                <articleTabHeader
                                    :table_name="data.table_name"
                                />
                            </thead>
                            <thead v-else>
                                <tabHeader :data="data"/>
                            </thead>



                            <tbody v-if="data.table_name == 'Products'">
                                <productsTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"

                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Mount vasives'">
                                <mountMassiveTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Events'">
                                <eventTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Sectors local images'">
                                <sectorLocalImageTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Local bisnes'">
                                <localBisnesTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Comments' || data.table_name == 'My comments'">
                                <commentsTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"

                                    :comments_tab_name="data.table_name"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Regions'">
                                <regionsTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Categories'">
                                <categoryTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Sectors'">
                                <sectorTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    @show_sector_modal="sector_modal"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'services'">
                                <serviceTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Shiped countries'">
                                <countryTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Films tags'">
                                <filmTagsTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"

                                    ref="control_tag"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name =='Sale codes'">
                                <saleCodesTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"

                                    ref="control_sale_code"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name === 'Routes'">
                                <routeTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"

                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Multi-pitchs'">
                                <mtpTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Pitches'">
                                <pitchsTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Users'">
                                <userTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Roles'">
                                <rolesTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Parmissions'">
                                <parmissionsTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Orders' || data.table_name == 'My orders' ">
                                <orderTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Posts'">
                                <postTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Post topics'">
                                <postTopicTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Competitions'">
                                <eventTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Ice sectors'">
                                <iceSectorTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Ice routes'">
                                <iceRouteTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Site Followers'">
                                <siteFollowersTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    
                                    @restart="update"
                                />
                            </tbody>
                            <tbody v-else>
                                <articlesTab
                                    v-for="table_info in data.data"
                                    :key="table_info.id"
                                    :table_info="table_info"
                                    
                                    @restart="update"
                                />
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>

        <mtpModel v-if="table_data[tab_num - 1].table_name == 'Multi-pitchs'" />

        <addRoleModal ref="add_role_modal"/>

        <addUserModal ref="add_user_modal" @restart="update"/>

        <countryAddModal ref="add_country" @restart="update"/>

        <saleCodeModal ref="add_sale_code" @restart="update"/>

        <orderDetalModal
            v-if="table_data[tab_num - 1].table_name == 'Orders'"
        />
        <editOrderStatusModal
            v-if="table_data[tab_num - 1].table_name == 'Orders'"
        />

        <articleQuickViewModal
            v-if="table_data[tab_num - 1].table_name == 'Orders'"
        />
    </div>
</template>

<script>
// import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
// import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

import functionalBattoms from "./tabs_components/TabFunctionalBottomsComponent.vue";
import tabHeader from "./tabs_components/TabHeaderComponent.vue";

import tableFilter from "../../filters/FiltersComponent.vue";

import orderTabHeader from "./tab_header/OrderTabHeaderComponent.vue"
import articleTabHeader from "./tab_header/ArticleTabHeaderComponent.vue"
import filmTagsTabHeader from "./tab_header/FilmTagsTabHeaderComponenr.vue"
import mountMassiveTabHeader from "./tab_header/MountMassiveTabHeaderComponent.vue"
import productTagsTabHeader from "./tab_header/ProductTabHeaderComponent.vue"
import sectorLocalImageTabHeader from "./tab_header/SectorLocalImageTabHeaderComponent.vue"
import eventTabHeader from "./tab_header/EventTabHeaderComponent.vue"
import regionTabHeader from "./tab_header/RegionTabHeaderComponrent.vue"
import routeTabHeader from "./tab_header/RouteTabHeaderComponrent.vue"
import SiteFollowersTabHeader from "./tab_header/SiteFollowersTabHeaderComponent.vue"

import eventTab from "./tabs/EventTabComponent.vue";
import sectorLocalImageTab from "./tabs/SectorLocalImageTabComponent.vue";
import routeTab from "./tabs/RouteTabComponent.vue";
import sectorTab from "./tabs/SectorTabComponent.vue";
import articlesTab from "./tabs/ArticlesTabComponent.vue";
import serviceTab from "./tabs/ServiceTabComponent.vue";
import localBisnesTab from "./tabs/LocalBisnesTabComponent.vue";
import mountMassiveTab from "./tabs/MountMassiveTabComponent.vue";
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
import countryTab from './tabs/CountryTabComponent.vue'
import categoryTab from "./tabs/CategoriesTabComponent.vue";
import postTopicTab from "./tabs/PostTopicTabComponent.vue";
import postTab from "./tabs/PostTabComponent.vue";
import iceSectorTab from "./tabs/IceSectorTabComponent.vue";
import iceRouteTab from "./tabs/IceRouteTabComponent.vue";
import siteFollowersTab from "./tabs/SiteFollowersTabComponent.vue";
// import competitionTab from "./tabs/CompetitionTabComponent.vue";

//import sectorModal from "./tab_modals/SectorsModalComponent.vue";
// import editOrderStatusModal from "./tab_modals/EditOrderStatusსModalComponent.vue";
import addRoleModal from "./tab_modals/RolesAddModalComponent.vue";
import countryAddModal from "./tab_modals/AddCountryModalComponent.vue";
import addUserModal from "./tab_modals/UserAddModalComponent.vue";
import articleQuickViewModal from "./tab_modals/ArticleQuickViewModalComponen.vue";
import saleCodeModal from "./tab_modals/AddSaleCodeModalComponen.vue";

export default {
    components: {
        tabHeader,
        functionalBattoms,

        // competitionTab,

        tableFilter,

        orderTabHeader,
        articleTabHeader,
        filmTagsTabHeader,
        productTagsTabHeader,
        mountMassiveTabHeader,
        sectorLocalImageTabHeader,
        eventTabHeader,
        regionTabHeader,
        routeTabHeader,
        routeTabHeader,
        SiteFollowersTabHeader,

        sectorLocalImageTab,
        routeTab,
        sectorTab,
        localBisnesTab,
        articlesTab,
        serviceTab,
        mountMassiveTab,
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
        countryTab,
        filmTagsTab,
        saleCodesTab,
        eventTab, 
        iceSectorTab,
        iceRouteTab,
        siteFollowersTab,

        saleCodeModal,
        countryAddModal,
        addRoleModal,
        addUserModal,
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
            this.$refs.add_country.add_country_model_open()
        },
        tag_modal(action){
            this.$refs.control_tag[0].tag_control_modal(action)
        },
        sale_code_modal(action){
            this.$refs.add_sale_code.add_sale_code_model_open()
        },

        add_role(){
            this.$refs.add_role_modal.open_role_add_modal()
        },
        add_user(){
            this.$refs.add_user_modal.open_user_add_modal()
        },

        filtring(event) {
            this.$emit("filtr", event);
        },
        update() {
            this.$emit("update-data");
        },
    },
};
</script>

<style scoped>
/* .tabs {
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
} */
</style>
