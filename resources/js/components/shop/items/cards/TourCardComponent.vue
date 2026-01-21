<template>
    <div class="col-md-4 col-sm-12">
      <div class="tours">
          <article class="tourcard" role="article">
              <figure>
                  <div class="tourpic">
                      <shop-img v-if="tour_data_prop.image && tour_data_prop.image.length > 0" :src="'/public/images/tour_img/'+tour_data_prop.image" :alt="'Tour image for ' + tour_data_prop.locale_data.title" />
                      <shop-img v-else :src="'/public/images/site_img/demo_imgs/tour_demo.jpg'" :alt="'Demo image for ' + tour_data_prop.locale_data.title" />

                      <span class="tourday hot" v-if="tour_data_prop.global_data.min_price" aria-label="Price starting from">{{ tour_data_prop.global_data.min_price }} / par</span>
                  </div>

                  <router-link :to="'../tour/'+tour_data_prop.global_data.url_title" aria-label="View tour details">
                    <figcaption class="actyve_panel">
                        <h3 class="entry-title">
                          <router-link :to="'../tour/'+tour_data_prop.global_data.url_title" aria-label="Tour title">{{ tour_data_prop.locale_data.title }}</router-link>
                        </h3>
                        <div class="description" v-if="tour_data_prop.locale_data.short_description" v-html="tour_data_prop.locale_data.short_description"></div>

                        <div class="tour-details">
                          <span class="tourprice location" v-if="tour_data_prop.locale_data.location">
                            <i class="fa fa-map-marker" aria-hidden="true"></i> {{ tour_data_prop.locale_data.location }}
                          </span>
                          <span class="tourprice duration" v-if="tour_data_prop.locale_data.duration">
                            <i class="fa fa-clock-o" aria-hidden="true"></i> {{ tour_data_prop.locale_data.duration }}
                          </span>
                        </div>
                    </figcaption>
                  </router-link>

                  <div class="tourfooter">
                    <div class="tour">
                      {{ tour_data_prop.locale_data.title }}
                    </div>
                    <div class="tourbtn">
                        <router-link class="btn" :to="'../tour/'+tour_data_prop.global_data.url_title" aria-label="Learn more about this tour">
                            <span>{{ $t('shop.tour.more') }}</span>
                        </router-link>
                    </div>
                  </div>
              </figure>
          </article>
      </div>
  </div>
</template>

<script>
export default {
    components: {
        // lingallery,
    },
    props:[
        'tour_data_prop',
    ],
    data: function () {
        return {
            // image_num: 0,
            // image_length: 0,
        };
    },
    mounted() {
        //
    },
    methods: {
      //
    }
}
</script>

<style>
a {
    color: #363636;
    text-decoration: none;
}

.btn {
    /* // background-color: #787878de; */
    /* // background-color: #8b8b8bc2; */
    background-color : rgb(160 158 158 / 87%) !important;
    background: transparent;
    margin: 5px;
    border: 1px solid #eaeaea;
    border-radius: 5px;
    padding: 10px 15px;
    font-size: 1em;
    font-weight: bold;
    text-align: center;
    transition: all 0.3s ease;
}

.btn:hover {
    background: #dddddd;
    color: black;
    transform: translateY(-2px);
}

.tours {
  display: flex;
  padding: 0;
  flex-flow: row wrap;
  justify-content: center;
}

.tours > .tourcard {
  -webkit-transition: all .3s ease-in-out;
  -moz-transition: all .3s ease-in-out;
  -o-transition: all .3s ease-in-out;
  transition: all .3s ease-in-out;
  background: #fff;
  box-shadow: 0 2px 8px rgba(0,0,0,.1);
  flex: 0 1 100%;
  /* // margin: 20px; */
  padding: 0;
  border-radius: 8px;
  overflow: hidden;
}

.tours > .tourcard:hover {
  background: #fff;
  box-shadow: 0 4px 16px rgba(0,0,0,.2);
  cursor: pointer;
  transform: translateY(-5px);
}

.tours > .tourcard figure {
    margin: 0;
    padding: 0;
  position: relative;
  flex: 1;
  display: flex;
  flex-flow: column nowrap;
  justify-content: space-between;
  width: 100%;
  height: 100%;
}

.tourpic {
  background: #444;
  position: relative;
  height: 100%;
  min-height: 9em;
  overflow: hidden;
}

.tourbtn {
    margin: 0;
    padding: 10px;
    text-align: center;
}

.tourbtn:after {
    clear: both;
}

.tours > .tourcard figure .tourpic img {
  -webkit-transition: all .3s ease-in-out;
  -moz-transition: all .3s ease-in-out;
  -o-transition: all .3s ease-in-out;
  transition: all .3s ease-in-out;
  width: 100%;
  height: 100%;
  object-fit: cover;
  margin: 0;
  padding: 0;
  opacity: 1;
}

.tours > .tourcard:hover figure .tourpic img {
    opacity: 0.3;
    transform: scale(1.05);
}

.tours > .tourcard figure .tourpic .tourday {
  text-transform: uppercase;
  color: #fff;
  font-size: 0.9em;
  position: absolute;
  left: 15px;
  bottom: 15px;
  padding: 5px 10px;
  border-radius: 4px;
  font-weight: bold;
}

.tours > .tourcard figure .tourpic .tourday.hot {
  /* // background: linear-gradient(90deg, red, orange); */
  background: linear-gradient(90deg, #09bb80, #09bb808c);
  background-size: 200% 200%;
  animation: hot 10s ease infinite;
}

@keyframes hot {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

.tours > .tourcard figure .tourbtn {
  background: transparent;
  display: flex;
  width: 100%;
  justify-content: center;
}

.tours > .tourcard figure .tourbtn a {
  flex: 0 1 auto;
}

.tours > .tourcard figure figcaption {
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  right: 0;
  padding: 20px;
  margin: 0;
  display: flex;
  flex-flow: column nowrap;
  color: white;
  background: rgb(0 0 0 / 86%);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.tours > .tourcard:hover figure figcaption {
  opacity: 1;
}

figcaption h3 {
    margin-bottom: 10px;
}

figcaption h3 a {
    text-decoration: none;
    color: white;
    background-color: rgba(83, 83, 83, 0.8);
    border-radius: 4px;
    display: inline-block;
    font-size: 1.2em;
    font-weight: bold;
}

.description {
    margin-bottom: 15px;
    font-size: 0.9em;
    line-height: 1.4;
    opacity: 1;
    display: block;
    transition: all 0.3s ease;
}

.tour-details {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.tourprice {
    font-size: 0.9em;
    font-weight: 300;
    opacity: 1;
    display: flex;
    align-items: center;
    gap: 5px;
}

.tourprice i {
    font-size: 1em;
}

.location, .duration {
    opacity: 1;
    display: flex;
}

.tourcard:hover h3 a {
  background-color: transparent;
}

.actyve_panel:hover {
    transition: all 0.5s;
    background-color: rgba(0,0,0,0.8);
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .tours > .tourcard {
        margin: 10px;
    }

    .tours > .tourcard figure {
        height: 250px;
    }

    .tourpic {
        height: 150px;
    }

    .tours > .tourcard figure figcaption {
        padding: 15px;
    }

    figcaption h3 a {
        font-size: 1em;
    }

    .description {
        font-size: 0.8em;
    }

    .tourprice {
        font-size: 0.8em;
    }

    .btn {
        padding: 8px 12px;
        font-size: 0.9em;
    }
}
</style>
