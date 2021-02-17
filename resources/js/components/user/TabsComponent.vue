<template>
    <div class="tabs">
        <div class="row">
        <input type="radio" name="tabs" id="1" checked="checked">
        <label for="1" v-if="this.table_1_get_route">{{this.table_1_name}}</label>
        <div class="tab" v-if="this.table_1_get_route">
            <div class="add_buttom">
                <a :href="table_1_add_url" class="btn btn-primary pull-left" type="submit">New </a>
            </div>
            <div class="form-groupe">
                <button @click="get_data_in_table_1" class="btn main-btn pull-right" v-if="!table_1_is_refresh">Refresh ({{table_1_reset_id}})</button>
                <span class="badge badge-primare mb-1 pull-right" v-if="table_1_is_refresh">Updating...</span>
            </div>
            <table class="table table-hover" id="dev-table">
                <thead>
                    <tr>
                        <th style='text-align: center;'>
                            <input type="checkbox" class="all">
                            <!-- <label><input type="checkbox" class="all"></label> -->
                        </th>
                        <th>|</th>
                        <th>ID</th>
                        <!-- <td v-if="table_1_name == 'Sector'">|</td> -->
                        <!-- <th v-if="table_1_name == 'Sector'">Add img</th> -->
                        <th>|</th>
                        <th>Name</th>
                        <th v-if="table_1_name != 'Sector'">|</th>
                        <th style='text-align: center;' v-if="table_1_name != 'Sector'">Published</th>
                        
                        <th>|</th>
                        <th>Edit</th>
                        
                        <th>|</th>
                        <th>Delite</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="table_1_info in table_1" :key="table_1_info.id">
                        <td style='text-align: center;'>
                            <input type="checkbox">
                        </td>
                        <th>|</th>
                        <td>{{table_1_info.id}}</td>
                        <!-- <th v-if="table_1_name == 'Sector'">|</th> -->
                        <!-- <td v-if="table_1_name == 'Sector'"><button class="btn btn-primary" @click="show=true">+</button></td> -->
                        <th>|</th>

                        <td v-if="table_1_name == 'Sector'">{{table_1_info.name}}</td>
                        <td v-else>{{table_1_info.url_title}} </td>

                        <td v-if="table_1_name != 'Sector'"></td>
                        <td style='text-align: center;' v-if="table_1_name != 'Sector'">{{table_1_info.published}}</td>
                        <th>|</th>

                        <td>
                            <a :href="table_1_edit_url+table_1_info.id" class="btn btn-primary" type="submit">Edit</a>
                        </td>
                        
                        <th>|</th>
                        <td>
                            <form method="post" @submit.prevent="table_1_del(table_1_info.id)" >
                                <input type="hidden" name="_token" >
                                <div class="form-group">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <input type="radio" name="tabs" id="2">
        <label for="2" v-if="this.table_2_get_route">{{this.table_2_name}}</label>
        <div class="tab" v-if="this.table_2_get_route">
            <div class="add_buttom">
                <a :href="table_2_add_url" class="btn btn-primary pull-left" type="submit">New </a>
            </div>
            <div class="form-groupe">
                <button @click="get_data_in_table_2" class="btn main-btn pull-right" v-if="!table_2_is_refresh">Refresh ({{table_2_reset_id}})</button>
                <span class="badge badge-primare mb-1 pull-right" v-if="table_2_is_refresh">Updating...</span>
            </div>
            <table class="table table-hover" id="dev-table">
                <thead>
                    <tr>
                        <th style='text-align: center;'>
                            <input type="checkbox" class="all">
                            <!-- <label><input type="checkbox" class="all"></label> -->
                        </th>
                        <th>|</th>
                        <th>ID</th>
                        <th>|</th>
                        <th>Name</th>

                        <th v-if="table_2_name != 'Route'">|</th>
                        <th style='text-align: center;' v-if="table_2_name != 'Route'">Published</th>
                        
                        <th>|</th>
                        <th>Edit</th>
                        
                        <th>|</th>
                        <th>Delite</th>
                        
                    </tr>
                </thead>
                <tbody>
                <tr v-for="table_2_info in table_2" :key="table_2_info.id">
                        <td style='text-align: center;'>
                            <input type="checkbox">
                        </td>
                        <th>|</th>
                        <td>{{table_2_info.id}}</td>
                        <th>|</th>

                        <td v-if="table_2_name == 'Route'">{{table_2_info.name}}</td>
                        <td v-else>{{table_2_info.name}} </td>
                        <!-- <td>{{table_2_info.title}}</td> -->

                        <th v-if="table_2_name != 'Route'">|</th>
                        <td style='text-align: center;' v-if="table_2_name != 'Route'">{{table_2_info.published}}</td>
                        
                        <th>|</th>
                        <td>
                            <a :href="table_2_edit_url+table_2_info.id" class="btn btn-primary" type="submit">Edit</a>
                        </td>
                        
                        <th>|</th>
                        <td>
                            <form method="post" @submit.prevent="table_2_del(table_2_info.id)" >
                                <input type="hidden" name="_token" >
                                <div class="form-group">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <input type="radio" name="tabs" id="3">
        <label for="3"  v-if="this.table_3_get_route">{{this.table_3_name}}</label>
        <div class="tab" v-if="this.table_3_get_route">
            <div class="add_buttom">
                <a :href="table_3_add_url" class="btn btn-primary pull-left" type="submit">New </a>
            </div>
            <div class="form-groupe">
                <button @click="get_data_in_table_3" class="btn main-btn pull-right" v-if="!table_3_is_refresh">Refresh ({{table_3_reset_id}})</button>
                <span class="badge badge-primare mb-1 pull-right" v-if="table_3_is_refresh">Updating...</span>
            </div>
            <table class="table table-hover" id="dev-table">
                <thead>
                    <tr>
                        <th style='text-align: center;'>
                            <input type="checkbox" class="all">
                        </th>
                        <th>|</th>
                        <th>ID</th>
                        <th>|</th>
                        <th>Name</th>

                        <th v-if="table_3_name != 'Multi-pitch'">|</th>
                        <th style='text-align: center;' v-if="table_3_name != 'Multi-pitch'">Published</th>
                        
                        <th>|</th>
                        <th>Edit</th>
                        
                        <th>|</th>
                        <th>Delite</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="table_3_info in table_3" :key="table_3_info.id">
                        <td style='text-align: center;'>
                            <input type="checkbox">
                        </td>
                        <th>|</th>
                        <td>{{table_3_info.id}}</td>
                        <th>|</th>

                        <td v-if="table_3_name == 'Multi-pitch'">{{table_3_info.name}}</td>
                        <td v-else>{{table_3_info.title}} </td>

                        <th v-if="table_3_name != 'Multi-pitch'">|</th>
                        <td style='text-align: center;' v-if="table_3_name != 'Multi-pitch'">{{table_3_info.published}}</td>
                        
                        <th>|</th>
                        <td>
                            <a :href="table_3_edit_url+table_3_info.id" class="btn btn-primary" type="submit">Edit</a>
                        </td>
                        
                        <th>|</th>
                        <td>
                            <form method="post" @submit.prevent="table_3_del(table_3_info.id)" >
                                <input type="hidden" name="_token" >
                                <div class="form-group">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <input type="radio" name="tabs" id="4">
        <label for="4"  v-if="this.table_4_get_route">{{this.table_4_name}}</label>
        <div class="tab" v-if="this.table_4_get_route">
            <div class="add_buttom">
                <a :href="table_4_add_url" class="btn btn-primary pull-left" type="submit">New </a>
            </div>
            <div class="form-groupe">
                <button @click="get_data_in_table_4" class="btn main-btn pull-right" v-if="!table_4_is_refresh">Refresh ({{table_4_reset_id}})</button>
                <span class="badge badge-primare mb-1 pull-right" v-if="table_4_is_refresh">Updating...</span>
            </div>
            <table class="table table-hover" id="dev-table">
                <thead>
                    <tr>
                        <th style='text-align: center;'>
                            <input type="checkbox" class="all">
                            <!-- <label><input type="checkbox" class="all"></label> -->
                        </th>
                        <th>|</th>
                        <th>ID</th>
                        <th>|</th>
                        <th>Name</th>

                        <th v-if="table_4_name != 'pitches'">|</th>
                        <th style='text-align: center;' v-if="table_4_name != 'pitches'">Published</th>
                        
                        <th>|</th>
                        <th>Edit</th>
                        
                        <th>|</th>
                        <th>Delite</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="table_4_info in table_4" :key="table_4_info.id">
                        <td style='text-align: center;'>
                            <input type="checkbox">
                        </td>
                        <th>|</th>
                        <td>{{table_4_info.id}}</td>
                        <th>|</th>

                        <td v-if="table_4_name == 'pitches'">{{table_4_info.name}}</td>
                        <td v-else>{{table_4_info.title}} </td>

                        <th v-if="table_4_name != 'pitches'">|</th>
                        <td style='text-align: center;' v-if="table_4_name != 'pitches'">{{table_4_info.published}}</td>
                        
                        <th>|</th>
                        <td>
                            <a :href="table_4_edit_url+table_4_info.id" class="btn btn-primary" type="submit">Edit</a>
                        </td>
                        
                        <th>|</th>
                        <td>
                            <form method="post" @submit.prevent="table_4_del(table_4_info.id)" >
                                <input type="hidden" name="_token" >
                                <div class="form-group">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <!-- <div class="d-flex">
            <div>
                <button class="btn btn-primary" @click="show=true">Open modal</button>
            </div>
        </div>

        <stack-modal
                :show="show"
                title="Modal #1"
                @close="show=false"
                :modal-class="{ [modalClass]: true }"
                :saveButton="{ visible: false }"
                :cancelButton="{ title: 'Close', btnClass: { 'btn btn-primary': true } }"
        >

            <hr/>
            <p>Additional options: </p>
        </stack-modal> -->
        
        <!-- <mobal></mobal> -->
    </div>
    <!-- <div class="row">
        <ComponentExample link="CrossList">
            <div class="side-by-side">
            <div class="side">
                <VueNestable
                v-model="nestableItems1"
                cross-list
                group="cross"
                >
                <VueNestableHandle
                    slot-scope="{ item }"
                    :item="item"
                >
                    {{ item.text }}
                </VueNestableHandle>
                </VueNestable>
            </div>
            <div class="side">
                <VueNestable
                v-model="nestableItems2"
                cross-list
                group="cross"
                >
                <VueNestableHandle
                    slot-scope="{ item }"
                    :item="item"
                >
                    {{ item.text }}
                </VueNestableHandle>
                </VueNestable>
            </div>
            </div>
        </ComponentExample>
    </div> -->
    </div>
