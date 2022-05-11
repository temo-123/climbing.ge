<template>
    <div class="col-md-12">
        <div class="row" >
            <div class="col-md-4 col-md-offset-1 text-center add_button">
                <div class="row service_icon" @click="open_image_modal()">
                    <i class="fa fa-camera" aria-hidden="true"></i>
                    <h2>Add Photo</h2>
                    <p>Add your ascent photo</p>
                </div>
            </div>
            <div class="col-md-4 col-md-offset-2 text-center add_button">
                <div class="row service_icon" @click="open_post_modal()">
                    <i class="fa fa-post-camera" aria-hidden="true"></i>
                    <h2>Add post</h2>
                    <p>Add your ascent post</p>
                </div>
            </div>
        </div>

        {{ get_auth_user }}

        <div class="row" >
            <div class="col-md-4 col-md-offset-2 text-center">
                <button class="btn btn-primary">Login</button>
            </div>
            <div class="col-md-4 text-center">
                <button class="btn btn-primary">Register</button>
            </div>
        </div>

        <hr style="margin-top: 5%">

        <stack-modal
                :show="add_image_modal"
                title="Add Image"
                @close="add_image_modal=false"
                :modal-class="{ [modalClass]: true }"
                :saveButton="{ visible: true, title: 'Post', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: true, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <div class="model-body">
                add image
            </div>
        </stack-modal>


        <stack-modal
                :show="add_post_modal"
                title="Add post"
                @close="add_post_modal=false"
                :modal-class="{ [modalClass]: true }"
                :saveButton="{ visible: true, title: 'Post', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: true, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <div class="model-body">
                add post
            </div>
        </stack-modal>
    </div>
</template>

<script>
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    import { mapActions, mapGetters } from 'vuex'
    export default {
        components: {
            StackModal,
        },
        data(){
            return{
                add_image_modal: false,
                add_post_modal: false,
                modalClass: '',
            }
        },
        mounted() {
            // if (process.browser){
            //     localStorage.getItem("user")
            // }
            // console.log( this.authing_user());
           
        },
        computed: mapGetters(["get_auth_user"]),
        // methods: mapActions(['authing_user']),
        methods: {
            ...mapActions(['authing_user']),
            // auth_user(){
            //     this.update_user_data()
            // },
            open_image_modal(){
                this.add_image_modal=true
            },
            open_post_modal(){
                this.add_post_modal=true
            }
        }
    }
</script>

<style>
    .fade {
        opacity: 1;
        background: #a2a2a2;
    }
    body.modal-open{
        margin-right: 0;
    }
    .modal-dialog {
        margin-top: 15em;
    }
    @media screen and (min-width: 768px){
        .modal-dialog {
            width: 30%;
        }
    }
    .modal-footer {
        text-align: left;
    }

    .service_icon i {
        font-size: 200%;
    }
    .add_button{
        border-style: groove;
        border-color: rgb(66, 66, 66);
        border-radius: 3em;
    }
    .add_button :hover{
        border-radius: 3em;
        background-color: #e2e2e2;
        cursor: pointer;
    }
</style>