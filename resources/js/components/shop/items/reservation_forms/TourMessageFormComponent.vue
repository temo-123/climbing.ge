<template>
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 mb30 text-center">
                    <h2>Tour Reservation Form</h2>
                </div>
            </div>
            <div class="row">

                <div class="col-xl-12 col-lg-12 col-md-12 mb30">
                    <div class="tour-booking-form">
                        <form @submit.prevent="create_reservation" id="reservation_form" class="contact-form" method="POST" enctype="multipart/form-data">

                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="check_in">Check in</label>
                                        <div class="select">
                                            <input id="check_in" name="check_in" type="date" placeholder="Check in" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label required" for="select">Number of Persons:</label>
                                        <div class="select">
                                            <input id="persons" name="persons" type="number" placeholder="Persons number" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-6 col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Name Surname</label>
                                        <input id="name" type="text" placeholder="Name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="email"> Email</label>
                                        <input id="email" type="text" placeholder="Your email" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="phone"> Phone</label>
                                        <input id="phone" type="text" placeholder="Your phone" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="country">Country</label>
                                        <input id="country" type="text" placeholder="Your country" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="city">City</label>
                                        <input id="city" type="text" placeholder="Your city" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="textarea">Describe Your Travel Requirements</label>
                                        <textarea class="form-control" id="textarea" name="textarea" rows="4" placeholder="Write Your Requirements"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="form-group">
                                        <button type="submit">Send</button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    // import metaData from '../../items/MetaDataComponent'

    export default {
        components: {
            // metaData,
        },
        data () {
            return {
                form_data: [],
            }
        },
        watch: {
            '$route' (to, from) {
                //this.clear_product_data()
            }
        },
        props: [
            'tour_id_prop',
        ],
        mounted() {
            //
        },
        methods: {
            create_reservation(){
                axios
                .get('/tour/reservation/create_reservation/'+this.tour_id_prop)
                .then(response => {
                    // this.product = response.data
                    alert(response.data);
                })
                .catch(error =>{
                })
            },
        }
    }
</script>
