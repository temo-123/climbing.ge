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
                                            filtr_data.table_category !== null
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
                                    <span v-else>
                                        <span v-if="filtr_data.table_add_url">
                                            <router-link
                                                class="btn btn-primary pull-left"
                                                :to="{
                                                    name: filtr_data.table_add_url,
                                                }"
                                                >New
                                                {{
                                                    filtr_data.name
                                                }}</router-link
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
                            <thead>
                                <tr>
                                    <th style="text-align: center">
                                        <input type="checkbox" class="all" />
                                    </th>
                                    <th>|</th>
                                    <th>ID</th>
                                    <th>|</th>
                                    <th v-if="data.table_name != 'Orders'">
                                        Name
                                    </th>

                                    <th v-if="data.table_name == 'Orders'">
                                        Status
                                    </th>

                                    <!-- <th v-if="data.table_name != 'Products'">|</th> -->

                                    <!-- <th style='text-align: center;' v-if="data.table_name == 'Sectors'">|</th>
                                    <th style='text-align: center;' v-if="data.table_name == 'Sectors'">Region</th> -->

                                    <!-- <th
                                        style="text-align: center"
                                        v-if="data.table_name == 'Products'"
                                    >
                                        |
                                    </th>
                                    <th
                                        style="text-align: center"
                                        v-if="data.table_name == 'Products'"
                                    >
                                        Price
                                    </th>

                                    <td v-if="data.table_name == 'Products' && data.table_name != 'Orders'">|</td> 

                                    <th
                                        style="text-align: center"
                                        v-if="data.table_name == 'Products'"
                                    >
                                        |
                                    </th>
                                    <th
                                        style="text-align: center"
                                        v-if="data.table_name == 'Products'"
                                    >
                                        Quantity
                                    </th>
                                    <th
                                        style="text-align: center"
                                        v-if="data.table_name == 'Products'"
                                    >
                                        |
                                    </th> -->

                                    <td
                                        v-if="
                                            data.table_name != 'Routes' &&
                                            data.table_name != 'Multi-pitchs' &&
                                            data.table_name != 'Regions' &&
                                            data.table_name != 'Pitches' &&
                                            data.table_name != 'Users' &&
                                            data.table_name != 'Categories' &&
                                            data.table_name != 'Orders' &&
                                            data.table_name != 'Parmissions'
                                        "
                                    >
                                        |
                                    </td>

                                    <th
                                        style="text-align: center"
                                        v-if="
                                            data.table_name != 'Routes' &&
                                            data.table_name != 'Multi-pitchs' &&
                                            data.table_name != 'Regions' &&
                                            data.table_name != 'Pitches' &&
                                            data.table_name != 'Users' &&
                                            data.table_name != 'Categories' &&
                                            data.table_name != 'Orders' &&
                                            data.table_name != 'Roles' &&
                                            data.table_name != 'Parmissions'
                                        "
                                    >
                                        Public
                                    </th>

                                    <td v-if="data.table_name == 'Roles'">
                                        Description
                                    </td>
                                    <!-- <td v-if="data.table_name == 'Users'">|</td>
                                    <th style='text-align: center;' v-if="data.table_name == 'Users'">email</th> -->

                                    <td v-if="data.table_name == 'Users'">|</td>
                                    <th
                                        style="text-align: center"
                                        v-if="data.table_name == 'Users'"
                                    >
                                        Role
                                    </th>

                                    <!-- <td v-if="data.table_name == 'Products'">
                                        |
                                    </td>
                                    <th
                                        style="text-align: center"
                                        v-if="data.table_name == 'Products'"
                                    >
                                        Colors
                                    </th> -->

                                    <th
                                        v-if="
                                            data.table_name == 'Routes' ||
                                            data.table_name == 'Pitches'
                                        "
                                    >
                                        |
                                    </th>
                                    <th
                                        style="text-align: center"
                                        v-if="
                                            data.table_name == 'Routes' ||
                                            data.table_name == 'Pitches'
                                        "
                                    >
                                        Grade
                                    </th>

                                    <th v-if="data.table_name != 'Parmissions'">
                                        |
                                    </th>
                                    <th v-if="data.table_name != 'Parmissions'">
                                        Edit
                                    </th>

                                    <th v-if="data.table_name != 'Parmissions'">
                                        |
                                    </th>
                                    <th v-if="data.table_name != 'Parmissions'">
                                        Delite
                                    </th>
                                </tr>
                            </thead>

                            <!-- -- {{ data.table_name }} -- -->

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

