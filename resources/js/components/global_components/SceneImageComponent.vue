<template>
    <!-- One of the animated climbing scenes in public/images/scenes/<name>.svg
         (flat style shared with the 404 climber). Each scene is a
         self-contained SVG with CSS animations and a reduced-motion fallback,
         so it costs nothing in the JS bundle and only loads where it's shown.
         Decorative: the page text says what happened. -->
    <img
        class="cg-scene-img"
        :class="{ 'cg-scene-img--wide': wide }"
        :src="'/images/scenes/' + name + '.svg'"
        alt=""
        aria-hidden="true"
        loading="lazy"
        decoding="async"
    />
</template>

<script>
export default {
    name: 'SceneImage',
    props: {
        // 401 · 403 · 419 · 429 · 500 · 503 · lost · summit · bolting · pack · empty
        name: { type: String, required: true },
        // landscape scenes (summit, bolting, pack, empty) are 360×240
        wide: { type: Boolean, default: false },
    },
};
</script>
