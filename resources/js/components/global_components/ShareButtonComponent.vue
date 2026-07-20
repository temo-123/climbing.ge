<template>
    <button
        type="button"
        class="share-icon-btn"
        @click="share"
        :aria-label="$t('common.share')"
        :title="$t('common.share')"
    >
        <i class="fa fa-share-alt" aria-hidden="true"></i>
    </button>
</template>

<script>
    // Native Web Share API (mobile browsers, most social apps) with a
    // clipboard-copy fallback for desktop browsers that don't support it.
    export default {
        name: 'ShareButtonComponent',
        props: {
            title: { type: String, default: '' },
            text: { type: String, default: '' },
            url: { type: String, default: '' },
            // Optional GA4 `share` event dimensions — e.g. contentType="article", itemId=42
            contentType: { type: String, default: '' },
            itemId: { type: [String, Number], default: '' },
        },
        methods: {
            async share() {
                const shareUrl = this.url || window.location.href;

                if (navigator.share) {
                    try {
                        await navigator.share({ title: this.title, text: this.text, url: shareUrl });
                        this.trackShare('web_share_api');
                    } catch (e) {
                        if (e.name !== 'AbortError') {
                            this.copyToClipboard(shareUrl);
                        }
                    }
                    return;
                }

                this.copyToClipboard(shareUrl);
            },
            copyToClipboard(shareUrl) {
                navigator.clipboard.writeText(shareUrl).then(() => {
                    this.$bus.$emit('toast', {
                        type: 'success',
                        message: this.$t('common.link_copied'),
                    });
                    this.trackShare('clipboard');
                }).catch(() => {
                    this.$bus.$emit('toast', {
                        type: 'danger',
                        message: this.$t('common.link_copy_failed'),
                    });
                });
            },
            trackShare(method) {
                if (!this.$gtag) return;
                this.$gtag.event('share', {
                    method,
                    content_type: this.contentType || undefined,
                    item_id: this.itemId ? String(this.itemId) : undefined,
                });
            },
        },
    }
</script>

<style scoped>
.share-icon-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    background: none;
    font-size: 1.4em;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    color: #666;
    transition: background-color 0.2s, color 0.2s;
}
.share-icon-btn:hover {
    background-color: #f0f0f0;
    color: #333;
}
</style>
