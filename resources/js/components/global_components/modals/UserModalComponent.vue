<template>
    <StackModal
            v-model="is_show_mtp_modal"
            :title="modalTitle"
            size="xxl"
            :modal-class="{ [modalClass]: true }"
            :saveButton="{
                visible: false,
            }"
            :cancelButton="{
                visible: true,
                title: 'Close',
                btnClass: { 'btn btn-danger float-right': true },
            }"
            @close="is_show_mtp_modal = false"

        >
            <div class="p-6">
                <div v-if="loading" class="flex justify-center items-center py-10">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                </div>
                <div v-else class="max-w-2xl mx-auto">
                    <div class="flex flex-col items-center mb-6">
                        <img v-if='localUser.image' :src="'/public/images/user_profil_img/' + localUser.image" class="modal_profil_image" :alt="'Profile image of ' + localUser.name + ' ' + localUser.surname" />
                        <h1 class="text-2xl md:text-3xl font-bold text-center text-gray-800">{{ localUser.name }} {{ localUser.surname }}</h1>
                        <p class="text-lg text-gray-600 text-center">{{ localUser.city }}, {{ localUser.country }}</p>
                    </div>
                    <div class="row">
                        <p><strong>{{ localUser.email }}</strong></p>

                        <span v-for="site in localUser.sites" :key="site.id">
                            <p @click="go_to_user_site(site.url)" class="cursor_pointer"><strong>{{ from_user_site_url_get_domen(site.url) }}</strong></p>
                        </span>
                    </div>
                </div>
            </div>
        </StackModal>
</template>

<script>
export default {
    emits: ['show_modal'],
    mixins: [
        // grade_chart,
    ],
    components: {
        // No local import needed - global
    },
    props: {
        modalClass: {
            type: String,
            default: '',
        }
    },
    computed: {
        modalTitle() {
            return this.localUser.name + ' ' + this.localUser.surname || 'Loading...';
        }
    },
    data: function () {
        return {
            is_show_mtp_modal: false,
            loading: false,
            localUser: {}
        };
    },
    mounted() {
    },
    methods: {
        show_modal(user_id){
            this.loading = true;
            this.localUser = {};
            this.get_user_data(user_id)
        },
        from_user_site_url_get_domen(url){
            return new URL(url).hostname;
        },
        get_user_data(id) {
            axios
                .get("/get_tour/get_guide/" + id)
                .then((response) => {
                    this.localUser = response.data;
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
