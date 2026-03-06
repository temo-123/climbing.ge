<template>
    <!-- <div class=""> -->
        <div :class="'mb-5 col-xs-6 col-sm-6 col-md-4'" >
            <div class="card h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 film_cover_img">
                            <router-link :to="'../film/'+film.global_film.url_title">
                                <site-img
                                    v-if="!film.global_film.image"
                                    :src="'../../../public/images/site_img/film poster demo.jpg'"
                                    :img_class="'img-fluid rounded mb-4 mb-lg-0 float-right'"
                                    :alt="film.us_film.name"
                                />
                                <site-img
                                    v-else
                                    :src="'../../../public/images/film_img/' + film.global_film.image"
                                    :img_class="'img-fluid rounded mb-4 mb-lg-0 float-right'"
                                    :alt="film.us_film.name"
                                />
                            </router-link>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <router-link :to="'../film/'+film.global_film.url_title">
                                <h2 class="card-title">{{ film.us_film.name }}</h2>
                            </router-link>

                            <p class="card-text">{{  film.us_film.short_description }}</p>
                        </div>
                    </div>
                </div>
                <!-- <div class="card-footer">
                    <router-link class="btn btn-primary btn-sm" :to="'film/'+film.url_title">
                        Open
                    </router-link>
                </div> -->
            </div>
        </div>
        <!-- <div class="clearfix"></div> -->
    <!-- </div> -->
</template>

<script>
export default {
    props: [
        'film',
        // 'col'
    ],
}
</script>

<style>
.film_cover_img img{
    width: 100%;
}
</style>