<template>
    <div class="container">
        <div class="share_icons_row">
            <a class="share_icons_col" :href="facebookUrl" target="_blank" rel="noopener noreferrer" :aria-label="$t('common.share')">
                <i class="share_icons fa fa-facebook-square" aria-hidden="true"></i>
            </a>
            <a class="share_icons_col" :href="twitterUrl" target="_blank" rel="noopener noreferrer" :aria-label="$t('common.share')">
                <i class="share_icons fa fa-twitter-square" aria-hidden="true"></i>
            </a>
            <a class="share_icons_col" :href="telegramUrl" target="_blank" rel="noopener noreferrer" :aria-label="$t('common.share')">
                <i class="share_icons fa fa-telegram" aria-hidden="true"></i>
            </a>
            <a class="share_icons_col" :href="whatsappUrl" target="_blank" rel="noopener noreferrer" :aria-label="$t('common.share')">
                <i class="share_icons fa fa-whatsapp" aria-hidden="true"></i>
            </a>
            <a class="share_icons_col" :href="emailUrl" :aria-label="$t('common.share')">
                <i class="share_icons fa fa-envelope" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</template>

<script>
    // Plain share-link anchors — no vue-social-sharing dependency (it isn't
    // installed and ShareNetwork was never registered, so this previously
    // rendered nothing at all).
    export default {
        props: {
            title: { type: String, default: 'climbing.ge' },
            description: { type: String, default: '' },
            hashtags: { type: String, default: 'georgia,climbingingeorgia' },
        },
        data() {
            return {
                url: window.location.href,
            }
        },
        computed: {
            facebookUrl() {
                return 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(this.url);
            },
            twitterUrl() {
                const params = new URLSearchParams({ url: this.url, text: this.title, hashtags: this.hashtags });
                return 'https://twitter.com/intent/tweet?' + params.toString();
            },
            telegramUrl() {
                const params = new URLSearchParams({ url: this.url, text: this.title });
                return 'https://t.me/share/url?' + params.toString();
            },
            whatsappUrl() {
                return 'https://api.whatsapp.com/send?text=' + encodeURIComponent(this.title + ' ' + this.url);
            },
            emailUrl() {
                const body = (this.description ? this.description + '\n\n' : '') + this.url;
                return 'mailto:?subject=' + encodeURIComponent(this.title) + '&body=' + encodeURIComponent(body);
            },
        },
    }
</script>

<style>
.share_icons_row{
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
}
.share_icons{
    font-size: 3em;
}
.share_icons_col{
    text-align: center;
    padding: 0 15px;
}
</style>
