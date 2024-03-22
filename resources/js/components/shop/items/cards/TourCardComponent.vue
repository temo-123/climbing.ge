<template>
    <div class="col-md-4 col-sm-12">
      <div class="tours">
          <div class="tourcard">
              <figure>
                  <div class="tourpic">
                      <shop-img width="320" height="180" v-if="tour_data_prop.image.length > 0" :src="'/public/images/tour_img/'+tour_data_prop.image" :alt="tour_data_prop.global_data.url_title" />
                      <shop-img width="320" height="180" v-else :src="'/public/images/site_img/demo_imgs/tour_demo.jpg'" :alt="tour_data_prop.global_data.url_title" />

                      <span class="tourcat" v-if="tour_data_prop.global_data.location">{{ tour_data_prop.global_data.location }}</span>                             
                      <span class="tourday hot" v-if="tour_data_prop.global_data.min_price">{{ tour_data_prop.global_data.min_price }} / par</span>
                  </div>

                  <router-link :to="'../tour/'+tour_data_prop.global_data.url_title">
                    <figcaption class="actyve_panel">
                        <h3 class="entry-title">
                          <router-link :to="'../tour/'+tour_data_prop.global_data.url_title">{{ tour_data_prop.locale_data.title }}</router-link>
                        </h3>
                        <!-- <span class="description">{{ tour_data_prop.locale_data.short_description }}</span> -->
                        <span class="description" v-html="tour_data_prop.locale_data.short_description"></span>
                      <!-- <span class="tourprice"> -->
                        <!-- <span class="currency">Rp. </span><span class="price">200 EUR</span> -->
                        <!-- <span> / par</span> -->
                      <!-- </span> -->
                    </figcaption>
                  </router-link>

                  <div class="tourbtn">
                      <router-link class="btn btn-default123" :to="'../tour/'+tour_data_prop.global_data.url_title">
                          <span class="text-muted">{{ $t('shop.tour.more') }}</span>
                      </router-link>
                  </div>
              </figure>
          </div>
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

<style lang="scss" scoped>
a {
    color: #363636;
}
.btn {
    background: transparent;
    margin: 3px;
    border: 1px solid #eaeaea;
    border-radius: 0px;
}
.btn:hover {
    background: #eaeaea;
    color: black;
}
.tours {
  display: flex;
  padding: 0;
  flex-flow: row wrap;
//   align-content: center;
//   align-items: stretch;
//   justify-content: center;
}
.tours > .tourcard {
  -webkit-transition: all .3s ease-in-out;
  -moz-transition: all .3s ease-in-out;
  -o-transition: all .3s ease-in-out;
  transition: all .3s ease-in-out;
  background: #fff;
  box-shadow: 0 1px 3px rgba(0,0,0,.1);
  flex: 0 1 360px;
  margin: 15px;
  padding: 0;
}
.tours > .tourcard:hover {
  background: #fff;
  box-shadow: 0 1px 5px rgba(0,0,0,.5);
  cursor: pointer;
}
.tours > .tourcard figure {
    margin: 0;
    padding: 0;
  position: relative;
  flex: 1;
  display: flex;
  flex-flow: column nowrap;
  justify-content: space-around;
  width: 100%;
}
.tourpic {
  background: #444;
  position: relative;
}
.tourbtn {
    margin: 0;
    padding: 0;
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
  height: auto;
  margin: 0;
  padding: 0;
  opacity: .50;
}
.tours > .tourcard:hover figure .tourpic img {
    opacity: 0.1;
}
.tours > .tourcard figure .tourpic .tourcat {
  color: #fff;
  font-size: .8em;
  padding: 3px 5px;
  position: absolute;
  right: 15px;
  text-transform: uppercase;
  bottom: 15px;
}
.tours > .tourcard figure .tourpic .tourday {
  text-transform: uppercase;
  color: #fff;
  font-size: .8em;
  position: absolute;
  left: 15px;
  bottom: 15px;
  padding: 3px 5px;
}
.tours > .tourcard figure .tourpic .tourday.hot {
  background: #000;
  background: red;
  background: -webkit-linear-gradient(0deg,red,orange);
  background: linear-gradient(90deg,red,orange);
  background-size: 200% 200%;
  -webkit-animation: hot 10s ease infinite;
  -moz-animation: hot 10s ease infinite;
  -o-animation: hot 10s ease infinite;
  animation: hot 10s ease infinite;
}
.tours > .tourcard figure .tourbtn {
  background: transparent;
  display: flex;
  width: 100%;
  flex-flow: row wrap;
  align-content: center;
}
.tours > .tourcard figure .tourbtn a {
  flex: 1;
}
.tours > .tourcard figure figcaption {
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  right: 0;
  padding: 15px;
  margin: 0 0 44px;
  display: flex;
  flex-flow: column nowrap;
  color: white;
}
figcaption h3 a {
    text-decoration: none;
    color: white;
    background-color: #535353;
}
.description {
    opacity: 0;
    display: none;
}
.tourprice {
    opacity: 0;
    display: none;
    font-size: .8em;
}
.currency {
    font-weight: 300;
}
.price {
    font-size: 2em;
    font-weight: 300;
}
.tourcard:hover figure figcaption .description
// , .tourcard:hover figure figcaption .tourprice 
{   
    display: block;
    opacity: 1;
}

.actyve_panel:hover{
    transition: all 0.5s; 
  background-color: #000000e5;
}
</style>