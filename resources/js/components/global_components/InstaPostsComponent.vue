<template>
  <div class="insta-section" v-if="feedId">
    <div class="container">
      <h2 class="index_h2">Instagram</h2>
      <div class="bar"><i class="fa fa-instagram"></i></div>

      <div class="insta-tabs" v-if="tags.length > 1">
        <button
          v-for="tag in tags"
          :key="tag"
          class="insta-tab"
          :class="{ active: activeTag === tag }"
          @click="activeTag = tag"
        >
          #{{ tag }}
        </button>
      </div>

      <!-- Behold widget — injected programmatically to avoid Vue component resolution -->
      <div ref="widgetContainer"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'InstaPostsComponent',

  data() {
    const raw = process.env.MIX_INSTAGRAM_HASHTAGS || '';
    const tags = raw.split(',').map(t => t.trim()).filter(Boolean);
    return {
      feedId: process.env.MIX_INSTAGRAM_FEED_ID || '',
      tags,
      activeTag: tags[0] || '',
    };
  },

  mounted() {
    console.log('[InstaPost] feedId:', this.feedId);
    if (!this.feedId) return;

    if (!window.customElements.get('behold-widget')) {
      const script = document.createElement('script');
      script.type = 'module';
      script.src = 'https://w.behold.so/widget.js';
      document.head.appendChild(script);
    }

    const widget = document.createElement('behold-widget');
    widget.setAttribute('feed-id', this.feedId);
    this.$refs.widgetContainer.appendChild(widget);
  },
};
</script>

<style scoped>
.insta-section {
  padding: 60px 0;
}

.insta-tabs {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  justify-content: center;
  margin-bottom: 24px;
}

.insta-tab {
  padding: 6px 18px;
  border: 2px solid #c13584;
  background: transparent;
  color: #c13584;
  border-radius: 20px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 0.9rem;
}

.insta-tab.active,
.insta-tab:hover {
  background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
  border-color: transparent;
  color: #fff;
}
</style>
