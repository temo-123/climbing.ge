<template>
    <StackModal
        v-model="is_show_modal"
        :title="$t('global.follow.profile_modal_title')"
        @close="close_modal"
        @save="go_to_full_profile"
        :saveButton="{ visible: !!active_user_id, title: $t('global.follow.view_full_profile_btn'), btnClass: { 'btn btn-primary': true } }"
        :cancelButton="{ title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
    >
        <climber-profile-content
            v-if="active_user_id"
            :user-id="active_user_id"
            @login-required="on_login_required"
            @login-succeeded="on_login_succeeded"
            @follow-changed="$emit('follow-changed', $event)"
        />
    </StackModal>
</template>

<script>
    import ClimberProfileContent from './ClimberProfileContentComponent.vue';

    export default {
        components: {
            'climber-profile-content': ClimberProfileContent,
        },
        emits: ['follow-changed'],
        data() {
            return {
                is_show_modal: false,
                active_user_id: null,
            };
        },
        methods: {
            show_modal(userId) {
                this.active_user_id = userId;
                this.is_show_modal = true;
            },
            close_modal() {
                this.is_show_modal = false;
            },
            on_login_required() {
                // Don't stack the login modal on top of this one — close first,
                // the login-succeeded callback below brings this modal back.
                this.is_show_modal = false;
            },
            on_login_succeeded() {
                this.is_show_modal = true;
            },
            go_to_full_profile() {
                const ssh = process.env.MIX_APP_SSH || 'http://';
                const siteHost = process.env.MIX_GUIDBOOK_URL || process.env.MIX_SITE_URL || '';
                window.open(ssh + siteHost + '/climber/' + this.active_user_id);
            },
        },
    };
</script>
