<template>
    <div class="smt-card">

        <!-- Image -->
        <router-link :to="'/summit/' + summit.url_title" class="smt-card__img-wrap">
            <svg viewBox="0 0 400 200" xmlns="http://www.w3.org/2000/svg" class="smt-card__svg">
                <defs>
                    <linearGradient :id="'smt-sky-' + summit.id" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%"   stop-color="#0d1f33"/>
                        <stop offset="100%" stop-color="#1a4a6b"/>
                    </linearGradient>
                </defs>
                <rect width="400" height="200" :fill="'url(#smt-sky-' + summit.id + ')'"/>
                <!-- Stars -->
                <circle cx="30"  cy="20" r="1.5" fill="white" opacity="0.8"/>
                <circle cx="100" cy="14" r="1"   fill="white" opacity="0.7"/>
                <circle cx="200" cy="10" r="1.5" fill="white" opacity="0.9"/>
                <circle cx="300" cy="16" r="1"   fill="white" opacity="0.6"/>
                <circle cx="370" cy="12" r="1.5" fill="white" opacity="0.8"/>
                <!-- Moon -->
                <circle cx="370" cy="28" r="12" fill="#f5e6a3" opacity="0.8"/>
                <circle cx="377" cy="22" r="9"  fill="#1a4a6b" opacity="0.9"/>
                <!-- Mountains -->
                <polygon points="0,200 100,90 200,200"        fill="#0c3320" opacity="0.65"/>
                <polygon points="200,200 310,70 400,140 400,200" fill="#0c3320" opacity="0.65"/>
                <polygon points="0,200 130,50 270,200"        fill="#145a32"/>
                <polygon points="120,200 260,30 400,200"      fill="#1d7a48"/>
                <!-- Snow cap -->
                <polygon points="240,75 260,30 280,75 270,60 250,60" fill="white" opacity="0.95"/>
                <line x1="260" y1="42" x2="250" y2="58" stroke="white" stroke-width="1.5" opacity="0.5"/>
                <line x1="260" y1="46" x2="270" y2="58" stroke="white" stroke-width="1.5" opacity="0.4"/>
            </svg>

            <!-- Height badge -->
            <span v-if="summit.height" class="smt-card__height-badge">
                <i class="fa fa-arrow-up"></i> {{ summit.height }}m
            </span>

            <!-- Hover arrow -->
            <div class="smt-card__hover-overlay">
                <i class="fa fa-arrow-right"></i>
            </div>
        </router-link>

        <!-- Body -->
        <div class="smt-card__body">
            <h3 class="smt-card__title">{{ summit.title }}</h3>
            <p v-if="summit.ka_title" class="smt-card__subtitle">{{ summit.ka_title }}</p>
            <p v-if="summit.mount_route_name" class="smt-card__route">
                <i class="fa fa-flag text-success"></i> {{ summit.mount_route_name }}
            </p>

            <div class="smt-card__actions">
                <router-link :to="'/summit/' + summit.url_title" class="btn btn-default btn-sm btn-send main-btn">
                    <i class="fa fa-info-circle"></i> Details
                </router-link>
                <router-link :to="'/make_ascent/' + summit.id" class="btn btn-success btn-sm">
                    <i class="fa fa-flag-checkered"></i> Ascent
                </router-link>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: 'SummitCard',
    props: {
        summit: {
            type: Object,
            required: true,
        },
    },
}
</script>

<style scoped>
.smt-card {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
    background: #fff;
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: box-shadow 0.25s ease, transform 0.25s ease;
}
.smt-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.18);
    transform: translateY(-3px);
}

/* ── Image zone ── */
.smt-card__img-wrap {
    position: relative;
    display: block;
    overflow: hidden;
    text-decoration: none;
}
.smt-card__svg {
    width: 100%;
    height: 175px;
    display: block;
    transition: transform 0.35s ease;
}
.smt-card__img-wrap:hover .smt-card__svg {
    transform: scale(1.06);
}

/* Height badge — bottom-left of image */
.smt-card__height-badge {
    position: absolute;
    bottom: 8px;
    left: 10px;
    background: rgba(0, 0, 0, 0.55);
    color: #fff;
    font-size: 12px;
    padding: 3px 8px;
    border-radius: 20px;
    pointer-events: none;
}

/* Arrow overlay on hover */
.smt-card__hover-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0);
    transition: background 0.3s;
}
.smt-card__hover-overlay i {
    font-size: 26px;
    color: #fff;
    opacity: 0;
    transition: opacity 0.3s;
}
.smt-card__img-wrap:hover .smt-card__hover-overlay {
    background: rgba(0, 0, 0, 0.4);
}
.smt-card__img-wrap:hover .smt-card__hover-overlay i {
    opacity: 1;
}

/* ── Body ── */
.smt-card__body {
    padding: 14px 16px 16px;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.smt-card__title {
    margin: 0 0 4px;
    font-size: 1rem;
    font-weight: 600;
    color: #222;
    line-height: 1.3;
}
.smt-card__subtitle {
    margin: 0 0 6px;
    font-size: 13px;
    color: #888;
}
.smt-card__route {
    margin: 0 0 8px;
    font-size: 13px;
    color: #555;
}
.smt-card__actions {
    margin-top: auto;
    padding-top: 10px;
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}
</style>
