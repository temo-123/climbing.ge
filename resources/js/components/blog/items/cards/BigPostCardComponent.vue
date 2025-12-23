<template>
  <section class="m-component-stack mm-component-stack--is-stacked">
    <div class="m-image-hero mm-image-hero--ssr" v-if="items && items.length > 0">
      <div v-for="(item, index) in items" :key="index" class="big-post-item">
        <router-link :to="item.type === 'news' ? '/news/' + item.data.global_data.url_title : '/post/' + item.data.global_data.url_title">
          <div class="m-image-hero--media">
            <div>
              <div class="is-loaded">
                <img v-if="item.data.global_data.image" :src="'/public/images/' + (item.type === 'news' ? 'news_img' : 'blog_img') + '/' + item.data.global_data.image" class="m-image-hero--image" :alt="item.data.locale_data.title" />
                <img v-else src="/public/images/site_img/image.png" class="m-image-hero--image" :alt="item.data.locale_data.title" />
              </div>
              <div class="m-image-hero--aspect-ratio-wrapper">
                <div class="m-image-hero--aspect-ratio-pusher"></div>
              </div>
            </div>
            <div class="m-image-hero--container">
              <div class="m-image-hero--label mm-image-hero--no-background">
                <div class="m-image-hero--metadata-label">
                  <div class="news_flag" v-if="item.type === 'news'">{{ $t('guide.news_flag') }}</div>
                  <div class="post_flag" v-else>{{ $t('blog.labels.post') }}</div>
                </div>
              </div>
              <div class="m-image-hero--text-panel">
                <router-link :to="item.type === 'news' ? '/news/' + item.data.global_data.url_title : '/post/' + item.data.global_data.url_title">
                  <div>
                    <h2 class="m-ellipsis m-image-hero--header-text mm-image-hero--no-background">
                      <span class="m-ellipsis--text">{{ item.data.locale_data.title }}</span>
                    </h2>
                    <span class="big_post_text text_shadow" v-html="item.data.locale_data.short_description || item.data.locale_data.description"></span>
                  </div>
                </router-link>
              </div>
            </div>
          </div>
        </router-link>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  props: [
    'items',
  ],
  data: function () {
    return {
    };
  },
  components: {
  },
  mounted() {
    // console.log('BigPostCardComponent mounted with items:', this.items);
  },
  methods: {
  }
}
</script>

<style scoped>
.big_post_text {
  color: #ffffffb5;
  font-size: 1.3em;
}

@media (max-width: 767px) {
  .big_post_text {
    text-shadow: #abababb5 1px 1px 0;
    display: none;
  }
}

.big-post-item {
  margin-bottom: 20px;
}

.big-post-item:last-child {
  margin-bottom: 0;
}

.news_flag {
  background-color: #007bff;
  color: white;
  padding: 5px 10px;
  border-radius: 3px;
  font-size: 20px;
  font-weight: bold;
  display: inline-block;
}

.post_flag {
  background-color: #28a745;
  color: white;
  padding: 5px 10px;
  border-radius: 3px;
  font-size: 20px;
  font-weight: bold;
  display: inline-block;
}
</style>
