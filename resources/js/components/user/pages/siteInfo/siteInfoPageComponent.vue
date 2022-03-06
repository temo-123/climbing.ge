<template>
    <div class="tabs">
        <div class="row">
            
            <input type="radio" name="tabs" id="1" checked="checked">
            <label for="1">Global Data</label>
            <div class="tab">
                <table>
                    <thead>
                        <tr>
                            <th>Value name</th>
                            <th>#</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <tr>
                            <td>ID</td>
                            <td>|</td>
                            <td>{{site_info.id}}</td>
                        </tr> -->
                        <tr>
                            <td>Email</td>
                            <td>|</td>
                            <td>{{this.$siteData.email}}</td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td>|</td>
                            <td>{{site_info.number}}</td>
                        </tr>

                        <tr>
                            <td>Map</td>
                            <td>|</td>
                            <td>{{site_info.map}}</td>
                        </tr>

                        <tr>
                            <td>Facebook</td>
                            <td>|</td>
                            <td>{{site_info.fb_link}}</td>
                        </tr>
                        <tr>
                            <td>Instagram</td>
                            <td>|</td>
                            <td>{{site_info.inst_link}}</td>
                        </tr>
                        <tr>
                            <td>Goolge</td>
                            <td>|</td>
                            <td>{{site_info.google_link}}</td>
                        </tr>
                        <tr>
                            <td>Twitter</td>
                            <td>|</td>
                            <td>{{site_info.twit_link}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <input type="radio" name="tabs" id="2">
            <label for="2">English Data</label>
            <div class="tab">
                <table>
                    <thead>
                        <tr>
                            <th>Value name</th>
                            <th>#</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Title</td>
                            <td>|</td>
                            <td>{{site_info.title}}</td>
                        </tr>
                        <tr>
                            <td>Short description</td>
                            <td>|</td>
                            <td>{{ site_info.short_description }}</td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td>|</td>
                            <td>{{site_info.text}}</td>
                        </tr>
                        <tr>
                            <td>other_activity_description</td>
                            <td>|</td>
                            <td>{{site_info.other_activity_description}}</td>
                        </tr>
                        <tr>
                            <td>mount_description</td>
                            <td>|</td>
                            <td>{{site_info.mount_description}}</td>
                        </tr>
                        <tr>
                            <td>outdoor_description</td>
                            <td>|</td>
                            <td>{{site_info.outdoor_description}}</td>
                        </tr>
                        <tr>
                            <td>ice_description</td>
                            <td>|</td>
                            <td>{{site_info.ice_description}}</td>
                        </tr>
                        <tr>
                            <td>topo_description</td>
                            <td>|</td>
                            <td>{{site_info.topo_description}}</td>
                        </tr>
                        <tr>
                            <td>what_we_do_description</td>
                            <td>|</td>
                            <td>{{site_info.what_we_do_description}}</td>
                        </tr>
                        <tr>
                            <td>shop_short_description</td>
                            <td>|</td>
                            <td>{{site_info.shop_short_description}}</td>
                        </tr>
                        <tr>
                            <td>shop_description</td>
                            <td>|</td>
                            <td>{{site_info.shop_description}}</td>
                        </tr>
                        <tr>
                            <td>services_description</td>
                            <td>|</td>
                            <td>{{site_info.services_description}}</td>
                        </tr>
                        <!-- <tr>
                            <td>meta_description</td>
                            <td>|</td>
                            <td>{{site_info.meta_description}}</td>
                        </tr> -->
                    </tbody>
                </table>
            </div>

            <input type="radio" name="tabs" id="3">
            <label for="3">Russion Data</label>
            <div class="tab">
                <table>
                    <thead>
                        <tr>
                            <th>Value name</th>
                            <th>#</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Title</td>
                            <td>|</td>
                            <td>{{site_info.title_ru}}</td>
                        </tr>
                        <tr>
                            <td>Short description</td>
                            <td>|</td>
                            <td>{{ site_info.short_description_ru}}</td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td>|</td>
                            <td>{{site_info.text_ru}}</td>
                        </tr>
                        <tr>
                            <td>other_activity_description</td>
                            <td>|</td>
                            <td>{{site_info.other_activity_description_ru}}</td>
                        </tr>
                        <tr>
                            <td>mount_description</td>
                            <td>|</td>
                            <td>{{site_info.mount_description_ru}}</td>
                        </tr>
                        <tr>
                            <td>outdoor_description</td>
                            <td>|</td>
                            <td>{{site_info.outdoor_description_ru}}</td>
                        </tr>
                        <tr>
                            <td>ice_description</td>
                            <td>|</td>
                            <td>{{site_info.ice_description_ru}}</td>
                        </tr>
                        <tr>
                            <td>topo_description</td>
                            <td>|</td>
                            <td>{{site_info.topo_description_ru}}</td>
                        </tr>
                        <tr>
                            <td>what_we_do_description</td>
                            <td>|</td>
                            <td>{{site_info.what_we_do_description_ru}}</td>
                        </tr>
                        <tr>
                            <td>shop_short_description</td>
                            <td>|</td>
                            <td>{{site_info.shop_short_description_ru}}</td>
                        </tr>
                        <tr>
                            <td>shop_description</td>
                            <td>|</td>
                            <td>{{site_info.shop_description_ru}}</td>
                        </tr>
                        <tr>
                            <td>services_description</td>
                            <td>|</td>
                            <td>{{site_info.services_description_ru}}</td>
                        </tr>
                        <!-- <tr>
                            <td>meta_description</td>
                            <td>|</td>
                            <td>{{site_info.meta_description_ru}}</td>
                        </tr> -->
                    </tbody>
                </table>
            </div>

            <input type="radio" name="tabs" id="4">
            <label for="4">Georgian Data</label>
            <div class="tab">
                <table>
                    <thead>
                        <tr>
                            <th>Value name</th>
                            <th>#</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Title</td>
                            <td>|</td>
                            <td>{{site_info.title_ka}}</td>
                        </tr>
                        <tr>
                            <td>Short description</td>
                            <td>|</td>
                            <td>{{ site_info.short_description_ka}}</td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td>|</td>
                            <td>{{site_info.text_ka}}</td>
                        </tr>
                        <tr>
                            <td>other_activity_description</td>
                            <td>|</td>
                            <td>{{site_info.other_activity_description_ka}}</td>
                        </tr>
                        <tr>
                            <td>mount_description</td>
                            <td>|</td>
                            <td>{{site_info.mount_description_ka}}</td>
                        </tr>
                        <tr>
                            <td>outdoor_description</td>
                            <td>|</td>
                            <td>{{site_info.outdoor_description_ka}}</td>
                        </tr>
                        <tr>
                            <td>ice_description</td>
                            <td>|</td>
                            <td>{{site_info.ice_description_ka}}</td>
                        </tr>
                        <tr>
                            <td>topo_description</td>
                            <td>|</td>
                            <td>{{site_info.topo_description_ka}}</td>
                        </tr>
                        <tr>
                            <td>what_we_do_description</td>
                            <td>|</td>
                            <td>{{site_info.what_we_do_description_ka}}</td>
                        </tr>
                        <tr>
                            <td>shop_short_description</td>
                            <td>|</td>
                            <td>{{site_info.shop_short_description_ka}}</td>
                        </tr>
                        <tr>
                            <td>shop_description</td>
                            <td>|</td>
                            <td>{{site_info.shop_description_ka}}</td>
                        </tr>
                        <tr>
                            <td>services_description</td>
                            <td>|</td>
                            <td>{{site_info.services_description_ka}}</td>
                        </tr>
                        <!-- <tr>
                            <td>meta_description</td>
                            <td>|</td>
                            <td>{{site_info.meta_description_ka}}</td>
                        </tr> -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        inject:['siteData'],
        props: [
        ],
        data() {
            return {
                site_info: [],
            }
        },
        components: {
        },
        mounted() {
            this.get_site_data()
        },
        methods: {
            get_site_data: function(){
                axios
                .get('about/get_site_data/')
                .then(response => {
                    this.site_info = response.data.site_info[0]
                    // console.log('1');
                    // console.log(this.site_info);
                })
                .catch(
                    error => console.log(error)
                );
            }
        }
    }
</script>
