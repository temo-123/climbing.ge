<template>
    <div class="row">
        <left-menu />

        <div class="col-sm-12">

            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>

            <div class="favorites_header">
                <div class="favorites_header_title">
                    <i class="fa fa-heart favorites_header_icon"></i>
                    <h3>{{ $t('user.favorites.films.title') }}</h3>
                </div>
                <div class="favorites_header_actions">
                    <button type="button" class="btn_icon" :class="{ 'is_spinning': is_loading }" @click="get_faworite_films()" :title="$t('common.refresh')">
                        <i class="fa fa-refresh"></i>
                    </button>
                    <button class="btn btn-primary" @click="go_to_film_list('/film')">
                        {{ $t('user.favorites.films.go_to_list') }}
                    </button>
                </div>
            </div>

            <div v-if="is_loading" class="favorites_loading">
                <div class="spinner-border" role="status"></div>
            </div>

            <div class="favorites_grid" v-else-if="films.length">
                <transition-group name="favorite_card_fade" tag="div" class="favorites_grid_inner">
                    <div class="favorite_card" v-for="film in films" :key="film.global_film.id">
                        <button
                            type="button"
                            class="favorite_card_remove"
                            @click="del_from_faworites(film.global_film.id)"
                            :title="$t('user.favorites.films.remove_tooltip')"
                        >
                            <i class="fa fa-times"></i>
                        </button>

                        <div class="favorite_card_img" @click="go_to_film_list('/film/'+film.global_film.url_title)">
                            <site-img v-if="film.global_film.image != null" :src="'/public/images/film_img/'+film.global_film.image" :alt='film.local_film.name'/>
                            <site-img v-else :src="'/public/images/site_img/image.png'" :alt='film.local_film.name'/>
                        </div>

                        <div class="favorite_card_body">
                            <h5 class="favorite_card_title cursor_pointer" @click="go_to_film_list('/film/'+film.global_film.url_title)">
                                {{ film.local_film.name }}
                            </h5>
                            <a class="favorite_card_link" @click="go_to_film_list('/film/'+film.global_film.url_title)">
                                {{ $t('user.favorites.films.view_film') }} <i class="fa fa-angle-right"></i>
                            </a>
                        </div>
                    </div>
                </transition-group>
            </div>

            <div class="favorites_empty" v-else>
                <i class="fa fa-film favorites_empty_icon"></i>
                <h2>{{ $t('user.favorites.films.empty') }}</h2>
                <p>{{ $t('user.favorites.films.empty_hint') }}</p>
                <button class="btn btn-primary" @click="go_to_film_list('/film')">
                    {{ $t('user.favorites.films.go_to_list') }}
                </button>
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
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
                films: [],
                is_loading: true,
            };
        },
        mounted() {
            this.get_faworite_films()
        },
        methods: {
            get_faworite_films(){
                this.is_loading = true
                axios
                .get('/get_film/get_faworite_film_list')
                .then(response => {
                    this.films = response.data
                })
                .catch(error => {
                    this.$bus.$emit('toast', { type: 'danger', message: this.$t('user.favorites.error') })
                })
                .finally(() => {
                    this.is_loading = false
                })
            },

            go_to_film_list(page){
                window.open(this.MIX_APP_SSH + 'films.' + this.MIX_SITE_URL + page)
            },

            del_from_faworites(id){
                if(confirm(this.$t('user.favorites.films.confirm_delete'))){
                    axios
                    .post('/get_film/del_from_faworite/'+id, {
                        film_id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_faworite_films()
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
</style>
