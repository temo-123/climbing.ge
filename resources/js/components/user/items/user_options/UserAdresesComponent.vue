<template>
    <div class="col-md-12">
        <div class="row edit_buttom">
            <div class="col-md-12">
                <p class="text-center">These addresses are used for your order declarations. Manage them here to ensure accurate delivery.</p>
            </div>
        </div>

        <!-- <div class="row edit_buttom">
            <div class="col-md-12">
                <button class="btn btn-primary" @click="open_add_modal()">Add new adres</button>
            </div>
        </div> -->

        <div class="row">
            <div class="col-md-6">
                <div class="form-groupe">
                <button class="btn btn-primary" @click="open_add_modal()">Add New Address</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-groupe">
                    <button @click="get_adres" class="btn btn-success float-right" v-if="!is_adres_refresh">Refresh ({{adres_reset_id}})</button>
                    <span class="badge badge-primare mb-1 float-right" v-if="is_adres_refresh">Updating...</span>
                </div>
            </div>
        </div>

        <div class="data_tab">
            <table class="table table-hover" id="dev-table">
                <thead>
                    <tr>
                        <th class='align-center'>
                            <input type="checkbox" class="all">
                        </th>
                        <th>|</th>
                        <th>ID</th>
                        <th>|</th>
                        <th>Address Name</th>

                        <th></th>
                        <th>Edit</th>

                        <th></th>
                        <th>Delete</th>
                        
                    </tr>
                </thead>
                
                <tbody >
                    <tr v-for="adres in adreses" :key="adres.id">
                        <td class='align-center'>
                            <input type="checkbox">
                        </td>
                        <td></td>
                        <td>{{ adres.id }}</td>

                        <td></td>
                        <td @click="quick_viwe_adres(adres.id)" :style="'cursor: zoom-in'" title="Click to view details">{{ adres.name }}</td>

                        <td></td>
                        <td>
                            <a :href="'#'" class="btn btn-primary" type="submit" @click="open_edit_modal(adres.id)">Edit</a>
                        </td>

                        <td></td>
                        <td>
                            <button type="submit" class="btn btn-danger" @click="del_adres(adres.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <stack-modal
                :show="is_add_adres"
                title="Add New Address"
                @close="is_add_adres=false"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <form class="form" method="POST" id="add_user_adres_form" v-on:submit.prevent="add_new_adres">
                    <input type="text" class="form-control" v-model="adding_data.demo_name" name="name" placeholder="Enter address name (e.g., Home, Work)" title="Enter address name">
                    <div class="alert alert-danger" role="alert" v-if="errors.demo_name">Please enter an address name.</div>

                    <select class="form-control" v-model="adding_data.country_id" name="Currency"> 
                        <option :value="0" disabled>Country</option>
                        <option v-for="country in qounties" :key="country.id" :value="country.id" >{{ country.country }}</option>
                    </select> 
                    <div class="alert alert-danger" role="alert" v-if="errors.country_id">Please select a country.</div>

                    <input type="text" class="form-control" v-model="adding_data.city" name="city" id="city" placeholder="Enter your city" title="Enter your city">
                    <div class="alert alert-danger" role="alert" v-if="errors.city">Please enter your city.</div>

                    <input type="text" class="form-control" v-model="adding_data.strit" name="strit" id="strit" placeholder="Enter your street" title="Enter your street">
                    <div class="alert alert-danger" role="alert" v-if="errors.strit">Please enter your street.</div>

                    <input type="text" class="form-control" v-model="adding_data.number" name="number" id="number" placeholder="Enter your house number" title="Enter your house number">
                    <div class="alert alert-danger" role="alert" v-if="errors.number">Please enter your house number.</div>

                    <input type="text" class="form-control" v-model="adding_data.floor" name="floor" id="floor" placeholder="Enter your floor (optional)" title="Enter your floor">
                    <div class="alert alert-danger" role="alert" v-if="errors.floor">Please enter your floor.</div>

                    <input type="text" class="form-control" v-model="adding_data.flat" name="flat" id="flat" placeholder="Enter your flat" title="Enter your flat">
                    <input type="text" class="form-control" v-model="adding_data.entrance" name="entrance" id="entrance" placeholder="Enter your entrance" title="Enter your entrance">
                    <input type="text" class="form-control" v-model="adding_data.zip_code" name="zip code" id="zip code" placeholder="Enter your zip code" title="Enter your zip code">
                    
                    <!-- <input type="text" class="form-control" v-model="adding_data.demo_name" name="name" placeholder="Enter your name" title="Enter your name">

                    <select class="form-control" v-model="edit_data.country_id" name="Currency"> 
                        <option :value="0" disabled>Country</option>
                        <option v-for="country in qounties" :key="country.id" :value="country.id" >{{ country.country }}</option>
                    </select> 

                    <input type="text" class="form-control" v-model="adding_data.city" name="city" id="city" placeholder="Enter your city" title="Enter your city">
                    <input type="text" class="form-control" v-model="adding_data.strit" name="strit" id="strit" placeholder="Enter your strit" title="Enter your strit">
                    <input type="text" class="form-control" v-model="adding_data.number" name="number" id="number" placeholder="Enter your number" title="Enter your number">
                    <input type="text" class="form-control" v-model="adding_data.floor" name="floor" id="floor" placeholder="Enter your floor" title="Enter your floor">
                    <input type="text" class="form-control" v-model="adding_data.flat" name="flat" id="flat" placeholder="Enter your flat" title="Enter your flat">
                    <input type="text" class="form-control" v-model="adding_data.entrance" name="entrance" id="entrance" placeholder="Enter your entrance" title="Enter your entrance">
                    <input type="text" class="form-control" v-model="adding_data.zip_code" name="zip code" id="zip code" placeholder="Enter your zip code" title="Enter your zip code"> -->
                </form>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="sumbit"
                        :class="{'btn btn-primary': true}"
                        form="add_user_adres_form"
                    >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>

        <stack-modal
                :show="is_edit_adres"
                title="Edit Address"
                @close="is_edit_adres=false"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <form class="form" method="POST" id="edit_user_adres_form" v-on:submit.prevent="edit_adres" >

                    <input type="text" class="form-control" v-model="edit_data.demo_name" name="name" placeholder="Enter address name (e.g., Home, Work)" title="Enter address name">
                    <div class="alert alert-danger" role="alert" v-if="errors.demo_name">Please enter an address name.</div>

                    <select class="form-control" v-model="edit_data.country_id" name="Currency"> 
                        <option :value="0" disabled>Country</option>
                        <option v-for="country in qounties" :key="country.id" :value="country.id" >{{ country.country }}</option>
                    </select> 
                    <div class="alert alert-danger" role="alert" v-if="errors.country_id">Please select a country.</div>

                    <input type="text" class="form-control" v-model="edit_data.city" name="city" id="city" placeholder="Enter your city" title="Enter your city">
                    <div class="alert alert-danger" role="alert" v-if="errors.city">Please enter your city.</div>

                    <input type="text" class="form-control" v-model="edit_data.strit" name="strit" id="strit" placeholder="Enter your street" title="Enter your street">
                    <div class="alert alert-danger" role="alert" v-if="errors.strit">Please enter your street.</div>

                    <input type="text" class="form-control" v-model="edit_data.number" name="number" id="number" placeholder="Enter your house number" title="Enter your house number">
                    <div class="alert alert-danger" role="alert" v-if="errors.number">Please enter your house number.</div>

                    <input type="text" class="form-control" v-model="edit_data.floor" name="floor" id="floor" placeholder="Enter your floor (optional)" title="Enter your floor">
                    <div class="alert alert-danger" role="alert" v-if="errors.floor">Please enter your floor.</div>

                    <input type="text" class="form-control" v-model="edit_data.flat" name="flat" id="flat" placeholder="Enter your flat" title="Enter your flat">
                    <input type="text" class="form-control" v-model="edit_data.entrance" name="entrance" id="entrance" placeholder="Enter your entrance" title="Enter your entrance">
                    <input type="text" class="form-control" v-model="edit_data.zip_code" name="zip code" id="zip code" placeholder="Enter your zip code" title="Enter your zip code">
                    
                    <!-- <input type="text" class="form-control" v-model="edit_data.demo_name" name="name" placeholder="Enter your name" title="Enter your name">
                    <input type="text" class="form-control" v-model="edit_data.country" name="surname" id="surname" placeholder="Enter your last name" title="Enter your last name">

                    <select class="form-control" v-model="edit_data.country_id" name="Currency"> 
                        <option :value="0" disabled>Country</option>
                        <option v-for="country in qounties" :key="country.id" :value="country.id" >{{ country.country }}</option>
                    </select> 

                    <input type="text" class="form-control" v-model="edit_data.strit" name="strit" id="strit" placeholder="Enter your strit" title="Enter your strit">
                    <input type="text" class="form-control" v-model="edit_data.number" name="number" id="number" placeholder="Enter your number" title="Enter your number">
                    <input type="text" class="form-control" v-model="edit_data.floor" name="floor" id="floor" placeholder="Enter your floor" title="Enter your floor">
                    <input type="text" class="form-control" v-model="edit_data.flat" name="flat" id="flat" placeholder="Enter your flat" title="Enter your flat">
                    <input type="text" class="form-control" v-model="edit_data.entrance" name="entrance" id="entrance" placeholder="Enter your entrance" title="Enter your entrance">
                    <input type="text" class="form-control" v-model="edit_data.zip_code" name="zip code" id="zip code" placeholder="Enter your zip code" title="Enter your zip code"> -->
                </form>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="submit"
                        :class="{'btn btn-primary': true}"
                        form="edit_user_adres_form"
                    >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>

        <stack-modal
                :show="is_quick_viwe_adres_model"
                :title="'Address Details: ' + quick_adres.name"
                @close="is_quick_viwe_adres_model=false"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                Country: {{ quick_adres.country }}
                City: {{ quick_adres.city }}
                Street: {{ quick_adres.strit }}
                House Number: {{ quick_adres.number }}
                Floor: {{ quick_adres.floor }}
                Flat: {{ quick_adres.flat }}
                Entrance: {{ quick_adres.entrance }}
                Zip Code: {{ quick_adres.zip_code }}
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <!--  -->
                </div>
            </div>
        </stack-modal>

    </div>
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
            // 'status',
        ],
        data(){
            return {
                is_add_adres: false,
                is_edit_adres: false,
                adreses: [],

                is_adres_refresh: false,
                adres_reset_id: 0,

                quick_adres: [],
                is_quick_viwe_adres_model: false,

                adding_data: {
                    demo_name: null,
                    country_id: 0,
                    city: null,
                    strit: null,
                    number: null,
                    floor: null,
                    flat: null,
                    entrance: null,
                    zip_code: null,
                },
                errors: {
                    demo_name: false,
                    country_id: false,
                    city: false,
                    strit: false,
                    number: false,
                    floor: false,
                },

                editing_adres_id: null,
                edit_data: {
                    demo_name: null,
                    country_id: 0,
                    city: null,
                    strit: null,
                    number: null,
                    floor: null,
                    flat: null,
                    entrance: null,
                    zip_code: null,
                },
                qounties: [],
            }
        },
        mounted() {
            this.get_adres()
            this.get_shipd_countries()
        },

        methods: {
            get_shipd_countries(){
                axios
                .get("/get_countries/")
                .then(response => {
                    this.qounties = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_adres() {                    
                this.is_adres_refresh = true
                axios
                .get('/get_user_adreses/')
                .then(Response => {
                    this.adreses = Response.data,

                    this.is_adres_refresh = false
                    this.adres_reset_id ++
                })
                .catch(error => console.log(error))
            },

            // add_new_adres() {
            //     axios
            //     .post('/add_user_adreses/',{
            //         adding_data: this.adding_data,

            //         _method: 'POST'
            //     })
            //     .then(Response => {
            //         this.get_adres()
            //         this.is_add_adres = false
            //     })
            //     .catch(error => console.log(error))
            // },
            add_new_adres() {
                this.errors = {
                    demo_name: false,
                    country_id: false,
                    city: false,
                    strit: false,
                    number: false,
                    floor: false,
                }
                if(
                    this.adding_data.demo_name && 
                    this.adding_data.country_id &&
                    this.adding_data.city &&
                    this.adding_data.strit &&
                    this.adding_data.number &&
                    this.adding_data.floor
                ){
                    axios
                    .post('/add_user_adreses/',{
                        adding_data: this.adding_data,

                        _method: 'POST'
                    })
                    .then(Response => {
                        this.get_adres(true)
                        this.adding_data = {
                            demo_name: null,
                            country_id: 0,
                            city: null,
                            strit: null,
                            number: null,
                            floor: null,
                            flat: null,
                            entrance: null,
                            zip_code: null,
                        }
                    })
                    .catch(error => console.log(error))
                }
                else{
                    if(!this.adding_data.demo_name){
                        this.errors.demo_name = true
                    }
                    if(!this.adding_data.country_id){
                        this.errors.country_id = true
                    }
                    if(!this.adding_data.city){
                        this.errors.city = true
                    }
                    if(!this.adding_data.strit){
                        this.errors.strit = true
                    }
                    if(!this.adding_data.number){
                        this.errors.number = true
                    }
                    if(!this.adding_data.floor){
                        this.errors.floor = true
                    }
                }
            },
            
            edit_adres() {
                this.errors = {
                    demo_name: false,
                    country_id: false,
                    city: false,
                    strit: false,
                    number: false,
                    floor: false,
                }
                if(
                    this.edit_data.demo_name && 
                    this.edit_data.country_id &&
                    this.edit_data.city &&
                    this.edit_data.strit &&
                    this.edit_data.number &&
                    this.edit_data.floor
                ){
                    axios
                    .post('/edit_adres/'+this.editing_adres_id,{
                        data: this.edit_data,
                        // editing_adres_id: this.editing_adres_id,

                        _method: 'POST'
                    })
                    .then(Response => {
                        // this.adreses = Response.data
                        this.get_adres()
                        this.is_edit_adres = false
                    })
                    .catch(error => console.log(error))
                }
                else{
                    if(!this.edit_data.demo_name){
                        this.errors.demo_name = true
                    }
                    if(!this.edit_data.country_id){
                        this.errors.country_id = true
                    }
                    if(!this.edit_data.city){
                        this.errors.city = true
                    }
                    if(!this.edit_data.strit){
                        this.errors.strit = true
                    }
                    if(!this.edit_data.number){
                        this.errors.number = true
                    }
                    if(!this.edit_data.floor){
                        this.errors.floor = true
                    }
                }
            },

            quick_viwe_adres(adres_id){
                this.get_activ_adres(adres_id)
            },
            get_activ_adres(adres_id){
                axios
                .get('/get_activ_adres/'+adres_id)
                .then(Response => {
                    this.quick_adres = Response.data
                    this.is_quick_viwe_adres_model = true
                })
                .catch(error => console.log(error))
            },

            get_editing_adres(adres_id) {
                this.edit_data = {
                    demo_name: '',
                    country_id: 0,
                    city: '',
                    strit: '',
                    number: '',
                    floor: '',
                    flat: '',
                    entrance: '',
                    zip_code: '',
                }
                this.editing_adres_id = ''

                axios
                .post('/get_editing_adres/'+adres_id)
                .then(Response => {
                    this.editing_adres_id = Response.data.id

                    this.edit_data = {
                        demo_name: Response.data.name,
                        country_id: Response.data.country_id,
                        city: Response.data.city,
                        strit: Response.data.strit,
                        number: Response.data.number,
                        floor: Response.data.floor,
                        flat: Response.data.flat,
                        entrance: Response.data.entrance,
                        zip_code: Response.data.zip_code,
                    }

                    this.is_edit_adres = true
                })
                .catch(error => console.log(error))
            },

            del_adres(id) {
                if(confirm('Are you sure you want to delete this address? This action cannot be undone.')){
                    axios
                    .delete('/del_user_adreses/'+id)
                    .then(response => {
                        this.get_adres()
                    })
                    .catch(error => console.log('Error deleting address:', error))
                }
            },

            open_edit_modal(adres_id) {
                this.errors = {
                    demo_name: false,
                    country_id: false,
                    city: false,
                    strit: false,
                    number: false,
                    floor: false,
                }
                this.get_editing_adres(adres_id)
                // this.is_edit_adres = true
            },

            open_add_modal() {
                this.errors = {
                    demo_name: false,
                    country_id: false,
                    city: false,
                    strit: false,
                    number: false,
                    floor: false,
                }
                this.is_add_adres = true
            }

        }
    }
</script>

<style>

</style>