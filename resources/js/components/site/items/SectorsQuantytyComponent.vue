<template>
    <div class="row">
        <div  v-if="sector_quantyt_loader">
            <content-loader
                :width="100"
                :height="7"
                primaryColor="#f3f3f3"
                secondaryColor="#279fbbb0"
            >
                <rect x="0" y="0" rx="2" ry="2" width="100%" height="6" />
            </content-loader>
        </div>
        <div v-else>
            <i18n>
                <p placeholder="sectors">666</p>
            </i18n>
            <h3 v-if='sectors'>
                {{ 
                    $t('guide.article.route_quantyty', { 
                        sectors: sectors,
                        sport_routes: sport_routes,
                        boulder_routes: boulder_routes,
                        mtps: mtps,
                    }) 
                }}
                <!-- In Georgia are  
                    <strong>{{sectors}}</strong> outdoor climbing sectors, 
                    <strong>{{sport_routes}}</strong> sport climbing routes, 
                    <strong>{{boulder_routes}}</strong> boulder routes, 
                    <strong>{{mtps}}</strong> multy pitch. You can see all outdoor climbing arias info on this page. -->
            </h3>
        </div>
    </div>
</template>

<script>
    import { ContentLoader } from 'vue-content-loader'
    export default {
        data: function () {
            return {
                sectors: 0,
                sport_routes: 0,
                boulder_routes: 0,
                mtps: 0,
                sector_quantyt_loader: true
            };
        },
        mounted() {
            this.get_routes_and_sectors_quntyty()
        },
        components: {
            ContentLoader
        },
        methods: {
            get_routes_and_sectors_quntyty(){
                this.sectors = 0
                this.sport_routes = 0
                this.boulder_routes = 0
                this.mtps = 0

                axios
                    .get('../api/sectors_and_routes_quantity')
                    .then(response => {
                        this.sectors = response.data.sectors
                        this.sport_routes = response.data.sport_routes
                        this.boulder_routes = response.data.boulder_routes
                        this.mtps = response.data.mtps
                    })
                    .catch(error =>{
                    })
                    .finally(() => this.sector_quantyt_loader = false)
            },
        }
    }
</script>