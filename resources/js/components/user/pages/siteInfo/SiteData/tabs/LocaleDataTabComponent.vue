<template>
    <table class="table table-hover" id="dev-table" >
        <thead>
            <tr>
                <th>Value name</th>
                <th>#</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>guid_title</td>
                <td>|</td>
                <td v-html="info.guid_title"></td>
            </tr>
            <tr>
                <td>guid_description</td>
                <td>|</td>
                <td v-html=" info.guid_description"></td>
            </tr>
            <tr>
                <td>guid_short_description</td>
                <td>|</td>
                <td v-html="info.guid_short_description"></td>
            </tr>
            <tr>
                <td>films_title</td>
                <td>|</td>
                <td v-html="info.films_title"></td>
            </tr>
            <tr>
                <td>films_description</td>
                <td>|</td>
                <td v-html="info.films_description"></td>
            </tr>
            <tr>
                <td>films_short_description</td>
                <td>|</td>
                <td v-html="info.films_short_description"></td>
            </tr>
            <tr>
                <td>forum_title</td>
                <td>|</td>
                <td v-html="info.forum_title"></td>
            </tr>
            <tr>
                <td>forum_description</td>
                <td>|</td>
                <td v-html="info.forum_description"></td>
            </tr>
            <tr>
                <td>forum_short_description</td>
                <td>|</td>
                <td v-html="info.forum_short_description"></td>
            </tr>
            <tr>
                <td>shop_title</td>
                <td>|</td>
                <td v-html="info.shop_title"></td>
            </tr>
            <tr>
                <td>shop_description</td>
                <td>|</td>
                <td v-html="info.shop_description"></td>
            </tr>
            <tr>
                <td>shop_short_description</td>
                <td>|</td>
                <td v-html="info.shop_short_description"></td>
            </tr>

            <tr>
                <td>other_activity_description</td>
                <td>|</td>
                <td v-html="info.other_activity_description"></td>
            </tr>
            <tr>
                <td>mount_description</td>
                <td>|</td>
                <td v-html="info.mount_description"></td>
            </tr>
            <tr>
                <td>event_description</td>
                <td>|</td>
                <td v-html="info.event_description"></td>
            </tr>
            <tr>
                <td>tech_tips_description</td>
                <td>|</td>
                <td v-html="info.tech_tips_description"></td>
            </tr>
            <tr>
                <td>news_description</td>
                <td>|</td>
                <td v-html="info.news_description"></td>
            </tr>
            <tr>
                <td>index_gallery_description</td>
                <td>|</td>
                <td v-html="info.index_gallery_description"></td>
            </tr>
            <tr>
                <td>outdoor_description</td>
                <td>|</td>
                <td v-html="info.outdoor_description"></td>
            </tr>
            <tr>
                <td>indoor_description</td>
                <td>|</td>
                <td v-html="info.indoor_description"></td>
            </tr>
            <tr>
                <td>ice_description</td>
                <td>|</td>
                <td v-html="info.ice_description"></td>
            </tr>
            <tr>
                <td>topo_description</td>
                <td>|</td>
                <td v-html="info.topo_description"></td>
            </tr>
            <tr>
                <td>what_we_do_description</td>
                <td>|</td>
                <td v-html="info.what_we_do_description"></td>
            </tr>
            <tr>
                <td>products_description</td>
                <td>|</td>
                <td v-html="info.products_description"></td>
            </tr>
            <tr>
                <td>services_description</td>
                <td>|</td>
                <td v-html="info.services_description"></td>
            </tr>
            <tr>
                <td>Terms of use</td>
                <td>|</td>
                <td v-html="info.terms_of_use"></td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {

    props: [
        'info'
    ],
}
</script>

<style>

</style>