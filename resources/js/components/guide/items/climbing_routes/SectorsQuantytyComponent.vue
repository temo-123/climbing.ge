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
            <h3 class="article_list_short_description" v-if="shortText">
                {{ shortText }}
            </h3>
            <p class="article_list_short_description" v-if="extendedText">
                {{ extendedText }}
            </p>
        </div>
    </div>
</template>

<script>
    import { ContentLoader } from 'vue-content-loader'
    export default {
        data: function () {
            return {
                category_counts: {},
                sector_quantyt_loader: true
            };
        },
        mounted() {
            this.get_routes_and_sectors_quntyty()
        },
        components: {
            ContentLoader
        },

        props: {
            route_categories_prop: {
                type: Array,
                default: () => [
                    { value: 'sport', label: 'guide.sector.sport_climbing' },
                    { value: 'boulder', label: 'guide.sector.bouldering' },
                    { value: 'dry', label: 'guide.sector.dry' },
                    { value: 'ice', label: 'guide.sector.ice' },
                    { value: 'mtp', label: 'guide.sector.mtp' }
                ]
            }
        },
        
        computed: {
            shortText() {
                const categories = this.route_categories_prop.map(cat => cat.value);
                const counts = this.category_counts;
                
                const parts = [];
                let totalRoutes = 0;
                let totalSectors = 0;
                
                categories.forEach(category => {
                    const data = counts[category];
                    if (!data) return;
                    
                    const sectorCount = data.sectors || 0;
                    let routeCount = 0;
                    let routeLabel = '';
                    
                    switch(category) {
                        case 'sport':
                            routeCount = data.sport_routes || 0;
                            routeLabel = this.$t('guide.sector.sport_climbing');
                            break;
                        case 'boulder':
                            routeCount = data.boulder_routes || 0;
                            routeLabel = this.$t('guide.sector.bouldering');
                            break;
                        case 'ice':
                            routeCount = data.ice_routes || 0;
                            routeLabel = this.$t('guide.sector.ice');
                            break;
                        case 'dry':
                            routeCount = data.dry_routes || 0;
                            routeLabel = this.$t('guide.sector.dry');
                            break;
                        case 'mtp':
                            routeCount = data.mtps || 0;
                            routeLabel = this.$t('guide.sector.mtp');
                            break;
                    }
                    
                    if (routeCount > 0 || sectorCount > 0) {
                        totalRoutes += routeCount;
                        totalSectors += sectorCount;
                        
                        parts.push({
                            sectors: sectorCount,
                            label: routeLabel,
                            routes: routeCount
                        });
                    }
                });
                
                if (parts.length === 0) return '';
                
                const categoriesList = parts.map(p => p.label).join(' ' + this.$t('and') + ' ');
                const sectorWord = totalSectors === 1 
                    ? this.$t('guide.sector.sector') 
                    : this.$t('guide.sector.sectors');
                const routeWord = totalRoutes === 1 
                    ? this.$t('guide.sector.route') 
                    : this.$t('guide.sector.routes');
                
                if (parts.length === 1) {
                    const p = parts[0];
                    const singleSectorWord = p.sectors === 1 
                        ? this.$t('guide.sector.sector') 
                        : this.$t('guide.sector.sectors');
                    const singleRouteWord = p.routes === 1 
                        ? this.$t('guide.sector.route') 
                        : this.$t('guide.sector.routes');
                    return this.$t('guide.article.routes_short_line_single', {
                        sectors: p.sectors,
                        label: p.label,
                        sector_word: singleSectorWord,
                        routes: p.routes,
                        route_word: singleRouteWord
                    });
                }
                
                return this.$t('guide.article.routes_short_line_multiple', {
                    totalSectors: totalSectors,
                    sector_word: sectorWord,
                    totalRoutes: totalRoutes,
                    categories: categoriesList
                });
            },
            
            extendedText() {
                const categories = this.route_categories_prop.map(cat => cat.value);
                const counts = this.category_counts;
                
                const parts = [];
                
                categories.forEach(category => {
                    const data = counts[category];
                    if (!data) return;
                    
                    const sectorCount = data.sectors || 0;
                    let routeCount = 0;
                    let routeLabel = '';
                    
                    switch(category) {
                        case 'sport':
                            routeCount = data.sport_routes || 0;
                            routeLabel = this.$t('guide.sector.sport_climbing');
                            break;
                        case 'boulder':
                            routeCount = data.boulder_routes || 0;
                            routeLabel = this.$t('guide.sector.bouldering');
                            break;
                        case 'ice':
                            routeCount = data.ice_routes || 0;
                            routeLabel = this.$t('guide.sector.ice');
                            break;
                        case 'dry':
                            routeCount = data.dry_routes || 0;
                            routeLabel = this.$t('guide.sector.dry');
                            break;
                        case 'mtp':
                            routeCount = data.mtps || 0;
                            routeLabel = this.$t('guide.sector.mtp');
                            break;
                    }
                    
                    if (routeCount > 0 || sectorCount > 0) {
                        const routeWord = routeCount === 1 
                            ? this.$t('guide.sector.route') 
                            : this.$t('guide.sector.routes');
                        const areaWord = sectorCount === 1 
                            ? this.$t('guide.sector.sector') 
                            : this.$t('guide.sector.sectors');
                        parts.push(this.$t('guide.article.routes_extended_detail', {
                            routes: routeCount,
                            label: routeLabel,
                            route_word: routeWord,
                            sectors: sectorCount,
                            area_word: areaWord
                        }));
                    }
                });
                
                if (parts.length === 0) return '';
                
                const intro = this.$t('guide.article.routes_extended_intro');
                const outro = this.$t('guide.article.routes_extended_outro');
                
                return intro + ' ' + parts.join('. ') + '. ' + outro;
            }
        },
        
        methods: {
            get_routes_and_sectors_quntyty(){
                axios
                    .post('/get_sector/sectors_and_routes_quantity_by_categories/', {
                        route_categories: this.route_categories_prop.map(cat => cat.value)
                    })
                    .then(response => {
                        this.category_counts = response.data.categories || {};
                    })
                    .catch(error =>{
                    })
                    .finally(() => this.sector_quantyt_loader = false)
            },
        }
    }
</script>

