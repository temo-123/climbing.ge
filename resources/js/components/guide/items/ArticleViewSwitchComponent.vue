<template>
    <!-- Plain text / cards switch for the article sections (services/guide/articleView.js) -->
    <viewSegmentSwitch
        :model-value="state.mode"
        :views="views"
        :label="$t('guide.article_view.label')"
        @update:model-value="set"
    />
</template>

<script>
    import { article_view, set_article_view } from '../../../services/guide/articleView.js'
    import viewSegmentSwitch from './ViewSegmentSwitchComponent.vue'

    export default {
        name: 'ArticleViewSwitchComponent',
        components: { viewSegmentSwitch },
        data() {
            return { state: article_view };
        },
        computed: {
            views() {
                return [
                    { mode: 'plain', icon: 'fa-align-left', label: this.$t('guide.article_view.plain') },
                    { mode: 'cards', icon: 'fa-th-large', label: this.$t('guide.article_view.cards') },
                ];
            },
        },
        methods: {
            set(mode) { set_article_view(mode); },
        },
    };
</script>
