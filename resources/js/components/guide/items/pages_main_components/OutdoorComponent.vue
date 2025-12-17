<template>
    <div>
        <div class="row">
            <div class="col-sm-8 blog-header">

                <h1 class="blog-title">
                    {{ this.article_prop.locale_data.title  }}

                    <span @click="toggle_favorite_status(article_prop.global_data.id)"> 
                        <i :class="is_favorite ? 'fa fa-heart favorite_icon add_to_favorite active' : 'fa fa-heart-o favorite_icon add_to_favorite'"></i> 
                    </span>
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8 blog-header">
                <breadcrumb />

                <p class="blog-post-meta"> {{ this.article_prop.global_data.created_at  }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-8 blog-main">
                
                <articleTextBlocks :article_prop="this.article_prop"/>

                <!-- routes -->
                <div v-if="this.article_prop.locale_data.route || this.article_prop.general_info.routes_info != []">
                    <h2 id="routes">{{ $t('guide.article.title.route')}}</h2>

                    <routeQuanDiogram 
                        :outdoor_region_article_id="this.article_prop.global_data.id"
                        ref="route_quan_diogram"
                    />

                    <generalInfo :global_info_prop="article_prop.general_info.routes_info" :locale_data_prop="article_prop.locale_data.route"/>

                </div>

                <routesTab 
                    :article_id_prop="this.article_prop.global_data.id" 
                    ref="routes_tab"
                />
                
                <galleryComponent 
                    :images_prop="this.article_prop.gallery_images" 
                />
            </div>

                <articleRightMenu :article_id="this.article_prop.global_data.id" />
            
        </div>

        <div class="row"> 
            <commentForm 
                :article_id="this.article_prop.global_data.id" 
                ref="comments"
            />
        </div>

        <SimilarArticles 
            :article_id="this.article_prop.global_data.id" 
            :article_category="this.article_prop.global_data.category" 
            :route="'outdoor/'"
            :img_dir="'outdoor_img/'"

            ref="similar_articles"
        />

    </div> 
</template>

<script>
    import routesTab from '../climbing_routes/SectorAndRoutesTabComponent'
    import commentForm from '../comments/CommentFormComponent'
    import galleryComponent from '../galleries/GalleryComponent'
    import articleRightMenu from '../navbars/RightMenuComponent'
    import SimilarArticles from '../SimilarArticlesComponent'
    import breadcrumb from '../BreadcrumbComponent.vue'
    import articleTextBlocks from '../article/ArticleTextBlocksComponent'

    import routeQuanDiogram from '../climbing_routes/RoutesQuantityComponent.vue'

    import generalInfo from '../article/GeneralInfoComponent'

    export default {
        props: [
            'article_prop',
        ],
        components: {
            commentForm,
            galleryComponent,
            articleRightMenu,
            SimilarArticles,
            routesTab,
            breadcrumb,
            routeQuanDiogram,
            articleTextBlocks,
            generalInfo
        },

        data: function () {
            return {
                posts: [],
                is_favorite: false,

                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
            }
        },


        mounted() {
            this.update_similar_articles_component(this.article_prop.global_data.id)
            this.check_favorite_status(this.article_prop.global_data.id)
            
            // Check for sector and route parameters from navigation
            // Wait for routesTab to finish loading before attempting scroll
            this.waitForRoutesTabLoad();
        },
        

        methods: {
            waitForRoutesTabLoad() {
                const maxWait = 5000; // 5 seconds max
                const checkInterval = 100; // Check every 100ms
                let waited = 0;
                
                const checkRoutesTab = () => {
                    if (this.$refs.routes_tab && 
                        this.$refs.routes_tab.climbing_area && 
                        this.$refs.routes_tab.climbing_area.length > 0) {
                        
                        console.log('RoutesTab loaded, starting navigation...');
                        this.handleRouteNavigation();
                        return;
                    }
                    
                    waited += checkInterval;
                    if (waited >= maxWait) {
                        console.log('RoutesTab load timeout, proceeding anyway...');
                        this.handleRouteNavigation();
                        return;
                    }
                    
                    setTimeout(checkRoutesTab, checkInterval);
                };
                
                checkRoutesTab();
            },

            update_similar_articles_component(id){
                this.$refs.similar_articles.update(id);
                this.$refs.route_quan_diogram.update(id);
                this.$refs.routes_tab.update(id);
                // this.$refs.gallery_component.update(id)
                this.$refs.comments.update(id);
            },

            check_favorite_status(article_id){
                axios
                .get('/set_faworite/check_favorite_status/' + article_id)
                .then(response => {
                    this.is_favorite = response.data.is_favorite;
                })
                .catch(error => {
                    console.log('Error checking favorite status:', error);
                    this.is_favorite = false;
                });
            },


            toggle_favorite_status(article_id){
                if(this.is_favorite) {
                    if(confirm('Are you sure you want to remove this area from your favorites?')){
                        // Remove from favorites
                        axios
                        .delete('/set_faworite/del_faworite_outdoor_region/' + article_id)
                        .then(response => {
                            this.is_favorite = false;
                            // Show success message
                            alert('Area removed from favorites successfully!');
                        })
                        .catch(error => {
                            if(error.response.status === 401) {
                                if(confirm('You are not login. Do you want log in?')){
                                    window.open(this.MIX_APP_SSH + 'user.' + this.MIX_SITE_URL);
                                }
                            }
                            else{
                                alert("Error " + error.response.status);
                            }
                        });
                    }
                } else {
                    // Add to favorites
                    axios
                    .post('/set_faworite/add_to_favorite_outdoor_area/' + article_id)
                    .then(response => {
                        this.is_favorite = true;
                        alert(response.data);
                    })
                    .catch(error => {
                        if(error.response.status === 401) {
                            if(confirm('You are not login. Do you want log in?')){
                                window.open(this.MIX_APP_SSH + 'user.' + this.MIX_SITE_URL);
                            }
                        }
                        else{
                            alert("Error " + error.response.status);
                        }
                    });
                }
            },

            handleRouteNavigation() {
                // Get query parameters
                const sectorId = this.$route.query.sector;
                const routeId = this.$route.query.route;
                
                // console.log('Navigation params:', { sectorId, routeId, fullRoute: this.$route.fullPath });
                // console.log('Route query object:', this.$route.query);
                
                if (sectorId || routeId) {
                    // Wait for the DOM to be fully rendered with multiple attempts
                    this.$nextTick(() => {
                        this.tryScrollToSector(sectorId, routeId, 0);
                    });
                } else {
                    console.log('No sector or route parameters found in URL');
                }
            },

            tryScrollToSector(sectorId, routeId, attempt) {
                const maxAttempts = 10;
                const delay = 300;
                
                if (attempt >= maxAttempts) {
                    console.log('Max scroll attempts reached');
                    return;
                }
                
                // Debug: log all sector elements on the page
                const allSectorElements = document.querySelectorAll('[id^="sector-"]');
                console.log(`Attempt ${attempt + 1}:`, {
                    lookingFor: `sector-${sectorId}`,
                    sectorIdType: typeof sectorId,
                    routeIdType: typeof routeId,
                    routeIdValue: routeId,
                    allSectorElements: Array.from(allSectorElements).map(el => ({
                        id: el.id,
                        textContent: el.textContent.substring(0, 100)
                    }))
                });
                
                // Try to find sector element by ID (convert to string for comparison)
                const sectorElement = document.getElementById(`sector-${sectorId}`);
                // console.log(`Looking for sector-${sectorId}`, sectorElement);
                
                // Debug: find all route elements
                const allRouteElements = document.querySelectorAll('[data-route-id]');
                console.log(`All route elements found:`, Array.from(allRouteElements).map(el => ({
                    routeId: el.getAttribute('data-route-id'),
                    tagName: el.tagName,
                    textContent: el.textContent.substring(0, 50)
                })));

                if (sectorElement) {
                    // Scroll to sector
                    console.log('Found sector, scrolling...');
                    sectorElement.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
                    });
                    
                    // Highlight the sector briefly
                    this.highlightElement(sectorElement);
                    
                    // Also scroll to route if provided
                    if (routeId) {
                        // console.log(`Attempting to highlight route with ID: ${routeId}`);
                        setTimeout(() => {
                            this.highlightRoute(routeId);
                        }, 1000);
                    }
                } else {
                    // console.log(`Sector element not found, trying fallback...`);
                    
                    // Fallback: scroll to sectors section
                    const sectorsSection = document.getElementById('sectors');
                    if (sectorsSection) {
                        // console.log('Found sectors section, scrolling...');
                        sectorsSection.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'start' 
                        });
                        
                        // Show a message that specific sector wasn't found
                        // setTimeout(() => {
                        //     console.log(`Sector with ID ${sectorId} not found. Scrolled to sectors section.`);
                        // }, 1000);
                    } else {
                        // console.log(`Sectors section also not found, retrying in ${delay}ms...`);
                        setTimeout(() => {
                            this.tryScrollToSector(sectorId, routeId, attempt + 1);
                        }, delay);
                    }
                }
            },
            
            highlightElement(element) {
                // Add temporary highlight effect
                const originalBackground = element.style.backgroundColor;
                element.style.backgroundColor = '#fff3cd';
                element.style.transition = 'background-color 0.3s ease';
                
                setTimeout(() => {
                    element.style.backgroundColor = originalBackground;
                }, 2000);
            },

            highlightRoute(routeId) {
                // Try to highlight specific route within sector
                const routeElement = document.querySelector(`[data-route-id="${routeId}"]`);
                if (routeElement) {
                    // Add active state styling to the route
                    routeElement.classList.add('active-route-highlight');
                    this.highlightElement(routeElement);
                    
                    // Remove active class after 5 seconds
                    setTimeout(() => {
                        routeElement.classList.remove('active-route-highlight');
                    }, 5000);
                    
                    console.log('Highlighted route with ID:', routeId);
                } else {
                    console.log('Route element not found with ID:', routeId);
                }
            }
        }
    }
