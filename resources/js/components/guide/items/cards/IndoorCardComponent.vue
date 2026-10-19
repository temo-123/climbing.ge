<template>
    <!-- Whole card opens the gym page (coming soon gyms have no page yet — show a notice instead) -->
    <div :class="['row', 'featurette', 'indoor-card', { 'indoor-card-soon': is_coming_soon }]" :role="is_coming_soon ? null : 'link'" @click="open_gym">
        <!-- error doesn't bubble — catch it in the capture phase to swap a missing image for the placeholder -->
        <div class='col-md-6 indoor_img' @error.capture="image_failed = true">
            <div class="product-image" v-if="indoor.global_data.new_flag">
                <div class="discount-percent-badge discount_percent_badge_for_indoor discount-badge-fourty">NEW</div>
            </div>
            <span v-if="alert_status" :class="['indoor_status_ribbon', 'indoor_status_ribbon_' + indoor.global_data.work_status]">
                {{ $t('guide.article.work_status.' + indoor.global_data.work_status) }}
            </span>
            <component :is="is_coming_soon ? 'span' : 'router-link'" :to="is_coming_soon ? null : gym_path">
                <site-img v-if="indoor.global_data.image && !image_failed" :src="'/public/images/indoor_img/'+indoor.global_data.image" :img_class="'m-image-hero--image'" :alt='indoor.locale_data.title'/>
                <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'m-image-hero--image'" :alt='indoor.locale_data.title'/>
            </component>
        </div>
        <div class='col-md-6 indoor_text indoot_text_for_mobile'>
            <component :is="is_coming_soon ? 'span' : 'router-link'" :to="is_coming_soon ? null : gym_path">
                <h2>{{ indoor.locale_data.title }}</h2>
            </component>

            <IndoorWorkStatusAlert :status="indoor.global_data.work_status" />

            <transition name="indoor_soon_notice">
                <div v-if="show_soon_notice" class="indoor_soon_notice">
                    <i class="fa fa-smile-o" aria-hidden="true"></i>
                    {{ $t('guide.article.coming_soon_click_notice') }}
                </div>
            </transition>

            <span class="indoor_short_description" v-html="indoor.locale_data.short_description"></span>

            <ul class="indoor_info_list">
                <li v-if="indoor.global_data.price_from">
                    <i class="fa fa-tag" aria-hidden="true"></i>
                    <b>{{ $t('guide.article.prices_from') }}</b>
                    <strong>{{ indoor.global_data.price_from }} GEL</strong>
                </li>
                <!-- Closed / coming soon: the alert above replaces the hours row -->
                <li v-if="!alert_status">
                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                    <b>{{ $t('guide.article.working_time') }}</b>
                    <span v-if="indoor.global_data.work_status === 'no_info' || !has_hours" class="text-muted">
                        {{ $t('guide.article.no_info_working_time') }}
                    </span>
                    <template v-else>
                        <span>{{ short_time(indoor.global_data.open_time) }} &ndash; {{ short_time(indoor.global_data.closed_time) }}</span>
                        <span :class="['indoor_open_badge', 'indoor_open_badge_' + open_state.state]">{{ open_state.label }}</span>
                    </template>
                </li>
            </ul>

        </div>
    </div>
</template>

<script>
    import IndoorWorkStatusAlert from '../IndoorWorkStatusAlert.vue'

    // Minutes since midnight for "HH:mm" or "HH:mm:ss"
    function to_minutes(time) {
        const [h, m] = String(time).split(':').map(Number)
        return h * 60 + (m || 0)
    }

    export default {
        components: {
            IndoorWorkStatusAlert,
        },
        props: [
            'indoor',
        ],
        data() {
            return {
                now: new Date(),
                timer: null,
                show_soon_notice: false,
                image_failed: false,
            }
        },
        computed: {
            is_coming_soon() {
                return this.indoor.global_data.work_status === 'coming_soon'
            },
            gym_path() {
                return 'indoor/' + this.indoor.global_data.url_title
            },
            alert_status() {
                return ['temporarily_closed', 'sport_session_closed', 'coming_soon'].includes(this.indoor.global_data.work_status)
            },
            has_hours() {
                return !!(this.indoor.global_data.open_time && this.indoor.global_data.closed_time)
            },
            // open / soon / closed, with "opens/closes in N min" inside the last hour
            open_state() {
                const open = to_minutes(this.indoor.global_data.open_time)
                const close = to_minutes(this.indoor.global_data.closed_time)
                const now = this.now.getHours() * 60 + this.now.getMinutes()
                // Closing after midnight (e.g. 10:00 – 01:00)
                const is_open = close > open ? (now >= open && now < close) : (now >= open || now < close)

                if (is_open) {
                    const left = (close - now + 1440) % 1440
                    return left <= 60
                        ? { state: 'soon', label: this.$t('guide.article.open_status.closes_in', { min: left }) }
                        : { state: 'open', label: this.$t('guide.article.open_status.open_now') }
                }
                const until = (open - now + 1440) % 1440
                return until <= 60
                    ? { state: 'soon', label: this.$t('guide.article.open_status.opens_in', { min: until }) }
                    : { state: 'closed', label: this.$t('guide.article.open_status.closed_now') }
            },
        },
        mounted() {
            // keep the open/closed hint current while the page stays open
            this.timer = setInterval(() => { this.now = new Date() }, 60000)
        },
        beforeUnmount() {
            clearInterval(this.timer)
        },
        methods: {
            open_gym() {
                if (this.is_coming_soon) {
                    this.show_soon_notice = true
                    return
                }
                this.$router.push((this.$i18n.locale === 'ka' ? '/ka' : '') + '/indoor/' + this.indoor.global_data.url_title)
            },
            short_time(time) {
                return String(time).slice(0, 5)
            },
        }
    }
