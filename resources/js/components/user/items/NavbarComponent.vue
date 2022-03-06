<template>
    <div class="header navbar-expand-lg navbar-right bg-light">
        <div class="row fixed-top top_menu">
            <div class="col-xs-12 col-sm-2 col-md-3 col-lg-3 ">
                <div class="site_title">
                    <a href="route('user_index') }}" class="site_title">
                        Hi Boss
                    </a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-10 col-md-9 col-lg-9">
                <nav class="navbar navbar-default  float-right float-top" role="navigation">
                    <!-- <div class="container-fluid">                    
                        <ul class="dropdown-menu shadows" role="menu" style="width: 100%;">
                            <li>
                                <form action="" method="POST" role="search" class="navbar-form">
                                    <div class="form-group" style="display:inline;">
                                    <div class="input-group" style="display:table;">
                                        <input class="form-control" name="q" placeholder="Search Here" autocomplete="off" autofocus="autofocus" type="text">
                                        
                                        <span class="input-group-btn"> 
                                            <button type="submit" class="input-group-addon"> 
                                                <span class="glyphicon glyphicon-search"></span>
                                            </button>
                                        </span>
                                    </div>
                                    </div>
                                </form>
                            </li>
                        </ul>
                    </div> -->
                    <!-- <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="navbar-nav mr-auto nav pull-right top_menu_buttons"> -->
                            <!-- {{-- <li class="nav navbar-nav"> <a class="nav-link" href="{{ route('index') }}" target="_blank">climibng.ge</a> </li> --}}
                            {{-- <li class="nav navbar-nav"> <a class="nav-link" href="{{ route('shop_index') }}" target="_blank">shop.climibng.ge</a> </li> --}} -->
                        <!-- </ul>
                    </div> -->
                    <!--  export default {
        components: {
            StackModal,
            SlickItem,
            SlickList,
        },
        props: [
            'status',
        ],
        data(){
            return {
                images: [],
                articles: [],
                editing_images: [],

                is_show_image: false,
                is_add_image: false,
                is_edit_image: false,

                modal_image: '',
                filter_images: 'All',

                reset_id: 0,
                is_refresh: false,

                published: "",
                category: "",
                article_id: "",
                filter: "",
                title: "",
                text: "",
                link: "",
                image: "",
                editing_id: "",
            }
        },
        mounted() {
            this.get_gallery_data()
            this.get_articles()
        },
        methods: {
            get_gallery_data: function(){
                axios
                .post("../api/gallery_image/")
                .then(response => {
                    this.images = response.data
                    this.is_refresh = false
                    this.reset_id++
                })
                .catch(
                    error => console.log(error)
                );
            },

            get_articles(){
                axios
                .get("../api/article/")
                .then(response => {
                    this.articles = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },

            show_image_modal(image_id){
                this.is_show_image = true
                axios
                .get("../gallery/get_image/"+image_id)
                .then(response => {
                    this.modal_image = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },

            add_image_modal(){
                this.published = '',
                this.category = '',
                this.article_id = '',
                this.filter = '',
                this.title = '',
                this.text = '',
                this.link = '',
                this.image = '',
                this.editing_id = ''

                this.is_add_image = true
            },

            filtr_images(){
                alert('test')
            },

            add_image(){
                var myFormData = new FormData(this.$refs.myForm)
                axios({
                    method: 'post',
                    url: '/gallery/add/',
                    data: myFormData,
                    config: { 
                        headers: {'Content-Type': 'multipart/form-data' },
                    },
                })
                .then((response)=>  {
                    this.is_add_image = false
                    this.get_gallery_data()
                });
            },


            get_editing_data(image_id){
                axios
                .get("/gallery/get_editing_image/"+image_id)
                .then(response => {
                    this.editing_images = response.data

                    this.published = this.editing_images.published,
                    this.category = this.editing_images.category,
                    this.article_id = this.editing_images.article_id,
                    this.filter = this.editing_images.filter,
                    this.title = this.editing_images.title,
                    this.text = this.editing_images.text,
                    this.link = this.editing_images.link
                    this.image = this.editing_images.image
                    this.editing_id = this.editing_images.id
                })
                .catch(
                    error => console.log(error)
                );
            },
            edit_image_modal(image_id){
                this.is_edit_image = true
                this.get_editing_data(image_id)
            },
            edit_image(editing_id){
                var editingFormData = new FormData(this.$refs.editingForm)
                axios({
                    method: 'post',
                    url: '/gallery/edit/' + editing_id,
                    data: editingFormData,
                    config: { 
                        headers: {'Content-Type': 'multipart/form-data' },
                    },
                })
                .then((response)=>  {
                    this.is_edit_image = false
                    this.get_gallery_data()

                    this.published = '',
                    this.category = '',
                    this.article_id = '',
                    this.filter = '',
                    this.title = '',
                    this.text = '',
                    this.link = '',
                    this.image = '',
                    this.editing_id = ''
                });
            },

            delete_image(itemId) {
                axios
                .delete('../api/gallery_image/' + itemId, {
                    id: itemId,
                })
                .then(Response => {
                    this.get_gallery_data()
                })
                .catch(error => console.log(error))
            }
        }
    } -->
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
export default {

}
</script>

<style>

</style>