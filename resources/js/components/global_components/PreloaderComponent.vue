<template>
  <transition name="fade">
    <div v-if="visible" id="page-preloader" class="preloader_background">
        <div class="loader-container">
          <div class="loader-wrapper">
            <div class="loader"></div>
          </div>
          <p class="loading-text">Loading<span class="preloader_dot">.</span><span class="preloader_dot">.</span><span class="preloader_dot">.</span></p>
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
    preloader() {
      setTimeout(() => {
        this.visible = false;
      }, 2000);
    }
  }
}
</script>

<style> 
 .preloader_background {
     position: fixed;
     left: 0;
     top: 0;
     width: 100%;
     height: 100%;
     background: radial-gradient(circle, #26c6eb 0% 0%, #1c6b87 100%);
     z-index: 1035;
     display: flex;
     justify-content: center;
     align-items: center;
 }
 .loader-container {
     display: flex;
     flex-direction: column;
     align-items: center;
     justify-content: center;
     text-align: center;
     position: relative;
 }

 .loader-wrapper {
     position: absolute;
     top: 50%;
     left: 50%;
 }

 .loader {
    width: 7em;
    height: 7em;
    border: 8px solid #e0f7ff;
    border-radius: 50%;
    border-top-color: #00bfff;
    animation: 1.5s spin infinite linear;
 }

 .loading-text {
     position: absolute;
     top: 50%;
     left: 50%;
     transform: translate(-50%, 5em);
     font-size: 24px;
     color: #e0f7ff;
     font-weight: bold;
     text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
     white-space: nowrap;
 }

 .preloader_dot {
     animation: blink 1.5s infinite;
 }

 .preloader_dot:nth-child(1) {
     animation-delay: 0s;
 }

 .preloader_dot:nth-child(2) {
     animation-delay: 0.5s;
 }

 .preloader_dot:nth-child(3) {
     animation-delay: 1s;
 }

 @keyframes blink {
     0%, 50% { opacity: 0; }
     51%, 100% { opacity: 1; }
 }

 .fade-enter-active, .fade-leave-active {
     transition: opacity 1s;
 }

 .fade-enter, .fade-leave-to {
     opacity: 0;
 }
</style>
