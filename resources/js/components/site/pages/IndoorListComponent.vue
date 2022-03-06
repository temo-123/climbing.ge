<template>
    <div class="h-recent-work">
        <div class="container">

            <h1 class="page_title index_h2" >{{__ ('title indoor climbing')}}</h1>                    

            <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
            <h6> <span v-html="this.$siteData.indoor_description"></span></h6>

            <div class="row featurette" style='margin-bottom: 7%;'>
                <div v-if="this.indoors.length > 0">
                    <indoorCard 
                        v-for="indoor in indoors"
                        :key='indoor.id'
                        :indoor="indoor"
                    />
                </div>
                <div v-else>
                    <emptyPageComponent />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import indoorCard from '../items/cards/IndoorCardComponent'
    import emptyPageComponent from '../items/EmptyPageComponent'
    
    export default {
        data: function () {
            return {
                indoors: []
            };
        },
        components: {
            indoorCard,
            emptyPageComponent,
        },
        mounted() {
            this.get_indoors()
        },
        methods: {
            get_indoors(){
                axios
                .get('../api/indoor')
                .then(response => {
                    this.indoors = response.data
                })
                .catch(error =>{
                })
            }
        }
    }
</script>

<style>

</style>