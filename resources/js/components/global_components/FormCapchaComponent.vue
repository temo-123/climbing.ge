<template>
    <span>
        <div class="footer_re_capcha">
            <!-- reCAPTCHA v3 native integration -->
        </div>

        <div v-if="loading == false" class="form-group">
            <button class="btn btn-default btn-send main-btn" @click="handleSubmit">{{ buttonTextProp }}</button>
        </div>
        <div v-if="loading == true">
            <h4  class="footer_title">Loading</h4>
        </div>
    </span>
</template>

<script>
export default {
    props: {
        buttonTextProp: {
            type: String,
            default: 'Verify'
        }
    },
    data() {
        return {
            MIX_GOOGLE_CAPTCHA_V3_SITE_KEY: process.env.MIX_GOOGLE_CAPTCHA_V3_SITE_KEY,
            is_verify_isset: false,
            loading: false,
        };
    },
    mounted() {
        // Load reCAPTCHA v3 script
        if (!window.grecaptcha) {
            const script = document.createElement('script');
            script.src = `https://www.google.com/recaptcha/api.js?render=${this.MIX_GOOGLE_CAPTCHA_V3_SITE_KEY}`;
            document.head.appendChild(script);
        }
    },
    methods: {
        async executeRecaptcha(action = 'follow') {
            try {
                await new Promise(resolve => window.grecaptcha.ready(resolve));
                const token = await window.grecaptcha.execute(this.MIX_GOOGLE_CAPTCHA_V3_SITE_KEY, {action});
                return token;
            } catch (error) {
                console.error('reCAPTCHA error:', error);
                return null;
            }
        },
        async handleSubmit() {
            this.loading = true;
            const token = await this.executeRecaptcha('message');
            if (token) {
                this.$emit('recaptcha-verified', token);
            }
            this.loading = false;
        },
        onCaptchaExpired() {
            this.is_verify_isset = false;
        }
    }
}
</script>

<style>
.footer_input, .footer_re_capcha {
    margin-bottom: 1.5%;
}
/* Hide reCAPTCHA badge */
.grecaptcha-badge {
    visibility: hidden !important;
}
</style>
