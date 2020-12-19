<template> 
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-md-6 img">
                    <i style='font-size: 1000%; margin-top: 2%;' class="fa fa-user-circle-o" aria-hidden="true"></i>
                </div>
                <div class="col-md-6 details">
                    <p>Name - {{this.user.name}}</p>
                    <p>Surname - {{this.user.surname}}</p>
                    <p>Email - {{this.user.email}}</p>
                    <p>Your Status = {{this.status}}</p>
                </div>
            </div>


            <form class="form" action="" method="post" id="registrationForm">

                <div class="form-group clearfix">
                    <div class="col-xs-6">
                        <label for="name"><h4>First name*</h4></label>
                        <input type="text" class="form-control" name="name" placeholder="name" title="enter your name" :value="this.user.name">
                    </div>

                    <div class="col-xs-6">
                        <label for="surname"><h4>Last name*</h4></label>
                        <input type="text" class="form-control" name="surname" id="surname" placeholder="last name" title="enter your last name" :value="this.user.surname">
                    </div>
                </div>

                <div class="form-group clearfix">
                    <div class="col-xs-6">
                        <label for="phone_number"><h4>Phone number*</h4></label>
                        <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="enter phone number" title="enter your phone number" :value="this.user.phone_number">
                    </div>

                    <div class="col-xs-6">
                        <label for="email"><h4>Email*</h4></label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="email" title="enter your email." :value="this.user.email">
                    </div>
                </div>

                <div class="form-group clearfix">
                    <div class="col-md-6">
                        <label for="Cuntry"><h4>Cuntry</h4></label>
                        <input type="text" class="form-control" name="city" id="city" placeholder="city" title="enter your city." :value="city">
                    </div>
                    
                    <div class="col-xs-6">
                        <label for="country"><h4>Yor country</h4></label>
                        <input type="text" class="form-control" name="country" id="country" placeholder="country" title="enter a country." :value="country">
                    </div>
                </div>

                <div class="form-group clearfix">
                    <div class="col-xs-6">
                        <label for="password"><h4>Password*</h4></label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="password" title="enter your password." :value="password">
                    </div>

                    <div class="col-xs-6">
                        <label for="password2"><h4>Password werify*</h4></label>
                        <input type="password" class="form-control" name="password2" id="password2" placeholder="password2" title="enter your password2." :value="password">
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

                name: "",
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
                    this.user_is_refresh = false
                    this.user_reset_id++
                    // console.log(this.user.name);
                })
                .catch(
                    error => console.log(error)
                );
            },
            user_data_update() {

            }
        }
    }
</script>
