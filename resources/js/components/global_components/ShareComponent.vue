<template>
    <div class="row">
        <div class="container">
            <div class="col-md-3 col-sm-3 col-xs-3 share_icons_col">
                <ShareNetwork
                    network="facebook"
                    :url="url"
                    :title="title"
                    :description="description"
                    :quote="quote"
                    :hashtags="hashtags"
                >
                    <i class="share_icons fa fa-facebook-square" aria-hidden="true"></i>
                </ShareNetwork>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3 share_icons_col">
                <ShareNetwork
                    network="Email"
                    :url="url"
                    :title="title"
                    :description="description"
                    :quote="quote"
                    :hashtags="hashtags"
                >
                    <i class="share_icons fa fa-envelope" aria-hidden="true"></i>
                </ShareNetwork>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3 share_icons_col">
                <ShareNetwork
                    network="Twitter"
                    :url="url"
                    :title="title"
                    :description="description"
                    :quote="quote"
                    :hashtags="hashtags"
                >
                    <i class="share_icons fa fa-twitter-square" aria-hidden="true"></i>
                </ShareNetwork>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3 share_icons_col">
                <ShareNetwork
                    network="WhatsApp"
                    :url="url"
                    :title="title"
                    :description="description"
                    :quote="quote"
                    :hashtags="hashtags"
                >
                    <i class="share_icons fa fa-whatsapp" aria-hidden="true"></i>
                </ShareNetwork>
            </div>
            <!-- <ShareNetwork
                network="Messenger"
                :url="url"
                :title="title"
                :description="description"
                :quote="quote"
                :hashtags="hashtags"
            >
                Share on Messenger
            </ShareNetwork> -->
        </div>
    </div>
</template>

<script>
    export default {
        // https://www.npmjs.com/package/vue-social-sharing
        data: function () {
            return {
                url: window.location.href,
                title: "Say hi to Vite! A brand new, extremely fast development setup for Vue.",
                description: "This week, I’d like to introduce you to 'Vite', which means 'Fast'. It’s a brand new development setup created by Evan You.",
                quote: "The hot reload is so fast it\'s near instant. - Evan You",
                hashtags: "georgia, climbing in georgia",
            };
        },
    }
</script>

<style>
.share_icons{
    font-size: 3em;
}
.share_icons_col{
    text-align: center;
}
</style>