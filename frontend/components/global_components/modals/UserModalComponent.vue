<template>
    <stack-modal
            :show="is_show_mtp_modal"
            :title="user.name + ' ' + user.surname"
            @close="is_show_mtp_modal = false"
            :modal-class="{ [modalClass]: true }"
            :saveButton="{
                visible: false,
            }"
            :cancelButton="{
                visible: true,
                title: 'Close',
                btnClass: { 'btn btn-danger float-right': true },
            }"
        >
            <div class="p-6">
                <div v-if="loading" class="flex justify-center items-center py-10">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                </div>
                <div v-else class="max-w-2xl mx-auto">
                    <div class="flex flex-col items-center mb-6">
                        <img v-if='user.image' :src="'/public/images/user_profil_img/' + user.image" class="modal_profil_image" :alt="'Profile image of ' + user.name + ' ' + user.surname" />
                        <!-- <div v-else class="w-32 h-32 md:w-40 md:h-40 rounded-full bg-gray-200 flex items-center justify-center mb-4">
                            <span class="text-gray-500 text-4xl">👤</span>
                        </div> -->
                        <h1 class="text-2xl md:text-3xl font-bold text-center text-gray-800">{{ user.name }} {{ user.surname }}</h1>
                        <p class="text-lg text-gray-600 text-center">{{ user.city }}, {{ user.country }}</p>
                    </div>
                    <div class="row">
                        <p><strong>{{ user.email }}</strong></p>

                        <span v-for="site in user.sites" :key="site.id">
                            <p @click="go_to_user_site(site.url)" class="cursor_pointer"><strong>{{ from_user_site_url_get_domen(site.url) }}</strong></p>
                        </span>
                    </div>
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
    props: {
        modalClass: {
            type: String,
            default: '',
        },
        user: {
            type: Object,
            default: () => ({}),
        },
        // "sector",
    },
    data: function () {
        return {
            is_show_mtp_modal: false,
            loading: false
        };
    },
    mounted() {
        // this.get_spot_rocks_images();
    },
    methods: {
        show_modal(user_id){
            this.loading = true;
            this.get_user_data(user_id)
        },
        from_user_site_url_get_domen(url){
            return new URL(url).hostname;
        },
        get_user_data(id) {
            axios
                .get("/get_tour/get_guide/" + id)
                .then((response) => {
                    this.user = response.data;
                    this.loading = false;
                    this.is_show_mtp_modal = true
                })
                .catch((error) => {
                    this.loading = false;
                });
        },

        go_to_user_site(url){
            window.open(url, '_blank');
        }
    }
}
</script>

<style>
.modal_profil_image {
    border: 4px solid #edf2f7;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 1rem;

    max-width: 70%;
    margin-left: 15%;
}
</style>