<template>
  <section class="m-component-stack mm-component-stack--is-stacked">
    
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