</template>

<script>
    import StackModal from '@innologica/vue-stackable-modal'

    export default {
        components: {
            StackModal,
        },

        props: [
            "table_1_get_route",
            "table_2_get_route",
            "table_3_get_route",
            "table_4_get_route",

            "table_1_name",
            "table_2_name",
            "table_3_name",
            "table_4_name",

            "table_1_add_url",
            "table_2_add_url",
            "table_3_add_url",
            "table_4_add_url",

            "table_1_edit_url",
            "table_2_edit_url",
            "table_3_edit_url",
            "table_4_edit_url",

            "table_1_del_url",
            "table_2_del_url",
            "table_3_del_url",
            "table_4_del_url",

            "table_1_categiry",
            "table_2_categiry",
            "table_3_categiry",
            "table_4_categiry",
        ],
        data() {
            return {
                // nestableItems1: [
                // {
                //     id: 0,
                //     text: 'Andy'
                //     }, {
                //     id: 1,
                //     text: 'Harry',
                //     children: [{
                //         id: 2,
                //         text: 'David'
                //     }]
                //     }, {
                //     id: 3,
                //     text: 'Lisa'
                //     }
                // ],
                // nestableItems2: [
                //     {
                //     id: 4,
                //     text: 'Mike'
                //     }, {
                //     id: 5,
                //     text: 'Edgar'
                //     }
                // ],
                show: false,
                show_second: false,
                show_third: false,
                modalClass: '',


                table_1: [],
                table_2: [],
                table_3: [],
                table_4: [],

                table_1_is_refresh: false,
                table_2_is_refresh: false,
                table_3_is_refresh: false,
                table_4_is_refresh: false,

                table_1_reset_id: 0,
                table_2_reset_id: 0,
                table_3_reset_id: 0,
                table_4_reset_id: 0,

                url_1: "",
                url_2: "",
                url_3: "",
                url_4: "",
            }
        },

        mounted() {
            this.get_data_in_table_1();
            this.get_data_in_table_2();
            this.get_data_in_table_3();
            this.get_data_in_table_4();
        },
         
        methods: {
            get_data_in_table_1: function(){
                this.table_1_is_refresh = true

                if (this.table_1_get_route == "/articles/get_article_data/") {
                    this.url_1 = this.table_1_get_route + this.table_1_categiry
                } else {
                    this.url_1 = this.table_1_get_route
                }

                axios
                .get(this.url_1)
                .then(response => {
                    this.table_1 = response.data
                    this.table_1_is_refresh = false
                    this.table_1_reset_id++

                    // this.get_data_in_table_1();
                })
                .catch(
                    error => console.log(error)
                );

            },
            get_data_in_table_2: function(){
                this.table_2_is_refresh = true

                if (this.table_2_get_route == "/articles/get_article_data/") {
                    this.url_2 = this.table_2_get_route + this.table_2_categiry
                } else {
                    this.url_2 = this.table_2_get_route
                }

                axios
                .get(this.url_2)
                .then(response => {
                    this.table_2 = response.data

                    this.table_2_is_refresh = false
                    this.table_2_reset_id++
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_data_in_table_3: function(){
                this.table_3_is_refresh = true

                if (this.table_3_get_route == "/articles/get_article_data/") {
                    this.url_3 = this.table_3_get_route + this.table_3_categiry
                } else {
                    this.url_3 = this.table_3_get_route
                }

                axios
                .get(this.url_3)
                .then(response => {
                    this.table_3 = response.data

                    this.table_3_is_refresh = false
                    this.table_3_reset_id++
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_data_in_table_4: function(){
                this.table_4_is_refresh = true

                if (this.table_4_get_route == "/articles/get_article_data/") {
                    this.url_4 = this.table_4_get_route + this.table_4_categiry
                } else {
                    this.url_4 = this.table_4_get_route
                }

                axios
                .get(this.url_4)
                .then(response => {
                    this.table_4 = response.data

                    this.table_4_is_refresh = false
                    this.table_4_reset_id++
                })
                .catch(
                    error => console.log(error)
                );
            },

            
            table_1_del(itemId) {
                axios
                .post(this.table_1_del_url + itemId, {
                    id: itemId,
                })
                .then(Response => {
                    console.log(response)
                    this.get_data_in_table_1()
                })
                .catch(error => console.log(error))
            },
            table_2_del(itemId) {
                axios
                .post(this.table_2_del_url + itemId, {
                    id: itemId,
                })
                .then(Response => {
                    console.log(response)
                    this.get_data_in_table_2()
                })
                .catch(error => console.log(error))
            },
            table_3_del(itemId) {
                axios
                .post(this.table_3_del_url + itemId, {
                    id: itemId,
                })
                .then(Response => {
                    console.log(response)
                    this.get_data_in_table_3()
                })
                .catch(error => console.log(error))
            },
            table_4_del(itemId) {
                axios
                .post(this.table_4_del_url + itemId, {
                    id: itemId,
                })
                .then(Response => {
                    console.log(response)
                    this.get_data_in_table_4()
                })
                .catch(error => console.log(error))
            },

        }
    }
</script>

<style scoped>
    /**
    * Tabs
    */
    .tabs {
        display: flex;
        flex-wrap: wrap; /* make sure it wraps */
    }
    .tabs label {
    /* .tabs  { */
        order: 1; /* Put the labels first */
        display: block;
        padding: 1rem 2rem;
        margin-right: 0.2rem;
        cursor: pointer;
    background: #ccced0;
    font-weight: bold;
    transition: background ease 0.2s;
    }
    .tabs .tab {
    order: 99; /* Put the tabs last */
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