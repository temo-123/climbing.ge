<template>
    <div class="container">
        <div class="row">
            <!-- <div class="col-sm-3"> -->
                <left-menu />
            <!-- </div> -->
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <breadcrumb />
                    </div>
                </div>
                <!-- <div class="row"> -->
                <div class="tabs"> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col">
                                    <input type="radio" :id="1" :value="1" v-model="tab_num">
                                    
                                    <label :for="1" >My privat info</label>
                                </div>
                                <div class="col">
                                    <input type="radio" :id="2" :value="2" v-model="tab_num">
                                    
                                    <label :for="2" >My adreses</label>
                                </div>
                                <div class="col">
                                    <input type="radio" :id="3" :value="3" v-model="tab_num">
                                    
                                    <label :for="3" >Notifications</label>
                                </div>
                            </div>

                            <div class="row" v-if="tab_num == 1">
                                <userOptions />
                            </div>

                            <div class="row" v-if="tab_num == 2">
                                <adresList />
                            </div>

                            <div class="row" v-if="tab_num == 3">
                                <notificationsControlList />
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- </div> -->
    </div>
</template>

<script>
    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    // import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    import breadcrumb from '../items/BreadcrumbComponent.vue'

    import notificationsControlList from '../items/user_options/UserNotificationsListComponent.vue'
    import adresList from '../items/user_options/UserAdresesComponent.vue'
    import userOptions from '../items/user_options/UserOptionsComponent.vue'

    export default {
        components: {
            // StackModal,
            // SlickItem,
            // SlickList,
            adresList,
            breadcrumb,
            userOptions,
            notificationsControlList
        },
        props: [
            'status',
        ],
        data(){
            return {
                tab_num: 1,
            }
        },
        mounted() {
            // this.get_user_data()
        },

        methods: {
            
        }
    }
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