// import editor from '../../../items/canvas/EditorComponent.vue'

import tableFilter from "../../filters/FiltersComponent.vue";

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
// import productTab from './tabs/ProductsTabComponent.vue'
import categoryTab from "./tabs/CategoriesTabComponent.vue";
import postTopicTab from "./tabs/PostTopicTabComponent.vue";
import postTab from "./tabs/PostTabComponent.vue";

import sectorModal from "./tab_modals/SectorsModalComponent.vue";
import editOrderStatusModal from "./tab_modals/EditOrderStatusსModalComponent.vue";
import mtpModel from "./tab_modals/MTPModalComponent.vue";
import orderDetalModal from "./tab_modals/OrderDetalsModalComponent.vue";
import roleModal from "./tab_modals/RolesModalComponent.vue";
import articleQuickViewModal from "./tab_modals/ArticleQuickViewModalComponen.vue";

export default {
    components: {
        tableFilter,

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

        sectorModal,
        editOrderStatusModal,
        mtpModel,
        orderDetalModal,
        roleModal,
        articleQuickViewModal,
    },

    props: ["table_data"],

    data() {
        return {
            tab_num: 1,
            show_sector_modal: false,
            // show_mtp_modal: false,
            // order_detals_modal: false,
            // roles_modal: false,
            // edit_order_modal: false,
        };
    },

    mounted() {
        this.tab_num = 1;
        // this.num = this.table_data.length
        // alert(this.table_data.length)
        // if (this.table_1_name == 'Sector') {
        //     this.get_sectors_data();
        //     this.get_region_data();
        //     this.get_routes_data();
        //     this.get_MTP_data()
        //     this.get_MTP_pitch_data()
        // }
        // if (this.table_1_name == 'Products') {
        //     this.get_product_category_data();
        //     this.get_product_data();
        // }
        // if (this.table_1_name == 'Mount routes') {
        //     this.get_mount_route_data();
        //     this.get_mount_data();
        // }
        // if(this.table_1_name == 'Users'){
        //     this.get_user_role()
        // }
    },

    methods: {
        filtr(event) {
            this.$emit("filtr", event);
        },
        update(id) {
            this.$emit("update-data", [id]);
        },

        sector_modal(event) {
            console.log(event);
            // this.show_sector_modal = event
        },

        // del_article(id){
        //     if(confirm('Are you sure, you want delite it?')){
        //         axios
        //         .post('../../api/article/'+id, {
        //             id: id,
        //             _method: 'DELETE'
        //         })
        //         .then(Response => {
        //             this.update(this.tab_num)
        //         })
        //         .catch(error => console.log(error))
        //     }
        // },

        // del_sector(id){
        //     if(confirm('Are you sure, you want delite it?')){
        //         axios
        //         .post('../../api/sector/'+id, {
        //             id: id,
        //             _method: 'DELETE'
        //         })
        //         .then(Response => {
        //             this.update(this.tab_num)
        //         })
        //         .catch(error => console.log(error))
        //     }
        // },
        // del_route(id){
        //     if(confirm('Are you sure, you want delite it?')){
        //         axios
        //         .post('../../api/route/'+id, {
        //             id: id,
        //             _method: 'DELETE'
        //         })
        //         .then(Response => {
        //             this.update(this.tab_num)
        //         })
        //         .catch(error => console.log(error))
        //     }
        // },
        // del_pitch(id){
        //     if(confirm('Are you sure, you want delite it?')){
        //         axios
        //         .post('../../api/MTPPitch/'+id, {
        //             id: id,
        //             _method: 'DELETE'
        //         })
        //         .then(Response => {
        //             this.update(this.tab_num)
        //         })
        //         .catch(error => console.log(error))
        //     }
        // },
        // del_multi_pitch(id){
        //     if(confirm('Are you sure, you want delite it?')){
        //         axios
        //         .post('../../api/MTP/'+id, {
        //             id: id,
        //             _method: 'DELETE'
        //         })
        //         .then(Response => {
        //             this.update(this.tab_num)
        //         })
        //         .catch(error => console.log(error))
        //     }
        // },

        // show_parmission_edit_madel(user_id){
        //     this.roles_modal=true;
        //     this.user_id_for_rditing_parmission = user_id
        // },

        // edit_permission(id) {
        //     axios
        //     .post('users/edit_user_permission/' + id, {
        //         parmission: this.user_new_parmission,
        //     })
        //     .then((response)=> {
        //         this.roles_modal = false
        //     })
        //     .catch(error =>{
        //         if (error.response.status == 422) {
        //             this.parmision_error = error.response.data.errors
        //         }
        //         this.is_parmision_error = true
        //     })
        // },
        // get_user_role: function(user_id){
        //     axios
        //     .get('users/get_role/', {

        //     })
        //     .then(Response => {
        //         console.log(Response.data);
        //         this.user_roles = Response.data
        //     })
        //     .catch(error => {
        //         // this.user_role = "error"
        //     })
        // },

        // show_order_status_edit_madel(order_id){
        //     this.edit_order_modal = true
        //     this.get_orders_data(order_id)
        // },
        // show_order_detals_madel(order_id){
        //     this.order_detals_modal = true
        //     this.get_orders_data(order_id)
        // },

        // show_sector_model(sector_id){
        //     this.show_sector_modal=true

        // if (this.show_sector_modal==true) {
        //     axios
        //     .get('/routes_and_sectors/get_routes_for_model/'+ sector_id)
        //     .then(response => {
        //         this.sector_routes = response.data
        //     })
        //     .catch(
        //         error => console.log(error)
        //     );

        //     axios
        //     .get('/routes_and_sectors/get_sector_image/'+ sector_id)
        //     .then(response => {
        //         this.sector_images = response.data.sector_images
        //         this.sector_images_size = response.data.sector_images_size
        //     })
        //     .catch(
        //         error => console.log(error)
        //     );

        //     axios
        //     .get('/routes_and_sectors/get_mtp_for_model/'+ sector_id)
        //     .then(response => {
        //         this.sector_mtps = response.data
        //     })
        //     .catch(
        //         error => console.log(error)
        //     );
        // }
        // else{
        //     this.sector_routes = ""
        //     this.sector_images = ""
        //     this.sector_mtp = ""
        // }
        // },
        // show_mtp_model(mtp_id){
        //     this.show_mtp_modal=true

        //     if (this.show_mtp_modal==true) {
        //         axios
        //         .get('/routes_and_sectors/get_mtp_pitchs_for_model/'+ mtp_id)
        //         .then(response => {
        //             this.sector_mtp_pitchs_for_modal = response.data
        //         })
        //         .catch(
        //             error => console.log(error)
        //         );
        //     }
        //     else{
        //         this.sector_mtp_pitchs_for_modal = ""
        //     }
        // },

        // save_pitchs_sequence(){
        //     axios
        //     .post('../routes_and_sectors/pitchs_sequence/', {
        //         pitchs_sequence: this.sector_mtp_pitchs_for_modal,
        //     })
        //     .then((response)=> {
        //         this.show_mtp_modal = false
        //     })
        //     .catch(error =>{
        //     })
        // },
        // save_routes_sequence(){
        //     axios
        //     .post('../routes_and_sectors/routes_sequence/', {
        //         routes_sequence: this.sector_routes,
        //         mtp_sequence: this.sector_mtps,
        //     })
        //     .then((response)=> {
        //         this.show_sector_modal = false
        //     })
        //     .catch(error =>{
        //     })
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
