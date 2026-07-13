<template>
  <StackModal
    :modelValue="modelValue"
    :title="ascent ? (ascent.name + ' ' + ascent.surname) : ''"
    size="md"
    :saveButton="{ visible: false }"
    :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
    @close="$emit('update:modelValue', false)"
  >
    <div v-if="ascent">

      <!-- Photo -->
      <div v-if="ascent.photo" class="text-center mb-4">
        <img
          :src="'/public/images/summit_ascents_img/' + ascent.photo"
          class="img-fluid rounded"
          style="max-height: 260px; object-fit: cover; width: 100%;"
          alt="Ascent photo"
        />
      </div>

      <table class="table table-sm mb-0">
        <tbody>
          <tr>
            <td class="text-muted" style="width:38%">{{ $t('summit.summit_page.climber') }}</td>
            <td><strong>{{ ascent.name }} {{ ascent.surname }}</strong></td>
          </tr>
          <tr>
            <td class="text-muted">{{ $t('common.date') }}</td>
            <td>{{ formatDate(ascent.ascent_date) }}<span v-if="ascent.ascent_time" class="text-muted ml-2">{{ ascent.ascent_time }}</span></td>
          </tr>
          <tr>
            <td class="text-muted">{{ $t('common.route') }}</td>
            <td>
              <a v-if="ascent.route_name && ascent.route_article_url"
                 :href="guideRouteUrl(ascent.route_article_url)"
                 target="_blank"
                 class="text-success">
                {{ ascent.route_name }}
                <span v-if="ascent.route_grade" class="label label-default ml-1">{{ ascent.route_grade }}</span>
                <i class="fa fa-external-link fa-xs ml-1"></i>
              </a>
              <span v-else-if="ascent.route_name">
                {{ ascent.route_name }}
                <span v-if="ascent.route_grade" class="label label-default ml-1">{{ ascent.route_grade }}</span>
              </span>
              <span v-else class="text-muted">—</span>
            </td>
          </tr>
          <tr>
            <td class="text-muted">GPS</td>
            <td>
              <span v-if="ascent.is_gps_validated" class="label label-success">
                <i class="fa fa-check-circle"></i> {{ $t('summit.ascent_page.gps_verified') }}
              </span>
              <span v-else class="text-muted">—</span>
            </td>
          </tr>
          <tr v-if="ascent.comment">
            <td class="text-muted" style="vertical-align: top; padding-top: 10px">{{ $t('summit.ascent_page.comment_label') }}</td>
            <td>
              <div class="bg-light rounded px-3 py-2 small" style="font-style: italic; line-height: 1.6">
                <i class="fa fa-quote-left mr-1 text-muted"></i>{{ ascent.comment }}
              </div>
            </td>
          </tr>
        </tbody>
      </table>

    </div>
  </StackModal>
</template>

<script>
export default {
  name: 'AscentDetailModal',
  props: {
    modelValue: { type: Boolean, default: false },
    ascent: { type: Object, default: null },
  },
  emits: ['update:modelValue'],
  methods: {
    formatDate(d) {
      if (!d) return '—'
      return new Date(d).toLocaleDateString('en-GB', { year: 'numeric', month: 'short', day: 'numeric' })
    },
    guideRouteUrl(articlePath) {
      const base = process.env.MIX_APP_SSH
        ? (process.env.MIX_APP_SSH || '').replace(/\/$/, '') + '/' + (process.env.MIX_SITE_URL || '').replace(/^\/|\/$/g, '')
        : window.location.origin
      return `${base}/${articlePath}`
    },
  },
}
</script>
