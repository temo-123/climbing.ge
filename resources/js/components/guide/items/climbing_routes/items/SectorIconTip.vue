<template>
    <span
        class="icon-tip"
        :class="{ 'tip-open': isOpen }"
        @mouseenter="isOpen = true"
        @mouseleave="isOpen = false"
        @click.stop="isOpen = !isOpen"
    >
        <img :class="imgClass" :src="src" :alt="title" />
        <span class="tip-text">
            <strong class="tip-title">{{ title }}</strong>
            <span class="tip-desc">{{ desc }}</span>
        </span>
    </span>
</template>

<script>
export default {
    props: {
        src:      { type: String, required: true },
        imgClass: { type: String, default: 'sun_svg' },
        title:    { type: String, required: true },
        desc:     { type: String, required: true },
    },
    data() {
        return { isOpen: false }
    },
    mounted() {
        document.addEventListener('click', this.close)
    },
    beforeUnmount() {
        document.removeEventListener('click', this.close)
    },
    methods: {
        close() { this.isOpen = false }
    }
}
</script>

<style scoped>
.sun_svg, .relief_svg {
    height: 40px;
    margin: 0.25rem;
    filter: drop-shadow(0 1px 1px rgba(0,0,0,0.1));
}

.icon-tip {
    position: relative;
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
}

.tip-text {
    display: none;
    position: absolute;
    bottom: 115%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(30, 30, 30, 0.92);
    color: #fff;
    padding: 10px 14px;
    border-radius: 8px;
    white-space: normal;
    width: 280px;
    z-index: 300;
    pointer-events: none;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.35);
    text-align: left;
}

.tip-title {
    display: block;
    font-size: 1.35rem;
    font-weight: 700;
    margin-bottom: 6px;
    line-height: 1.3;
}

.tip-desc {
    display: block;
    font-size: 1.15rem;
    opacity: 0.88;
    line-height: 1.5;
}

.tip-text::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 6px solid transparent;
    border-top-color: rgba(30, 30, 30, 0.92);
}

.icon-tip:hover .tip-text,
.icon-tip.tip-open .tip-text {
    display: block;
}
</style>
