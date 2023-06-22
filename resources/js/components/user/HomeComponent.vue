<template>
    <div>
        <navbar />

        <div class="container top_menu_margin">
            <div class="bootstrap snippet mt-5">
                <router-view />
            </div>
        </div>
    </div>
</template>

<script>
import navbar from "./items/navbars/NavbarComponent.vue";

import { mapState } from 'vuex'
export default {
    computed: mapState({
        arrayIndex: state => state.arrayIndex
    }),

    data: function () {
        return {
            // search_query: '',
        };
    },
    components: {
        navbar,
    },

    mounted() {
        this.get_user_data()
    },

    methods: {
        get_user_data: function(){
        axios
            .get("/user/get_auth_user_permissions/")
            .then(response => {
                this.$ability.update(
                    response.data
                )
            })
            .catch(
                // error => console.log(error)
            ); 
        },
    },

    watch: {
        $route(to, from) {
            window.scrollTo(0, 0);
            this.get_user_data()
        },
    },
};
</script>