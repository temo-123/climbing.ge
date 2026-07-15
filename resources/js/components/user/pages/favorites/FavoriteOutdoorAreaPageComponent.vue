<template>
    <div class="row">
        <left-menu />

        <div class="col-sm-12">

            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>

            <div class="tabs">
                <div class="row">
                    <div class="col-md-6">
                        <input type="radio" id="1" :value="1" v-model="tab_num" />

                        <label for="1">{{ $t('user.favorites.outdoor.tab_areas') }}</label>
                    </div>
                    <div class="col-md-6">
                        <input type="radio" id="2" :value="2" v-model="tab_num" />

                        <label for="2">{{ $t('user.favorites.outdoor.tab_reviews') }}</label>
                    </div>
                </div>

                <div class="row" v-if="tab_num == 1">
                    <div class="col-md-12">
                        <div class="favorites_header">
                            <div class="favorites_header_title">
                                <i class="fa fa-heart favorites_header_icon"></i>
                                <h3>{{ $t('user.favorites.outdoor.title') }}</h3>
                            </div>
                            <div class="favorites_header_actions">
                                <button type="button" class="btn_icon" :class="{ 'is_spinning': is_loading }" @click="get_faworite_outdoor_region()" :title="$t('common.refresh')">
                                    <i class="fa fa-refresh"></i>
                                </button>
                                <button class="btn btn-primary" @click="go_to_spots_list('/outdoor')">
                                    {{ $t('user.favorites.outdoor.go_to_list') }}
                                </button>
                            </div>
                        </div>

                        <div v-if="is_loading" class="favorites_loading">
                            <div class="spinner-border" role="status"></div>
                        </div>

                        <div class="favorites_grid" v-else-if="spots.length">
                            <transition-group name="favorite_card_fade" tag="div" class="favorites_grid_inner">
                                <div class="favorite_card" v-for="spot in spots" :key="spot.id">
                                    <button
                                        type="button"
                                        class="favorite_card_remove"
                                        @click="del_from_faworites(spot.global_data.id)"
                                        :title="$t('user.favorites.outdoor.remove_tooltip')"
                                    >
                                        <i class="fa fa-times"></i>
                                    </button>

                                    <div class="favorite_card_img" @click="go_to_spots_list('/outdoor/'+spot.global_data.url_title)">
                                        <site-img v-if="spot.global_data.image != null" :src="'/public/images/outdoor_img/'+spot.global_data.image" :img_class="'cursor_pointer'" :alt='spot.locale_data.title'/>
                                        <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'cursor_pointer'" :alt='spot.locale_data.title'/>
                                    </div>

                                    <div class="favorite_card_body">
                                        <h5 class="favorite_card_title cursor_pointer" @click="go_to_spots_list('/outdoor/'+spot.global_data.url_title)">
                                            {{ spot.locale_data.title }}
                                        </h5>
                                        <a class="favorite_card_link" @click="go_to_spots_list('/outdoor/'+spot.global_data.url_title)">
                                            {{ $t('user.favorites.outdoor.view_spot') }} <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </transition-group>
                        </div>

                        <div class="favorites_empty" v-else>
                            <i class="fa fa-map-marker favorites_empty_icon"></i>
                            <h2>{{ $t('user.favorites.outdoor.empty') }}</h2>
                            <p>{{ $t('user.favorites.outdoor.empty_hint') }}</p>
                            <button class="btn btn-primary" @click="go_to_spots_list('/outdoor')">
                                {{ $t('user.favorites.outdoor.go_to_list') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row" v-if="tab_num == 2">
                    <div class="col-md-12">
                        <div class="favorites_header">
                            <div class="favorites_header_title">
                                <i class="fa fa-star favorites_header_icon"></i>
                                <h3>{{ $t('user.favorites.outdoor.reviews_title') }}</h3>
                            </div>
                            <div class="favorites_header_actions">
                                <button type="button" class="btn_icon" :class="{ 'is_spinning': is_reviews_loading }" @click="get_user_sport_routes_review()" :title="$t('common.refresh')">
                                    <i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>

                        <div v-if="is_reviews_loading" class="favorites_loading">
                            <div class="spinner-border" role="status"></div>
                        </div>

                        <div class="table-responsive" v-else-if="reviews.length">
                            <table class="table table-hover favorites_reviews_table">
                                <thead>
                                    <tr>
                                        <th>{{ $t('common.id') }}</th>
                                        <th>{{ $t('user.favorites.outdoor.col_route') }}</th>
                                        <th>{{ $t('user.favorites.outdoor.col_rating') }}</th>
                                        <th class="text-right">{{ $t('common.delete') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="review in reviews" :key="review.route.id">
                                        <td>{{ review.review.id }}</td>
                                        <td>{{ review.route.name }}</td>
                                        <td>
                                            <span class="favorites_stars">
                                                <i
                                                    v-for="star in 5"
                                                    :key="star"
                                                    class="fa"
                                                    :class="star <= review.review.stars ? 'fa-star' : 'fa-star-o'"
                                                ></i>
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <button type="button" class="btn_icon btn_icon_danger" @click="del_route_review(review.review.id)" :title="$t('common.delete')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="favorites_empty" v-else>
                            <i class="fa fa-star-o favorites_empty_icon"></i>
                            <h2>{{ $t('user.favorites.outdoor.reviews_empty') }}</h2>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    export default {
        components: {
            breadcrumb,
        },
        data: function () {
            return {
                spots: [],
                reviews: [],
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
                tab_num: 1,
                is_loading: true,
                is_reviews_loading: true,
            };
        },
        mounted() {
            this.get_faworite_outdoor_region()
        },
        methods: {
            get_faworite_outdoor_region(){
                this.is_loading = true
                axios
                .get('/get_faworite/get_faworite_outdoor_region')
                .then(response => {
                    this.spots = response.data
                    this.get_user_sport_routes_review()
                })
                .catch(error => {
                    this.$bus.$emit('toast', { type: 'danger', message: this.$t('user.favorites.error') })
                })
                .finally(() => {
                    this.is_loading = false
                })
            },
            get_user_sport_routes_review(){
                this.is_reviews_loading = true
                axios
                .get('/get_route/get_route_review/get_user_review')
                .then(response => {
                    this.reviews = response.data
                })
                .catch(error => {
                    this.$bus.$emit('toast', { type: 'danger', message: this.$t('user.favorites.error') })
                })
                .finally(() => {
                    this.is_reviews_loading = false
                })
            },

            go_to_spots_list(page){
                window.open(this.MIX_APP_SSH + '' + this.MIX_SITE_URL + page)
            },

            del_from_faworites(id){
                if(confirm(this.$t('user.favorites.outdoor.confirm_delete'))){
                    axios
                    .post('/set_faworite/del_favorite_outdoor_area/'+id, {
                        article_id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_faworite_outdoor_region()
                    })
                    .catch(error => {
                        this.$bus.$emit('toast', { type: 'danger', message: this.$t('user.favorites.error') })
                    })
                }
            },

            del_route_review(id){
                if(confirm(this.$t('user.favorites.outdoor.confirm_delete_review'))){
                    axios
                    .post('/set_route/set_route_review/del_route_review/'+id, {
                        review_id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_user_sport_routes_review()
                    })
                    .catch(error => {
                        this.$bus.$emit('toast', { type: 'danger', message: this.$t('user.favorites.error') })
                    })
                }
            }
        }
    }
</script>

<style scoped>
.favorites_header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin: 20px 0 24px;
}
.favorites_header_title {
    display: flex;
    align-items: center;
    gap: 10px;
}
.favorites_header_title h3 {
    margin: 0;
}
.favorites_header_icon {
    color: #279fbb;
    font-size: 1.3rem;
}
.favorites_header_actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn_icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: 1px solid #e9e9e9;
    background: #fff;
    color: #606060;
    cursor: pointer;
    transition: background 0.15s ease, color 0.15s ease, border-color 0.15s ease;
}
.btn_icon:hover {
    background: #279fbb;
    border-color: #279fbb;
    color: #fff;
}
.btn_icon.is_spinning i {
    animation: favorites_spin 0.8s linear infinite;
}
.btn_icon_danger:hover {
    background: #e3342f;
    border-color: #e3342f;
}
@keyframes favorites_spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.favorites_loading {
    display: flex;
    justify-content: center;
    padding: 60px 0;
    color: #279fbb;
}

.favorites_grid_inner {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
    gap: 20px;
}

.favorite_card {
    position: relative;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}
.favorite_card:hover {
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    transform: translateY(-3px);
}
.favorite_card_fade-move,
.favorite_card_fade-enter-active,
.favorite_card_fade-leave-active {
    transition: all 0.25s ease;
}
.favorite_card_fade-enter-from,
.favorite_card_fade-leave-to {
    opacity: 0;
    transform: scale(0.95);
}
.favorite_card_fade-leave-active {
    position: absolute;
}

.favorite_card_img {
    position: relative;
    width: 100%;
    height: 160px;
    overflow: hidden;
    cursor: pointer;
    background: #f4f4f4;
}
.favorite_card_img :deep(img) {
    width: 100%;
    height: 160px;
    object-fit: cover;
    display: block;
}

.favorite_card_remove {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 2;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: none;
    background: rgba(0, 0, 0, 0.55);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.15s ease;
}
.favorite_card_remove:hover {
    background: #e3342f;
}

.favorite_card_body {
    padding: 14px 16px 16px;
}
.favorite_card_title {
    margin: 0 0 8px;
    font-size: 1.05rem;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}
.favorite_card_link {
    font-size: 0.85rem;
    color: #279fbb;
    cursor: pointer;
}
.favorite_card_link:hover {
    color: #1e8da8;
    text-decoration: underline;
}

.favorites_empty {
    text-align: center;
    padding: 60px 20px;
    color: #606060;
}
.favorites_empty_icon {
    font-size: 2.5rem;
    color: #ccc;
    margin-bottom: 14px;
    display: block;
}
.favorites_empty h2 {
    font-size: 1.3rem;
    margin-bottom: 6px;
}
.favorites_empty p {
    margin-bottom: 18px;
    color: #888;
}

.favorites_reviews_table th {
    font-weight: 600;
    color: #606060;
}
.favorites_stars {
    color: #ffc60b;
}
.text-right {
    text-align: right;
}
</style>
