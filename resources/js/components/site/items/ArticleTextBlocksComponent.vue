<template>
    <div>
        <span v-html="this.article.locale_data.text" id="description" v-if="this.article.locale_data.text != NUll"></span>

        <!-- Best time for climbing block -->
        <div v-if="this.article.locale_data.weather || this.article.global_info.best_time.length != 0">
            <div v-if="this.article.locale_data.weather == NULL && this.article.locale_data.best_time != NULL || this.article.global_info.best_time != []">
                <h2 id="best_time_to_climb">{{ $t('guide.article.title.best time')}}</h2>

                <generalInfo :global_info="article.global_info.best_time" :locale_data="article.locale_data.best_time"/>

            </div>  
            <div v-else-if="this.article.locale_data.weather != NULL && this.article.locale_data.best_time != NULL">
                <h2 id="best_time_to_climb">{{ $t('guide.article.title.best time')}}</h2>

                <div class="row">

                    <generalInfo :global_info="article.global_info.best_time" :locale_data="article.locale_data.best_time"/>

                    <div class="col-md-6" style="text-align: center;">
                        <span v-html="this.article.weather"></span>
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="this.article.locale_data.weather || this.article.global_info.best_time.length != 0 || this.article.weather != null">
            <span v-html="this.article.weather"></span>
        </div>

        <!-- addres -->
        <div v-if="this.article.locale_data.address != NUll">
            <h2 id="how_to_get_there">{{ $t('guide.article.title.address')}}</h2>
            <span v-html="this.article.locale_data.address"></span>
        </div>
        
        <!-- how get -->
        <div v-if="this.article.locale_data.how_get != NUll && this.article.locale_data.how_get != ''">
            <h2 id="how_to_get_there">{{ $t('guide.article.title.how_get')}}</h2>
            <span v-html="this.article.locale_data.how_get"></span>
        </div>

        <!-- map -->
        <div v-if="this.article.map != NULL">
            <div class="article_map">
                <span v-html="this.article.map"></span>
            </div>
        </div>

        <!-- price -->
        <div v-if="this.article.locale_data.prices_text != NUll">
            <h2 id="how_to_get_there">{{ $t('guide.article.title.price')}}</h2>
            <span v-html="this.article.locale_data.prices_text"></span>
        </div>

        <!-- what need -->
        <div v-if="this.article.locale_data.what_need || this.article.global_info.what_need_info.length != 0">
            <h2 id="how_to_get_there">{{ $t('guide.article.title.what need')}}</h2>

            <generalInfo :global_info="article.global_info.what_need_info" :locale_data="article.locale_data.what_need"/>

        </div>

        <!-- info -->
        <div v-if="this.article.locale_data.info || this.article.global_info.info_block.length != 0">
            <h2 id="how_to_get_there">{{ $t('guide.article.title.info')}}</h2>

            <generalInfo :global_info="article.global_info.info_block" :locale_data="article.locale_data.info"/>

        </div>

    </div>
</template>

<script>
    import generalInfo from './article/GeneralInfoComponent'

    export default {
        props: [
            'article'
        ],
        data: function () {
            return {
                //
            }
        },
        components: {
            generalInfo
        },
    }
</script>

<style>

</style>