</script>

<style scoped>
    .indoor-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 7%;
        padding: 20px;
    }

    .indoor-card {
        cursor: pointer;
    }

    .indoor-card-soon {
        cursor: default;
    }

    .indoor_soon_notice {
        background: #eaf6f9;
        border-left: 4px solid #279fbb;
        border-radius: 4px;
        color: #1f7f96;
        padding: 10px 14px;
        margin-bottom: 1em;
    }

    .indoor_soon_notice-enter-active { transition: opacity 0.3s ease; }
    .indoor_soon_notice-enter-from { opacity: 0; }

    .indoor-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        transform: translateY(-5px);
    }

    .indoor_img {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
    }

    .indoor_img {
        min-height: 150px;
    }

    .indoor_img img {
        width: 100%;
        height: auto;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .indoor_img:hover img {
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        filter: brightness(1.05);
    }

    .product-image {
        position: relative;
    }

    .discount_percent_badge_for_indoor {
        position: absolute;
        top: 10px;
        right: 10px;
        margin: 0;
        background: #279fbb;
        color: #fff;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        z-index: 10;
    }

    .indoor_text {
        padding-left: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .indoor_text h2 {
        font-size: 1.8em;
        margin: 0.6em 0 0.6em 0;
        color: #333;
        font-weight: bold;
    }

    .indoor_short_description {
        display: block;
        font-size: 1em;
        line-height: 1.6;
        color: #666;
        margin-bottom: 1em;
    }

    .indoor_text b {
        font-size: 1.2em;
        color: #279fbb;
        margin-bottom: 0.5em;
    }

    .indoor_text p {
        font-size: 1em;
        color: #555;
        margin: 0.5em 0;
    }

    .indoor_text a:hover {
        text-decoration: none;
    }

    .indoor_info_list {
        list-style: none;
        padding: 0;
        margin: 0.5em 0 1.2em;
    }

    .indoor_info_list li {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        color: #555;
    }

    .indoor_info_list .fa {
        color: #279fbb;
        width: 18px;
        text-align: center;
    }

    .indoor_info_list b {
        margin: 0;
    }

    .indoor_open_badge {
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 0.85em;
        font-weight: bold;
        color: #fff;
    }
    .indoor_open_badge_open   { background: #27ae60; }
    .indoor_open_badge_soon   { background: #f39c12; }
    .indoor_open_badge_closed { background: #e74c3c; }


    .indoor_status_ribbon {
        position: absolute;
        top: 12px;
        left: 12px;
        z-index: 10;
        padding: 5px 12px;
        border-radius: 4px;
        color: #fff;
        font-size: 13px;
        font-weight: bold;
        box-shadow: 0 2px 6px rgba(0,0,0,0.25);
    }
    .indoor_status_ribbon_temporarily_closed   { background: #e74c3c; }
    .indoor_status_ribbon_sport_session_closed { background: #f39c12; }
    .indoor_status_ribbon_coming_soon          { background: #279fbb; }

    .discount_percent_badge_for_indoor {
        margin: 5px 18px 0 0;
        background: #279fbb;
        color: #fff;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .indoor-card {
            flex-direction: column;
            padding: 15px;
        }

        .indoor_img {
            margin-bottom: 20px;
        }

        .indoor_text {
            padding-left: 0;
        }

        .indoor_text h2 {
            font-size: 1.5em;
            margin: 1em 0;
        }

        .indoor_text b {
            font-size: 1.1em;
        }

        .indoor_text p {
            font-size: 0.9em;
        }
    }
</style>
