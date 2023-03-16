<template>
    <div>
        <navbar />

        <div class="container top_menu_margin">
            <div class="container bootstrap snippet mt-5">
                <div v-if="$can('read', 'Article')">
                    <a >read Article</a>
                </div>
                <div v-if="$can('create', 'Article')">
                    <a >create Article</a>
                </div>
                <div v-if="$can('del', 'Article')">
                    <a >del Article</a>
                </div>
                <router-view />
            </div>
        </div>
    </div>
</template>

<script>
import navbar from "./items/NavbarComponent.vue";

export default {
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
            .get("../../../api/user/get_auth_user_permissions/")
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