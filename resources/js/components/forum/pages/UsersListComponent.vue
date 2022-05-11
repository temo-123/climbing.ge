<template>
    <div>
        <main role="main" class="container">

        <div class="row">
            <div class="col-md-8 blog-main">
                user list
            </div>

            <rightMenu />

        </div>
        </main>
    </div>
</template>

<script>
    import rightMenu from '../items/RightMenuComponent.vue'

    export default {
        components: {
            rightMenu,
        },
        data(){
            return {
                users: [],
            }
        },
        mounted() {
            this.get_users()
        },
        watch: {
            '$route' (to, from) {
                this.users = [],
                this.get_users()
            }
        },
        methods: {
            get_users(){
                axios
                .get("../api/users/")
                .then(response => {
                    this.users == response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
        }
    }
</script>

<style>

</style>