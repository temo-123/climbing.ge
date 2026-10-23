<template>
  <!-- type="transition": the background drift is an infinite animation, so Vue must
       wait for the fade transition only (otherwise the leave never finishes) -->
  <transition name="cg-fade" type="transition">
    <div v-if="visible" id="page-preloader" class="preloader_background cg-preloader">
        <div class="cg-preloader__inner">
            <!-- A climber's sketchbook: contour drawings of a mountain range and
                 gear (carabiner, ice axe, hold, quickdraw) draw themselves in turn. -->
            <svg class="cg-sketchbook" viewBox="0 0 360 218" aria-hidden="true">
                <defs>
                    <filter id="cgPencil" x="-5%" y="-5%" width="110%" height="110%">
                        <feTurbulence type="fractalNoise" baseFrequency="0.04" numOctaves="2" seed="7" />
                        <feDisplacementMap in="SourceGraphic" scale="3" xChannelSelector="R" yChannelSelector="G" />
                    </filter>
                </defs>

                <g filter="url(#cgPencil)" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <!-- mountains + contour lines -->
                    <g class="cg-sk cg-sk--peaks">
                        <path pathLength="1" d="M40 112L100 46L126 74L170 20L214 80L236 60L300 112" />
                        <path pathLength="1" d="M156 40l14-20 14 20-7-4-7 6-7-6zM88 60l12-14 10 12-6-3-5 4z" />
                        <path pathLength="1" class="cg-sk__thin" d="M30 122c50-10 90 4 150-4s100 6 150-2M50 132c40-8 80 4 130-2s90 4 120-2" />
                        <path pathLength="1" class="cg-sk__accent" d="M170 20V2l14 5-14 5" />
                    </g>

                    <!-- carabiner -->
                    <g class="cg-sk cg-sk--biner" transform="translate(34 142) scale(1.25)">
                        <path pathLength="1" d="M8 0C2 0-2 6-2 12v28c0 8 5 12 12 12h6c8 0 12-5 12-12l-3-30C32 3 28 0 22 0z" />
                        <path pathLength="1" class="cg-sk__accent" d="M24 4l4 32" />
                    </g>

                    <!-- ice axe -->
                    <g class="cg-sk cg-sk--axe" transform="translate(108 146) scale(1.2)">
                        <path pathLength="1" d="M6 52c8-16 16-32 30-48" />
                        <path pathLength="1" d="M33 5l22 10c4 2 3 5-1 5L32 13M36 4l-14-9c-3-2-5 1-2 3l12 10" />
                        <path pathLength="1" class="cg-sk__accent" d="M2 46l8 6-3 6" />
                    </g>

                    <!-- climbing hold with its bolt -->
                    <g class="cg-sk cg-sk--hold" transform="translate(190 148) scale(1.2)">
                        <path pathLength="1" d="M4 30C0 14 12 2 28 2s28 10 26 24c-2 8-8 10-14 7s-14-3-20 2-14 8-16-5z" />
                        <circle pathLength="1" class="cg-sk__accent" cx="28" cy="18" r="5" />
                    </g>

                    <!-- quickdraw -->
                    <g class="cg-sk cg-sk--draw" transform="translate(300 140) scale(1.2)">
                        <path pathLength="1" d="M-6 0c-3 0-5 3-5 6v8c0 5 3 7 6 7h8c4 0 6-3 6-7V6c0-3-2-6-6-6z" />
                        <path pathLength="1" class="cg-sk__accent" d="M-4 20h8v16h-8z" />
                        <path pathLength="1" d="M-7 35c-4 0-6 3-6 7v8c0 5 4 8 8 8h8c5 0 7-3 7-8v-8c0-4-3-7-7-7z" />
                    </g>
                </g>
            </svg>
            <p class="cg-preloader__text">{{ $t('global.loading') }}<span class="preloader_dot">.</span><span class="preloader_dot">.</span><span class="preloader_dot">.</span></p>
        </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'Preloader',
  data() {
    return {
      visible: true,
    }
  },
  mounted() {
    this.preloader();
  },
  methods: {
    // Hide as soon as the page has loaded instead of always blocking for 2s:
    // at least 450ms (so it doesn't just flash), at most 2s (slow images).
    preloader() {
      const started = performance.now();
      const MIN = 450, MAX = 2000;
      let done = false;
      const hide = () => {
        if (done) return;
        done = true;
        const wait = Math.max(0, MIN - (performance.now() - started));
        setTimeout(() => { this.visible = false; }, wait);
      };
      if (document.readyState === 'complete') hide();
      else window.addEventListener('load', hide, { once: true });
      setTimeout(hide, MAX);
    }
  }
}
</script>
