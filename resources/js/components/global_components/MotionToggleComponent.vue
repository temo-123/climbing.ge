<template>
    <!-- Navbar switch for the site's animations (services/motion.js).
         Per device; the account default lives in user options → Preferences. -->
    <li class="cg-motion-toggle">
        <button
            type="button"
            class="cg-icon-btn"
            :class="{ 'is-off': !motion.enabled }"
            :aria-pressed="motion.enabled"
            :title="motion.enabled ? $t('global.motion.turn_off') : $t('global.motion.turn_on')"
            :aria-label="motion.enabled ? $t('global.motion.turn_off') : $t('global.motion.turn_on')"
            @click="toggle"
        >
            <i class="fa fa-magic" aria-hidden="true"></i>
        </button>
    </li>
</template>

<script>
    import { motion, set_motion } from '../../services/motion.js'

    export default {
        name: 'MotionToggle',
        data() {
            return { motion };
        },
        methods: {
            toggle() {
                set_motion(!this.motion.enabled);
                this.$bus.$emit('toast', {
                    type: 'info',
                    duration: 2500,
                    message: this.motion.enabled ? this.$t('global.motion.on_toast') : this.$t('global.motion.off_toast'),
                });
            },
        },
    };
</script>

<style scoped>
    .cg-motion-toggle { list-style: none; }
    .cg-motion-toggle button { border: 0; position: relative; }
    /* struck-through wand when animations are off: a red "\" line */
    .cg-motion-toggle button.is-off { opacity: .85; }
    .cg-motion-toggle button.is-off::after {
        content: "";
        position: absolute;
        left: 20%;
        right: 20%;
        top: 50%;
        height: 2.5px;
        margin-top: -1.25px;
        border-radius: 2px;
        background: #dc3545;
        box-shadow: 0 0 0 1px rgba(255, 255, 255, .55);
        transform: rotate(45deg);
    }
</style>
