<template>
    <button class="back-to-top" v-show="showButton" @click="scrollToTop">
        <i class="fa fa-chevron-up" aria-hidden="true"></i>
    </button>
  </template>
  
  <script>
  export default {
    data() {
      return {
        showButton: false
      };
    },
    mounted() {
      window.addEventListener("scroll", this.handleScroll);
    },
    beforeDestroy() {
      window.removeEventListener("scroll", this.handleScroll);
    },
    methods: {
      handleScroll() {
        // Show the button when the user scrolls down 100px
        this.showButton = window.pageYOffset > 100;
      },
      scrollToTop() {
        // Scroll back to the top of the page
        window.scrollTo({
          top: 0,
          behavior: "smooth"
        });
      }
    }
  };
  </script>
  
  <style>
  .back-to-top {
    position: fixed;
    bottom: 20px;
    right: 20px;
    /* display: none; */
    z-index: 1000;
    padding: 10px;
    background-color: #333;
    color: #fff;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    outline: none;
  }
  
  .back-to-top:hover {
    background-color: #555;
  }
  
  @media screen and (max-width: 768px) {
    .back-to-top {
      bottom: 10px;
      right: 10px;
      font-size: 14px;
      padding: 8px;
    }
  }
  </style>
  