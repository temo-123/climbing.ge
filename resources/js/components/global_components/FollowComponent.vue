<template>
    <div class="row f-links container">
        <div class="flick">
            <h4  class="footer_title">Follow us</h4>
        </div>
        <div class="form-group row">
            <div class="col-md-10">
                <form name="contact-form" method="POST" id="global_form" ref="myForm" @submit.prevent="follow" enctyp="multipart/form-data">
                    <input type="text" v-model="email" name="email" class="form-control"> 

                    <button class="btn btn-success" v-if="this.email != ''">Follow</button>
                    <button class="btn btn-success" disabled v-else>Follow</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:[
            // 'service',
        ],
        data: function () {
            return {
                service: 0,
                email: '',
            };
        },
        components: {
        },
        mounted() {
            
        },
        methods: {
            follow(){
                if(window.location.hostname == process.env.MIX_SITE_URL){
                    this.service = 1
                }
                else if (window.location.hostname == process.env.MIX_SHOP_URL) {
                    this.service = 2
                }
                else if (window.location.hostname == 'films.climbing.loc') {
                    this.service = 3
                }

                axios
                .post('./api/follow/' + this.service, {
                    email: this.email,

                    // _method: 'post'
                })
                .then(Response => {
                    alert(Response.data)
                    this.email = ''
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors
                        this.isLoading = false
                    }
                })
            },
        }
    }
</script>

<style>

</style>