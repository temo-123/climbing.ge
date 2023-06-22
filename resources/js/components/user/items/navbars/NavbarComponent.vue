<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-perple fixed-top admin_page_header_navbar">

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-bars " aria-hidden="true"></i>
        </button>

        <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2 mobali_menu"  id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Pricing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">Disabled</a>
                </li>
            </ul>
        </div>

        <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
            
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <label for="check">
                        <span id="open_menu" class="menu_but">
                            <i class="fa fa-bars cursor_pointer" aria-hidden="true"></i>
                        </span>
                    </label>
                </li>
            </ul>
        </div>
        <div class="mx-auto order-0">
            <router-link :to="{name: 'home'}" class="navbar-brand mx-auto" exact>Hi</router-link>
        </div>
        <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <router-link :to="{name: 'myComentsList'}" class="nav-link" exact>{{ $t('user.menu.my comments') }}</router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{name: 'myOrders'}" class="nav-link" exact>{{ $t('user.menu.my orders') }}</router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{name: 'cart'}" class="nav-link" exact>{{ $t('user.menu.cart') }}</router-link>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
    import countryFlag from 'vue-country-flag' // https://www.npmjs.com/package/vue-country-flag

    export default {
        data: function () {
            return {
                get activ_lang() {
                    return localStorage.getItem('lang') || 'en';
                },
                set activ_lang(value) {
                    localStorage.setItem('lang', value);
                },

                user: '',
            };
        },
        mounted(){
            this.get_user()
        },
        components: {
            countryFlag,
        },
        watch: {
            '$route' (to, from) {
                this.user = '',
                this.get_user(),
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_user(){
                axios
                .get('/api/auth_user')
                .then((response)=>{
                    this.user = response.data['name']
                })
                .catch(
                    // this.user = 'Boss'
                );
            }
        },
    }
</script>

<style>
.admin_page_header_navbar{
    transition: all .5s;
}
.menu_but{
    position: fixed;
    font-size: 200%;
    top: 0;
}
.bg-perple {
    background-color: #7427bb !important;
}
@media (min-width: 993px) {
    #navbarNav{
        display: none !important;
    }
}
</style>