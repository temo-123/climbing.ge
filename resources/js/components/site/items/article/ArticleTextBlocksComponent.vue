<template>
    <div>
        <span v-html="this.article_prop.locale_data.text" id="description" v-if="this.article_prop.locale_data.text != NUll"></span>

        <!-- Best time for climbing block -->
        <div v-if="this.article_prop.locale_data.weather || this.article_prop.general_info.best_time.length != 0">
            <div v-if="this.article_prop.locale_data.weather == NULL && this.article_prop.locale_data.best_time != NULL || this.article_prop.general_info.best_time != []">
                <h2 id="best_time_to_climb">{{ $t('guide.article.title.best time')}}</h2>

                <generalInfo :global_info_prop="article_prop.general_info.best_time" :locale_data_prop="article_prop.locale_data.best_time"/>

            </div>  
            <div v-else-if="this.article_prop.locale_data.weather != NULL && this.article_prop.locale_data.best_time != NULL">
                <h2 id="best_time_to_climb">{{ $t('guide.article.title.best time')}}</h2>

                <div class="row">

                    <generalInfo :global_info_prop="article_prop.general_info.best_time" :locale_data_prop="article_prop.locale_data.best_time"/>

                    <div class="col-md-6" style="text-align: center;">
                        <span v-html="this.article_prop.weather"></span>
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="this.article_prop.locale_data.weather || this.article_prop.general_info.best_time.length != 0 || this.article_prop.weather != null">
            <span v-html="this.article_prop.weather"></span>
        </div>

        <!-- addres -->
        <div v-if="this.article_prop.locale_data.address != NUll">
            <h2 id="how_to_get_there">{{ $t('guide.article.title.address')}}</h2>
            <span v-html="this.article_prop.locale_data.address"></span>
        </div>
        
        <!-- how get -->
        <div v-if="this.article_prop.locale_data.how_get != NUll && this.article_prop.locale_data.how_get != ''">
            <h2 id="how_to_get_there">{{ $t('guide.article.title.how_get')}}</h2>
            <span v-html="this.article_prop.locale_data.how_get"></span>
        </div>

        <!-- map -->
        <div v-if="this.article_prop.map != NULL">
            <div class="article_map">
                <span v-html="this.article_prop.map"></span>
            </div>
        </div>

        <!-- price -->
        <div v-if="this.article_prop.locale_data.prices_text != NUll">
            <h2 id="how_to_get_there">{{ $t('guide.article.title.price')}}</h2>
            <span v-html="this.article_prop.locale_data.prices_text"></span>
        </div>

        <!-- what need -->
        <div v-if="this.article_prop.locale_data.what_need || this.article_prop.general_info.what_need_info.length != 0">
            <h2 id="how_to_get_there">{{ $t('guide.article.title.what need')}}</h2>

            <generalInfo :global_info_prop="article_prop.general_info.what_need_info" :locale_data_prop="article_prop.locale_data.what_need"/>

        </div>

        <!-- info -->
        <div v-if="this.article_prop.locale_data.info || this.article_prop.general_info.info_block.length != 0">
            <h2 id="how_to_get_there">{{ $t('guide.article.title.info')}}</h2>

            <generalInfo :global_info_prop="article_prop.general_info.info_block" :locale_data_prop="article_prop.locale_data.info"/>

        </div>

    </div>
</template>

<script>
    import generalInfo from './GeneralInfoComponent'

    export default {
        props: [
            'article_prop'
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