<template>
    <div>
        <span v-html="this.article[0].text" id="descripton" v-if="this.article[0].text != NUll"></span>

        <!-- Best time for climbing block -->
        <div v-if="this.article[0].weather || this.article.global_info.best_time.length != 0">
            <div v-if="this.article[0].weather == NULL && this.article[0].best_time != NULL || this.article.global_info.best_time != []">
                <h2 id="best_time_to_climb">{{ $t('guide.article.title.best time')}}</h2>

                <span v-if="this.article.global_info.best_time.length == 0">
                    <span v-html="this.article[0].best_time"></span>
                </span>
                <span v-else>
                    <span v-if="this.article.global_info.best_time.block_action == 'befor'">
                        <span v-html="this.article.global_info.best_time.text"></span>
                        <span v-html="this.article[0].best_time"></span>
                    </span>
                    <span v-if="this.article.global_info.best_time.block_action == 'after'">
                        <span v-html="this.article[0].best_time"></span>
                        <span v-html="this.article.global_info.best_time.text"></span>
                    </span>
                    <span v-if="this.article.global_info.best_time.block_action == 'instead'">
                        <span v-html="this.article.global_info.best_time.text"></span>
                    </span>
                </span>

            </div>  
            <div v-else-if="this.article[0].weather != NULL && this.article[0].best_time != NULL">
                <h2 id="best_time_to_climb">{{ $t('guide.article.title.best time')}}</h2>

                <div class="row">
                    <div class="col-md-6" style="margin-top: 5%;">
                        <span v-if="this.article.global_info.best_time.length == 0">
                            <span v-html="this.article[0].best_time"></span>
                        </span>
                        <span v-else>
                            <span v-if="this.article.global_info.best_time.block_action == 'befor'">
                                <span v-html="this.article.global_info.best_time.text"></span>
                                <span v-html="this.article[0].best_time"></span>
                            </span>
                            <span v-if="this.article.global_info.best_time.block_action == 'after'">
                                <span v-html="this.article[0].best_time"></span>
                                <span v-html="this.article.global_info.best_time.text"></span>
                            </span>
                            <span v-if="this.article.global_info.best_time.block_action == 'instead'">
                                <span v-html="this.article.global_info.best_time.text"></span>
                            </span>
                        </span>
                    </div>
                    <div class="col-md-6" style="text-align: center;">
                        <span v-html="this.article.weather"></span>
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="this.article[0].weather || this.article.global_info.best_time.length != 0 || this.article.weather != null">
            <span v-html="this.article.weather"></span>
        </div>

        <!-- addres -->
        <div v-if="this.article[0].address != NUll">
            <h2 id="how_to_get_there">{{ $t('guide.article.title.address')}}</h2>
            <span v-html="this.article[0].address"></span>
        </div>
        
        <!-- how get -->
        <div v-if="this.article[0].how_get != NUll && this.article[0].how_get != ''">
            <h2 id="how_to_get_there">{{ $t('guide.article.title.how_get')}}</h2>
            <span v-html="this.article[0].how_get"></span>
        </div>

        <!-- map -->
        <div v-if="this.article.map != NULL">
            <div class="article_map">
                <span v-html="this.article.map"></span>
            </div>
        </div>

        <!-- price -->
        <div v-if="this.article[0].prices_text != NUll">
            <h2 id="how_to_get_there">{{ $t('guide.article.title.price')}}</h2>
            <span v-html="this.article[0].prices_text"></span>
        </div>

        <!-- what need -->
        <div v-if="this.article[0].what_need || this.article.global_info.what_need_info.length != 0">
            <h2 id="how_to_get_there">{{ $t('guide.article.title.what need')}}</h2>
            <span v-if="this.article.global_info.what_need_info.length == 0">
                <span v-html="this.article[0].what_need"></span>
            </span>
            <span v-else>
                <span v-if="this.article.global_info.what_need_info.block_action == 'befor'">
                    <span v-html="this.article.global_info.what_need_info.text"></span>
                    <span v-html="this.article[0].what_need"></span>
                </span>
                <span v-if="this.article.global_info.what_need_info.block_action == 'after'">
                    <span v-html="this.article[0].what_need"></span>
                    <span v-html="this.article.global_info.what_need_info.text"></span>
                </span>
                <span v-if="this.article.global_info.what_need_info.block_action == 'instead'">
                    <span v-html="this.article.global_info.what_need_info.text"></span>
                </span>
            </span>
        </div>

        <!-- info -->
        <div v-if="this.article[0].info || this.article.global_info.info_block.length != 0">
            <h2 id="how_to_get_there">{{ $t('guide.article.title.info')}}</h2>
            <span v-if="this.article.global_info.info_block.length == 0">
                <span v-html="this.article[0].info"></span>
            </span>
            <span v-else>
                <span v-if="this.article.global_info.info_block.block_action == 'befor'">
                    <span v-html="this.article.global_info.info_block.text"></span>
                    <span v-html="this.article[0].info"></span>
                </span>
                <span v-if="this.article.global_info.info_block.block_action == 'after'">
                    <span v-html="this.article[0].info"></span>
                    <span v-html="this.article.global_info.info_block.text"></span>
                </span>
                <span v-if="this.article.global_info.info_block.block_action == 'instead'">
                    <span v-html="this.article.global_info.info_block.text"></span>
                </span>
            </span>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            'article'
        ],
    }
</script>

<style>

</style>