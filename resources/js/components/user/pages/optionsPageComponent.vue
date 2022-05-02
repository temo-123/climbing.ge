<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <left-menu />
            </div>
            <div class="col-md-9">
                <div class="tabs"> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col">
                                    <input type="radio" :id="1" :value="1" v-model="tab_num">
                                    
                                    <label :for="1" >my privat info</label>
                                </div>
                                <div class="col">
                                    <input type="radio" :id="2" :value="2" v-model="tab_num">
                                    
                                    <label :for="2" >my adreses</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12" v-if="tab_num == 1">
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12 img">
                                                <img v-if="this.user.image == null" :src="'/public/images/user_img/user_demo_img/user_demo_img.gif'" class="rounded mx-auto d-block user_demo_img"  alt="user image">
                                                <img v-else :src="'/public/images/user_img/user_demo_img/' + this.user.image" class="rounded mx-auto d-block user_demo_img"  alt="user image">
                                            </div>
                                            <div class="col-md-6 col-sm-12 details">
                                                <p>Name - {{this.user.name}}</p>
                                                <p>Surname - {{this.user.surname}}</p>
                                                <p>Email - {{this.user.email}}</p>
                                                <p>Your Status = {{this.status}}</p>
                                                <div class="form-groupe">
                                                    <button @click="get_user_data" class="btn main-btn pull-right" v-if="!user_is_refresh">Refresh ({{user_reset_id}})</button>
                                                    <span class="badge badge-primare mb-1 pull-right" v-if="user_is_refresh">Updating...</span>
                                                </div>
                                            </div>
                                        </div>


                                        <form class="form" method="POST" @submit.prevent="user_data_update" id="registrationForm">

                                            <div class="form-group clearfix">
                                                <div class="col-xs-6">
                                                    <label for="name"><h4>First name*</h4></label>
                                                    <input type="text" class="form-control" v-model="name" name="name" placeholder="name" title="enter your name">
                                                </div>

                                                <div class="col-xs-6">
                                                    <label for="surname"><h4>Last name*</h4></label>
                                                    <input type="text" class="form-control" v-model="surname" name="surname" id="surname" placeholder="last name" title="enter your last name">
                                                </div>
                                            </div>

                                            <div class="form-group clearfix">
                                                <div class="col-xs-6">
                                                    <label for="phone_number"><h4>Phone number*</h4></label>
                                                    <input type="text" class="form-control" v-model="phone_number" name="phone_number" id="phone_number" placeholder="enter phone number" title="enter your phone number">
                                                </div>

                                                <div class="col-xs-6">
                                                    <label for="email"><h4>Email*</h4></label>
                                                    <input type="email" class="form-control" v-model="email" name="email" id="email" placeholder="email" title="enter your email.">
                                                </div>
                                            </div>

                                            <div class="form-group clearfix">
                                                <div class="col-md-6">
                                                    <label for="Cuntry"><h4>Cuntry</h4></label>
                                                    <input type="text" class="form-control" v-model="city" name="city" id="city" placeholder="city" title="enter your city.">
                                                </div>
                                                
                                                <div class="col-xs-6">
                                                    <label for="country"><h4>Yor country</h4></label>
                                                    <input type="text" class="form-control" v-model="country" name="country" id="country" placeholder="country" title="enter a country.">
                                                </div>
                                            </div>

                                            <div class="form-group clearfix">
                                                <div class="col-xs-12">
                                                    <button class="btn btn-lg btn-success mt-5" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save new user information</button>
                                                </div>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12" v-if="tab_num == 2">
                                    
                                    <div class="row edit_buttom">
                                        <div class="col-md-12">
                                            <button class="btn btn-primary" >Add new adres</button>
                                        </div>
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

                                                <th>|</th>
                                                <th>Edit</th>
                                                
                                                <th>|</th>
                                                <th>Delite</th>
                                                
                                            </tr>
                                        </thead>
                                        
                                        <tbody >
                                            <tr >
                                                <td style='text-align: center;'>
                                                    <input type="checkbox">
                                                </td>
                                                <td>|</td>
                                                <td>id</td>

                                                <td>|</td>
                                                <td>adres name</td>

                                                <td>|</td>
                                                <td>
                                                    <a :href="'#'" class="btn btn-primary" type="submit">Edit</a>
                                                </td>
                                                
                                                <td>|</td>
                                                <td>
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'status',
        ],
        data(){
            return {
                tab_num: 1,

                user: [],
                user_big_array: [],
                user_is_refresh: false,
                user_reset_id: 0,

                user_id: "",
                name: "",
                surname: "",
                phone_number: "",
                email: "",
                city: "",
                country: "",
            }
        },
        mounted() {
            this.get_user_data()
        },

        methods: {
            get_user_data: function(){
                this.user_is_refresh = true
                axios
                .get("/options/get_user_data/")
                .then(response => {
                    this.user_big_array = response.data
                    this.user = this.user_big_array[0]
                    
                    this.user_id = this.user['id']
                    this.name = this.user['name']
                    this.surname = this.user['surname']
                    this.phone_number = this.user['phone_number']
                    this.email = this.user['email']
                    this.city = this.user['city']
                    this.country = this.user['country']

                    this.user_is_refresh = false
                    this.user_reset_id++
                    // console.log(this.user.name);
                })
                .catch(
                    error => console.log(error)
                );
            },
            user_data_update() {
                axios
                .post('/options/user_info_update/' + this.user_id, {
                    name: this.name,
                    surname: this.surname,
                    phone_number: this.phone_number,
                    email: this.email,
                    city: this.city,
                    country: this.country,
                })
                .then(Response => {
                    console.log(response)
                })
                .catch(error => console.log(error))
            }
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