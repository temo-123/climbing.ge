<template>
    <div class="container">
        <outdoor :article="outdoor" />
    </div>
</template>

<script>
    import outdoor from '../items/OutdoorComponent'
    export default {
        data: function () {
            return {
                outdoor: [],
            }
        },
        components: {
            outdoor,
        },
        mounted() {
            this.get_outdoor()
        },
        methods: {
            get_outdoor(){
                axios
                .get('../api/article/outdoor/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.outdoor = response.data
                })
                .catch(error =>{
                })
            },
        }
    }
</script>