</script>


<style scoped>
.tabs input[type="radio"]:checked + label {
    background: #fff;
    border: 1px solid #ccc !important;
}

.add_to_favorite{
    float: right; 
    cursor: pointer;
}

/* Active favorite heart styling */
.favorite_icon.active {
    color: #e74c3c !important;
    transition: color 0.3s ease;
}

.favorite_icon:hover {
    opacity: 0.8;
    transform: scale(1.1);
    transition: all 0.3s ease;
}

/* Active route highlighting styles */
.active-route-highlight {
    background-color: #fff3cd !important;
    border: 2px solid #ffc107 !important;
    border-radius: 4px !important;
    transition: all 0.3s ease !important;
    position: relative !important;
}

.active-route-highlight td {
    background-color: inherit !important;
    color: #856404 !important;
    font-weight: 600 !important;
}

.active-route-highlight::before {
    content: "🎯" !important;
    position: absolute !important;
    left: -10px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    font-size: 1.2em !important;
    z-index: 10 !important;
}

/* Enhanced sector highlighting */
.active-sector-highlight {
    background-color: #d4edda !important;
    border: 2px solid #28a745 !important;
    border-radius: 8px !important;
    padding: 15px !important;
    margin: 10px 0 !important;
    transition: all 0.3s ease !important;
}

.active-sector-highlight h2 {
    color: #155724 !important;
}
</style>
