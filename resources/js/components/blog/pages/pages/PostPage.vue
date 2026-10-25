<template>
  <main role="main" class="container">
    <!-- reading progress, pinned under the header -->
    <div v-if="post" class="cg-read-progress" aria-hidden="true"><span :style="{ transform: 'scaleX(' + progress + ')' }"></span></div>
    <div class="row">
      <div class="col-md-8 blog-main">
        <section class="hero">
          <div class="container">
            <div class="row">
              <div v-if="loading" class="text-center">
                <p>{{ $t('blog.loading.post') }}</p>
              </div>
              <div v-else-if="post">
                <article class="blog-post">
                  <header class="mb-4">
                    <div class="d-flex align-items-start justify-content-between">
                      <h1 class="display-4">{{ post.title }}</h1>
                      <share-button
                        :title="post.title"
                        :text="post.short_description"
                        content-type="blog_post"
                        :item-id="post.id"
                      />
                    </div>
                    <div class="d-flex align-items-center flex-wrap gap-2 text-muted">
                      <small>{{ $t('blog.loading.created', { date: $formatDate(post.created_at) }) }}</small>
                      <small v-if="reading_minutes" class="cg-read-time"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $t('blog.reading_time', { n: reading_minutes }) }}</small>
                    </div>
                  </header>
                  <div class="content" ref="content" v-html="post.content"></div>
                </article>
              </div>
              <div v-else>
                <p>post not found.</p>
              </div>
            </div>
          </div>
        </section>
      </div>

      <!-- desktop table of contents, built from the post's own h2/h3 -->
      <aside v-if="toc.length >= 2" class="col-md-4 d-none d-md-block">
        <nav class="cg-post-toc" :aria-label="$t('global.on_this_page')">
          <strong class="cg-post-toc__title">{{ $t('global.on_this_page') }}</strong>
          <ol>
            <li v-for="item in toc" :key="item.id" :class="['is-' + item.level, { 'is-current': item.id === current }]">
              <a :href="'#' + item.id" @click.prevent="go(item.id)">{{ item.label }}</a>
            </li>
          </ol>
        </nav>
      </aside>
    </div>

    <metaData
        :title="post ? post.title : $t('blog.meta.post')"
        :description="post ? post.short_description : this.$siteData.data.guid_short_description"
        :image="post && post.image ? post.image : '/public/images/meta_img/outdoor.jpg'"
    />
  </main>
</template>

<script>
  import axios from 'axios'
  import metaData from '../../items/MetaDataComponent'

  export default {
    name: 'PostPage',
    components: {
      metaData
    },
    data() {
      return {
        post: null,
        loading: true,
        progress: 0,
        reading_minutes: 0,
        toc: [],
        current: null,
      }
    },
    mounted() {
      this.fetchPost()
      window.addEventListener('scroll', this.on_scroll, { passive: true })
    },
    beforeUnmount() {
      window.removeEventListener('scroll', this.on_scroll)
    },
    methods: {
      fetchPost() {
        // const postId = this.$route.params.id
        axios.get(`/get_post/get_post/${this.$route.params.url_title}`)
          .then(response => {
            this.post = response.data

            if (this.$gtag && this.post?.id) {
              this.$gtag.event('select_content', {
                content_type: 'blog_post',
                item_id: String(this.post.id),
              })
            }
          })
          .catch(error => {
            console.error('Error fetching post:', error)
          })
          .finally(() => {
            this.loading = false
            this.$nextTick(this.prepare_content)
          })
      },
      // ~200 words a minute; headings get ids so the TOC (and "On this page"
      // on phones) can link to them
      prepare_content() {
        const el = this.$refs.content
        if (!el) return
        const words = (el.textContent || '').trim().split(/\s+/).filter(Boolean).length
        this.reading_minutes = Math.max(1, Math.round(words / 200))
        const used = new Set()
        this.toc = Array.from(el.querySelectorAll('h2, h3')).map((h, i) => {
          const label = h.textContent.replace(/\s+/g, ' ').trim()
          if (!label) return null
          if (!h.id) {
            let id = 'part-' + (label.toLowerCase().replace(/[^a-z0-9\u10d0-\u10ff]+/g, '-').replace(/^-|-$/g, '').slice(0, 50) || i)
            while (used.has(id) || document.getElementById(id)) id += '-' + i
            h.id = id
          }
          used.add(h.id)
          return { id: h.id, label, level: h.tagName === 'H2' ? 2 : 3 }
        }).filter(Boolean)
        this.on_scroll()
      },
      on_scroll() {
        const el = this.$refs.content
        if (!el) return
        const rect = el.getBoundingClientRect()
        const total = rect.height - window.innerHeight * 0.6
        this.progress = Math.min(1, Math.max(0, total > 0 ? -rect.top / total : 1))
        let current = null
        this.toc.forEach(t => {
          const h = document.getElementById(t.id)
          if (h && h.getBoundingClientRect().top < 140) current = t.id
        })
        this.current = current
      },
      go(id) {
        const h = document.getElementById(id)
        if (!h) return
        const reduce = window.cgReducedMotion()
        window.scrollTo({ top: h.getBoundingClientRect().top + window.scrollY - 90, behavior: reduce ? 'auto' : 'smooth' })
      },
      formatDate(dateString) {
        const date = new Date(dateString)
        return date.toLocaleDateString('en-US', {
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        })
      }
    }
  }
</script>

<style scoped>
  .cg-read-progress { position: fixed; top: var(--cg-nav-h, 64px); left: 0; right: 0; z-index: 1029; height: 3px; pointer-events: none; }
  .cg-read-progress span { display: block; height: 100%; background: var(--cg-accent); transform-origin: 0 50%; transition: transform .1s linear; }
  .cg-read-time { font-weight: 700; }
  .cg-read-time::before { content: "·"; margin-right: .5rem; }
  .cg-post-toc { position: sticky; top: calc(var(--cg-nav-h, 64px) + 1.5rem); margin-top: 1.5rem; padding: 1rem 1.1rem; border-left: 3px solid var(--cg-accent); background: #fff; border-radius: 0 var(--cg-r) var(--cg-r) 0; box-shadow: var(--cg-shadow-sm); }
  .cg-post-toc__title { display: block; margin-bottom: .5rem; font-size: .75rem; letter-spacing: .1em; text-transform: uppercase; color: var(--cg-muted); }
  .cg-post-toc ol { list-style: none; margin: 0; padding: 0; }
  .cg-post-toc li a { display: block; padding: .3rem 0; color: var(--cg-ink-2); font-weight: 600; font-size: .92rem; text-decoration: none; line-height: 1.3; }
  .cg-post-toc li.is-3 a { padding-left: .9rem; font-size: .85rem; font-weight: 500; }
  .cg-post-toc li a:hover { color: var(--cg-accent-dark); }
  .cg-post-toc li.is-current a { color: var(--cg-accent-dark); font-weight: 800; }
  @media (prefers-reduced-motion: reduce) { .cg-read-progress span { transition: none; } }
</style>
