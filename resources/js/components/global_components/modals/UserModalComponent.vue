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
                title: $t('common.close'),
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
                        <img v-if='localUser.image' :src="'/public/images/user_profil_img/' + localUser.image" class="modal_profil_image" :alt="$t('global.profile_image_of_prefix') + ' ' + localUser.name + ' ' + localUser.surname" />
                        <div v-else class="modal_profil_image modal_profil_image_placeholder"><i class="fa fa-user"></i></div>
                        <h1 class="text-3xl md:text-4xl font-bold text-center text-gray-800">{{ localUser.name }} {{ localUser.surname }}</h1>
                        <span v-if="localUser.member_status" class="user-modal-status">{{ localUser.member_status }}</span>
                        <p v-if="localUser.city || localUser.country" class="user-modal-subtitle">
                            <i class="fa fa-map-marker"></i> {{ [localUser.city, localUser.country].filter(Boolean).join(', ') }}
                        </p>
                    </div>

                    <div class="user-modal-info">
                        <p class="user-modal-bio" v-if="localUser.my_bio">{{ localUser.my_bio }}</p>

                        <div class="user-modal-info-row" v-if="localUser.email">
                            <i class="fa fa-envelope"></i>
                            <span>{{ localUser.email }}</span>
                        </div>

                        <div class="user-modal-sites" v-if="localUser.sites && localUser.sites.length">
                            <span
                                v-for="site in localUser.sites"
                                :key="site.id"
                                @click="go_to_user_site(site.url)"
                                class="user-modal-site-link"
                            >
                                <i class="fa fa-globe"></i> {{ from_user_site_url_get_domen(site.url) }}
                            </span>
                        </div>
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
            return this.localUser.name + ' ' + this.localUser.surname || this.$t('common.loading');
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
        show_modal(user_id, type = 'user'){
            this.loading = true;
            this.localUser = {};
            this.get_user_data(user_id, type)
        },
        from_user_site_url_get_domen(url){
            return new URL(url).hostname;
        },
        get_user_data(id, type) {
            const url = type === 'guide'
                ? '/get_tour/get_guide/' + id
                : '/get_team/get_team_member_data/' + id;
            axios
                .get(url)
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
    display: block;
    width: 200px;
    height: 200px;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid #edf2f7;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin: 0 auto 1rem;
}

.modal_profil_image_placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f1f2f6;
    color: #b2b2ff;
    font-size: 3.5rem;
}

.user-modal-status {
    display: inline-block;
    margin-top: 8px;
    padding: 4px 14px;
    background: linear-gradient(135deg, #7c7cfd, #b2b2ff);
    color: #fff;
    font-size: 0.95rem;
    font-weight: 600;
    border-radius: 20px;
}

.user-modal-subtitle {
    color: #6b7280;
    font-size: 1.15rem;
    display: flex;
    align-items: center;
    gap: 6px;
    justify-content: center;
    margin-top: 6px;
}

.user-modal-subtitle i {
    color: #7c7cfd;
}

.user-modal-info {
    background: #f9fafb;
    border-radius: 10px;
    padding: 16px 20px;
}

.user-modal-bio {
    font-size: 1.3rem;
    line-height: 1.6;
    color: #374151;
    white-space: pre-line;
    margin: 0 0 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e5e7eb;
}

.user-modal-info-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    font-size: 1.35rem;
    color: #374151;
}

.user-modal-info-row i {
    width: 22px;
    text-align: center;
    font-size: 1.3rem;
    color: #7c7cfd;
}

.user-modal-sites {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 10px;
}

.user-modal-site-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    font-size: 1.2rem;
    color: #4b5563;
    cursor: pointer;
    transition: all 0.2s ease;
}

.user-modal-site-link:hover {
    background: #7c7cfd;
    color: #fff;
    border-color: #7c7cfd;
}
</style>
