<template> 
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-md-6 col-sm-12 img">
                    <img v-if="this.user.image == null" :src="'/public/images/user_img/user_demo_img/user_demo_img.gif'" class="rounded mx-auto d-block user_demo_img"  alt="user image">
                    <img v-else :src="'/public/images/user_img/user_demo_img/' + this.user.image" class="rounded mx-auto d-block user_demo_img"  alt="user image">
                    <!-- <a :href="'/options/user_info_update/'+this.user.id"><p class="text-center">Upload new photo</p></a> -->
                    <!-- <input type="file" name="profile_pic" id="profile_pic" class="text-center">  -->
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

                <!-- <div class="form-group clearfix">
                    <div class="col-xs-6">
                        <label for="password"><h4>Password*</h4></label>
                        <input type="password" class="form-control" v-model="pa" name="password" id="password" placeholder="password" title="enter your password." :value="password">
                    </div>

                    <div class="col-xs-6">
                        <label for="password2"><h4>Password werify*</h4></label>
                        <input type="password" class="form-control" v-model="web_link" name="password2" id="password2" placeholder="password2" title="enter your password2." :value="password">
                    </div>
                </div> -->

                <div class="form-group clearfix">
                    <div class="col-xs-12">
                        <button class="btn btn-lg btn-success mt-5" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save new user information</button>
                    </div>
                </div>

            </form>

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
