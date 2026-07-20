<template>
    <div class="container climber-profile">
        <metaData
            :title="metaTitle"
            :description="metaDescription"
            :image="metaImage"
        />

        <climber-profile-content :user-id="userId" @profile-loaded="on_profile_loaded" />
    </div>
</template>

<script>
    import ClimberProfileContent from '../items/climber/ClimberProfileContentComponent.vue';
    import metaData from '../items/MetaDataComponent';

    export default {
        components: {
            'climber-profile-content': ClimberProfileContent,
            metaData,
        },
        data() {
            return {
                profile: null,
            };
        },
        computed: {
            userId() {
                return this.$route.params.id;
            },
            climberName() {
                if (!this.profile) return '';
                return `${this.profile.user.name || ''} ${this.profile.user.surname || ''}`.trim();
            },
            metaTitle() {
                return this.climberName
                    ? `${this.climberName} - ${this.$t('global.follow.profile_modal_title')} - climbing.ge`
                    : 'climbing.ge';
            },
            metaDescription() {
                if (this.profile && this.profile.user.my_bio) return this.profile.user.my_bio;
                return this.climberName
                    ? this.$t('global.follow.climber_meta_description', { name: this.climberName })
                    : '';
            },
            metaImage() {
                return this.profile && this.profile.user.image
                    ? '/public/images/user_profil_img/' + this.profile.user.image
                    : '';
            },
        },
        watch: {
            userId() {
                this.profile = null;
            },
        },
        methods: {
            on_profile_loaded(profile) {
                this.profile = profile;
            },
        },
    };
</script>
