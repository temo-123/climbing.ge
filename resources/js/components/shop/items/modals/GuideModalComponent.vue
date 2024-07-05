<template>
    <stack-modal
            :show="is_show_mtp_modal"
            :title="guide.name + ' ' + guide.surname"
            @close="is_show_mtp_modal = false"
            :modal-class="{ [modalClass]: true }"
            :saveButton="{
                visible: true,
                title: 'Save',
                btnClass: { 'btn btn-primary': true },
            }"
            :cancelButton="{
                visible: false,
                title: 'Close',
                btnClass: { 'btn btn-danger': true },
            }"
        >
            <div class="model-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <img v-if='guide.image != null' :src="'../../../../public/images/user_profil_img/' + guide.image" />
                        </div>
                    </div>
                    <div class="row">
                        <h1>{{ guide.name }} {{ guide.surname }}</h1>
                        <p>{{ guide.city }}, {{ guide.country }}</p>

                        <hr />
                        <h3>Guide, socials and contact</h3>

                        <p><strong>{{ guide.email }}</strong></p>

                        <span v-for="site in guide.sites" :key="site.id">
                            <p @click="go_to_user_site(site.url)" class="cursor_pointer"><strong>{{ from_user_site_url_get_domen(site.url) }}</strong></p>
                        </span>
                    </div>
                </div>
            </div>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <!-- footer -->
                </div>
            </div>
        </stack-modal>
</template>

<script>
import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

export default {
    mixins: [
        // grade_chart,
    ],
    components: {
        StackModal,
    },
    props: [
        // "sector",
    ],
    data: function () {
        return {
            guide: '',
            is_show_mtp_modal: false
        };
    },
    mounted() {
        // this.get_spot_rocks_images();
    },
    methods: {
        show_guide_modal(user_id){
            // this.is_show_mtp_modal = true
            this.get_guide_data(user_id)
        },
        from_user_site_url_get_domen(url){
            return new URL(url).hostname;
        },
        get_guide_data(id) {
            axios
                .get("../../api/options/get_selected_user_data/" + id)
                .then((response) => {
                    this.guide = response.data;
                    // this.mtp_post_list = true;
                    this.is_show_mtp_modal = true
                })
                .catch((error) => {});
        },

        go_to_user_site(url){
            window.open(url, '_blank');
        }
    }
}
</script>

<style>

</style>