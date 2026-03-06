"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_shop_pages_pages_ClimbingWallPriceColculatePage_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: ['title', 'description', 'image'],
  data: function data() {
    return {
      locale: localStorage.getItem('lang'),
      mix_site_url: "climbing.loc",
      mix_app_ssh: "http://",
      mix_site_name: "climbing.ge",
      action_image: "http://" + '' + "climbing.loc" + '/' + this.image
    };
  },
  metaInfo: function metaInfo() {
    return {
      title: this.title + ' - ' + 'shop.' + this.mix_site_url,
      // title: 'test title',
      htmlAttrs: {
        lang: this.locale
      },
      link: [
      // { rel: 'stylesheet', href: '/css/index.css' },
      {
        rel: 'canonical',
        href: '/'
      }],
      meta: [{
        name: 'viewport',
        content: 'width=device-width, initial-scale=1'
      }, {
        charset: 'utf-8'
      }, {
        name: 'description',
        content: this.description
      }, {
        title: 'Default App Title',
        titleTemplate: '%s | ' + this.title + ' - ' + 'shop.' + this.mix_site_url
      }, {
        canonical: 'Default App Title',
        content: '%s | ' + this.title + ' - ' + 'shop.' + this.mix_site_url
      },
      // https://www.digitalocean.com/community/tutorials/vuejs-vue-seo-tips
      // OpenGraph data (Most widely used)
      {
        property: 'og:title',
        content: this.title + ' - ' + 'shop.' + this.mix_site_url
      }, {
        property: 'og:site_name',
        content: this.mix_site_name
      },
      // The list of types is available here: http://ogp.me/#types
      {
        property: 'og:type',
        content: 'website'
      },
      // Should the the same as your canonical link, see below.
      {
        property: 'og:url',
        content: this.mix_app_ssh + 'shop.' + this.mix_site_url
      }, {
        property: 'og:image',
        content: this.action_image
      },
      // Often the same as your meta description, but not always.
      {
        property: 'og:description',
        content: this.description
      },
      // Twitter card
      {
        name: 'twitter:card',
        content: 'summary'
      }, {
        name: 'twitter:site',
        content: this.mix_app_ssh + 'shop.' + this.mix_site_url
      }, {
        name: 'twitter:title',
        content: this.title + ' - ' + 'shop.' + this.mix_site_url
      }, {
        name: 'twitter:description',
        content: this.description
      },
      // Your twitter handle, if you have one.
      {
        name: 'twitter:creator',
        content: '@alligatorio'
      }, {
        name: 'twitter:image:src',
        content: this.action_image
      },
      // Google / Schema.org markup:
      {
        itemprop: 'name',
        content: this.title + ' - ' + 'shop.' + this.mix_site_url
      }, {
        itemprop: 'description',
        content: this.description
      }, {
        itemprop: 'image',
        content: this.action_image
      }]
    };
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/pages/ClimbingWallPriceColculatePage.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/pages/ClimbingWallPriceColculatePage.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _items_MetaDataComponent__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../items/MetaDataComponent */ "./resources/js/components/shop/items/MetaDataComponent.vue");
// import { VueHtml2pdf } from 'vue-html2pdf'

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    metaData: _items_MetaDataComponent__WEBPACK_IMPORTED_MODULE_0__["default"]
    // VueHtml2pdf
  },

  data: function data() {
    return {
      active_image: 'non',
      wall_height_for_mat: false,
      hold_include: false,
      width: 0,
      height: 0,
      depth: 0,
      wall_squareness: 0,
      mat_squareness: 0,
      mat_width: 0,
      mat_height: 0,
      mat_depth: 0,
      holds_quantyty_for_meter: 0,
      holds_total_quantity: 0,
      holds_total_price: 0,
      wall_price_sum: 0,
      mat_price_sum: 0,
      holds_price_sum: 0,
      total_price_sum: 0
    };
  },
  mounted: function mounted() {
    //
  },
  methods: {
    generateReport: function generateReport() {
      this.$refs.html2Pdf.generatePdf();
    },
    set_actyve_image: function set_actyve_image(image) {
      this.active_image = image;
      if (this.height > 5) {
        this.wall_height_for_mat = true;
        this.mat_width = 0;
        this.mat_height = 0;
        this.mat_depth = 0;
        this.mat_price_sum = 0;
      } else if (this.height < 6) {
        this.wall_height_for_mat = false;
      }
      this.colculate_wall_price();
    },
    inserting_holds: function inserting_holds() {
      this.hold_include = !this.hold_include;
    },
    colculate_holds_price: function colculate_holds_price() {
      this.holds_total_quantity = this.wall_squareness * this.holds_quantyty_for_meter;
      this.holds_total_price = this.holds_total_quantity * 15;
      this.colculate_total_price();
    },
    colculate_wall_price: function colculate_wall_price() {
      if (this.depth > 0 && this.width > 0 && this.height > 0) {
        this.wall_squareness = this.depth * this.width * this.height;
        this.wall_price_sum = this.wall_squareness * 90;
      } else if (this.width > 0 && this.height > 0 && this.depth == 0) {
        this.wall_squareness = this.height * this.width;
        this.wall_price_sum = this.wall_squareness * 100;
      }
      if (!this.wall_height_for_mat) {
        this.colculate_mat_size();
      } else {
        this.colculate_total_price();
      }
    },
    colculate_mat_size: function colculate_mat_size() {
      this.mat_width = this.width;
      this.mat_squareness = this.mat_depth * this.mat_width;
      this.mat_squareness = Math.ceil(this.mat_squareness);
      if (this.depth > 0 && this.width > 0 && this.height > 0) {
        this.depth = Number(this.depth);
        var procent = this.depth * 20 / 100;
        this.mat_depth = this.depth + procent;
        this.mat_height = this.height * 0.08;
        this.mat_height = this.mat_height;
      } else if (this.width > 0 && this.height > 0) {
        this.mat_height = this.height * 0.08;
        this.mat_height = this.mat_height;
        this.mat_depth = 1.5;
      }
      this.colculate_mat_price();
    },
    colculate_mat_price: function colculate_mat_price() {
      if (this.mat_depth > 0 && this.mat_width > 0 && this.mat_height > 0) {
        this.mat_price_sum = this.mat_depth * this.mat_width * this.mat_height * 400;
      } else if (this.mat_width > 0 && this.mat_height > 0) {
        this.mat_price_sum = this.mat_width * this.mat_height * 400;
      }
      this.colculate_total_price();
    },
    colculate_total_price: function colculate_total_price() {
      this.wall_price_sum = Math.ceil(this.wall_price_sum);
      this.mat_price_sum = Math.ceil(this.mat_price_sum);
      this.total_price_sum = this.wall_price_sum + this.mat_price_sum + this.holds_total_price;
      this.total_price_sum = Math.ceil(this.total_price_sum);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=template&id=24818c71&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=template&id=24818c71& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function render() {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div");
};
var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/pages/ClimbingWallPriceColculatePage.vue?vue&type=template&id=a1bf826e&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/pages/ClimbingWallPriceColculatePage.vue?vue&type=template&id=a1bf826e& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function render() {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "container"
  }, [_c("h1", {
    staticClass: "page_title"
  }, [_vm._v("Climbing wall price colculator")]), _vm._v(" "), _vm._m(0), _vm._v(" "), _c("h3", [_c("span", {
    domProps: {
      innerHTML: _vm._s(this.$siteData.message)
    }
  })]), _vm._v(" "), _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-6"
  }, [_c("div", {
    staticClass: "row"
  }, [_c("form", [_c("div", {
    staticClass: "row"
  }, [_c("p", [_vm._v("Insert climbing wall size.")]), _vm._v(" "), _c("div", {
    staticClass: "col-md-4"
  }, [_c("p", [_vm._v("Height")]), _vm._v(" "), _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "form-group"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.height,
      expression: "height"
    }],
    staticClass: "form-control",
    attrs: {
      type: "number",
      name: "height",
      id: "height",
      min: "0",
      placeholder: _vm.$t("shop.wall.height")
    },
    domProps: {
      value: _vm.height
    },
    on: {
      click: function click($event) {
        return _vm.set_actyve_image("height");
      },
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.height = $event.target.value;
      }
    }
  })])])])]), _vm._v(" "), _c("div", {
    staticClass: "col-md-4"
  }, [_c("p", [_vm._v("Width")]), _vm._v(" "), _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "form-group"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.width,
      expression: "width"
    }],
    staticClass: "form-control",
    attrs: {
      type: "number",
      name: "width",
      id: "width",
      min: "0",
      placeholder: _vm.$t("shop.wall.width")
    },
    domProps: {
      value: _vm.width
    },
    on: {
      click: function click($event) {
        return _vm.set_actyve_image("width");
      },
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.width = $event.target.value;
      }
    }
  })])])])]), _vm._v(" "), _c("div", {
    staticClass: "col-md-4"
  }, [_c("p", [_vm._v("Depth")]), _vm._v(" "), _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "form-group"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.depth,
      expression: "depth"
    }],
    staticClass: "form-control",
    attrs: {
      type: "number",
      name: "depth",
      id: "depth",
      min: "0",
      placeholder: _vm.$t("shop.wall.depth")
    },
    domProps: {
      value: _vm.depth
    },
    on: {
      click: function click($event) {
        return _vm.set_actyve_image("depth");
      },
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.depth = $event.target.value;
      }
    }
  })])])])])]), _vm._v(" "), !_vm.wall_height_for_mat ? _c("div", {
    staticClass: "row"
  }, [_c("p", [_vm._v("Mat size colculate automaticly.")]), _vm._v(" "), _c("div", {
    staticClass: "col-md-4"
  }, [_c("p", [_vm._v("Mat height")]), _vm._v(" "), _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "form-group"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.mat_height,
      expression: "mat_height"
    }],
    staticClass: "form-control",
    attrs: {
      type: "number",
      name: "mat_height",
      min: "0.1",
      id: "mat_height",
      placeholder: _vm.$t("shop.wall.mat height")
    },
    domProps: {
      value: _vm.mat_height
    },
    on: {
      click: function click($event) {
        return _vm.set_actyve_image("mat_height");
      },
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.mat_height = $event.target.value;
      }
    }
  })])])])]), _vm._v(" "), _c("div", {
    staticClass: "col-md-4"
  }, [_c("p", [_vm._v("Mat width")]), _vm._v(" "), _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "form-group"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.mat_width,
      expression: "mat_width"
    }],
    staticClass: "form-control",
    attrs: {
      type: "number",
      name: "mat_width",
      min: "0",
      id: "mat_width",
      placeholder: _vm.$t("shop.wall.mat width")
    },
    domProps: {
      value: _vm.mat_width
    },
    on: {
      click: function click($event) {
        return _vm.set_actyve_image("mat_width");
      },
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.mat_width = $event.target.value;
      }
    }
  })])])])]), _vm._v(" "), _c("div", {
    staticClass: "col-md-4"
  }, [_c("p", [_vm._v("Mat depth")]), _vm._v(" "), _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "form-group"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.mat_depth,
      expression: "mat_depth"
    }],
    staticClass: "form-control",
    attrs: {
      type: "number",
      name: "mat_depth",
      min: "0",
      id: "mat_depth",
      placeholder: _vm.$t("shop.wall.mat depth")
    },
    domProps: {
      value: _vm.mat_depth
    },
    on: {
      click: function click($event) {
        return _vm.set_actyve_image("mat_depth");
      },
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.mat_depth = $event.target.value;
      }
    }
  })])])])])]) : _c("div", {
    staticClass: "row"
  }, [_c("p", [_vm._v("wall is more then 5 meter, it is so height for mat. after 5 meters you need rops and belay system for this height.")])]), _vm._v(" "), _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-4"
  }, [_c("p", [_vm._v("Do you want includ holds in price?")]), _vm._v(" "), _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "form-group"
  }, [_c("input", {
    attrs: {
      type: "checkbox",
      name: "hold_insert",
      id: "hold_insert"
    },
    on: {
      click: function click($event) {
        return _vm.inserting_holds();
      }
    }
  })])])])]), _vm._v(" "), _vm.hold_include && _vm.wall_squareness > 0 ? _c("div", {
    staticClass: "col-md-4"
  }, [_c("p", [_vm._v("How many holds you want on 1m2™ on wall?")]), _vm._v(" "), _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "form-group"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.holds_quantyty_for_meter,
      expression: "holds_quantyty_for_meter"
    }],
    staticClass: "form-control",
    attrs: {
      type: "number",
      name: "holds_quantyty",
      id: "holds_quantyty",
      placeholder: _vm.$t("shop.wall.holds_quantyty")
    },
    domProps: {
      value: _vm.holds_quantyty_for_meter
    },
    on: {
      click: function click($event) {
        return _vm.colculate_holds_price();
      },
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.holds_quantyty_for_meter = $event.target.value;
      }
    }
  })])])])]) : _vm.wall_squareness == 0 ? _c("div", {
    staticClass: "col-md-4"
  }, [_c("p", [_vm._v("You need insert wall size!")])]) : _vm._e()])]), _vm._v(" "), _c("button", {
    staticClass: "btn btn-default btn-send main-btn",
    on: {
      click: function click($event) {
        return _vm.colculate_wall_price();
      }
    }
  }, [_vm._v("Colculate price")])]), _vm._v(" "), _c("div", {
    staticClass: "row"
  }, [_c("section", {
    attrs: {
      slot: "pdf-content"
    },
    slot: "pdf-content"
  }, [_c("table", {
    staticClass: "table col-md-12 table table-hover"
  }, [_c("tbody", [_c("tr", [_c("td", [_vm._v("Squareness of the wall")]), _vm._v(" "), _c("td", [_vm._v("|")]), _vm._v(" "), _c("td", [_vm._v(_vm._s(_vm.wall_squareness) + " (1m2™)")])]), _vm._v(" "), _c("tr", [_c("td", [_vm._v("Wall price")]), _vm._v(" "), _c("td", [_vm._v("|")]), _vm._v(" "), _c("td", [_vm._v(_vm._s(_vm.wall_price_sum) + " $")])]), _vm._v(" "), !_vm.wall_height_for_mat ? _c("tr", [_c("td", [_vm._v("--")]), _vm._v(" "), _c("td", [_vm._v("--")]), _vm._v(" "), _c("td", [_vm._v("--")])]) : _vm._e(), _vm._v(" "), !_vm.wall_height_for_mat ? _c("tr", [_c("td", [_vm._v("Squareness of the mat")]), _vm._v(" "), _c("td", [_vm._v("|")]), _vm._v(" "), _c("td", [_vm._v(_vm._s(_vm.mat_squareness) + " (1m2™)")])]) : _vm._e(), _vm._v(" "), !_vm.wall_height_for_mat ? _c("tr", [_c("td", [_vm._v("Mat price")]), _vm._v(" "), _c("td", [_vm._v("|")]), _vm._v(" "), _c("td", [_vm._v(_vm._s(_vm.mat_price_sum) + " $")])]) : _vm._e(), _vm._v(" "), _vm.hold_include ? _c("tr", [_c("td", [_vm._v("--")]), _vm._v(" "), _c("td", [_vm._v("--")]), _vm._v(" "), _c("td", [_vm._v("--")])]) : _vm._e(), _vm._v(" "), _vm.hold_include ? _c("tr", [_c("td", [_vm._v("Holds qouantyty for 1 m")]), _vm._v(" "), _c("td", [_vm._v("|")]), _vm._v(" "), _c("td", [_vm._v(_vm._s(_vm.holds_quantyty_for_meter))])]) : _vm._e(), _vm._v(" "), _vm.hold_include ? _c("tr", [_c("td", [_vm._v("Holds total quantity")]), _vm._v(" "), _c("td", [_vm._v("|")]), _vm._v(" "), _c("td", [_vm._v(_vm._s(_vm.holds_total_quantity))])]) : _vm._e(), _vm._v(" "), _vm.hold_include ? _c("tr", [_c("td", [_vm._v("Holds total price")]), _vm._v(" "), _c("td", [_vm._v("|")]), _vm._v(" "), _c("td", [_vm._v(_vm._s(_vm.holds_total_price) + " $")])]) : _vm._e()])])]), _vm._v(" "), _vm.total_price_sum != 0 ? _c("h2", {
    staticClass: "float_right"
  }, [_vm._v("Total price = " + _vm._s(_vm.total_price_sum) + " $")]) : _vm._e(), _vm._v(" "), _vm.total_price_sum != 0 ? _c("button", {
    staticClass: "btn btn-default btn-send main-btn",
    on: {
      click: function click($event) {
        return _vm.generateReport();
      }
    }
  }, [_vm._v("Print")]) : _vm._e()])]), _vm._v(" "), _c("div", {
    staticClass: "col-md-6"
  }, [_c("img", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: _vm.active_image == "non",
      expression: "active_image == 'non'"
    }],
    attrs: {
      src: "/public/images/site_img/climbing_wall_img/wall.png",
      alt: "climbing wall"
    }
  }), _vm._v(" "), _c("img", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: !_vm.wall_height_for_mat && _vm.active_image == "height",
      expression: "!wall_height_for_mat && active_image == 'height'"
    }],
    attrs: {
      src: "/public/images/site_img/climbing_wall_img/wall-h.png",
      alt: "climbing wall height"
    }
  }), _vm._v(" "), _c("img", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: !_vm.wall_height_for_mat && _vm.active_image == "width",
      expression: "!wall_height_for_mat && active_image == 'width'"
    }],
    attrs: {
      src: "/public/images/site_img/climbing_wall_img/wall-w.png",
      alt: "climbing wall width"
    }
  }), _vm._v(" "), _c("img", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: !_vm.wall_height_for_mat && _vm.active_image == "depth",
      expression: "!wall_height_for_mat && active_image == 'depth'"
    }],
    attrs: {
      src: "/public/images/site_img/climbing_wall_img/wall-d.png",
      alt: "climbing wall depth"
    }
  }), _vm._v(" "), _c("img", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: _vm.wall_height_for_mat && _vm.active_image == "height",
      expression: "wall_height_for_mat && active_image == 'height'"
    }],
    attrs: {
      src: "/public/images/site_img/climbing_wall_img/wall-h no mat.png",
      alt: "climbing wall height"
    }
  }), _vm._v(" "), _c("img", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: _vm.wall_height_for_mat && _vm.active_image == "width",
      expression: "wall_height_for_mat && active_image == 'width'"
    }],
    attrs: {
      src: "/public/images/site_img/climbing_wall_img/wall-w no mat.png",
      alt: "climbing wall width"
    }
  }), _vm._v(" "), _c("img", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: _vm.wall_height_for_mat && _vm.active_image == "depth",
      expression: "wall_height_for_mat && active_image == 'depth'"
    }],
    attrs: {
      src: "/public/images/site_img/climbing_wall_img/wall-d no mat.png",
      alt: "climbing wall depth"
    }
  }), _vm._v(" "), _c("img", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: _vm.active_image == "mat_height",
      expression: "active_image == 'mat_height'"
    }],
    attrs: {
      src: "/public/images/site_img/climbing_wall_img/mat-h.png",
      alt: "climbing wall mat height"
    }
  }), _vm._v(" "), _c("img", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: _vm.active_image == "mat_width",
      expression: "active_image == 'mat_width'"
    }],
    attrs: {
      src: "/public/images/site_img/climbing_wall_img/mat-w.png",
      alt: "climbing wall mat width"
    }
  }), _vm._v(" "), _c("img", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: _vm.active_image == "mat_depth",
      expression: "active_image == 'mat_depth'"
    }],
    attrs: {
      src: "/public/images/site_img/climbing_wall_img/mat-d.png",
      alt: "climbing wall mat depth"
    }
  })])]), _vm._v(" "), _c("metaData", {
    attrs: {
      title: _vm.$t("shop.meta.climbing wall"),
      description: "Climbing wall colculator. Colculate climbing wall price.",
      image: "/public/images/site_img/climbing_wall_img/wall.png"
    }
  })], 1);
};
var staticRenderFns = [function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "bar"
  }, [_c("i", {
    staticClass: "fa fa-calculator",
    attrs: {
      "aria-hidden": "true"
    }
  })]);
}];
render._withStripped = true;


/***/ }),

/***/ "./resources/js/components/shop/items/MetaDataComponent.vue":
/*!******************************************************************!*\
  !*** ./resources/js/components/shop/items/MetaDataComponent.vue ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _MetaDataComponent_vue_vue_type_template_id_24818c71___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MetaDataComponent.vue?vue&type=template&id=24818c71& */ "./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=template&id=24818c71&");
/* harmony import */ var _MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MetaDataComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _MetaDataComponent_vue_vue_type_template_id_24818c71___WEBPACK_IMPORTED_MODULE_0__.render,
  _MetaDataComponent_vue_vue_type_template_id_24818c71___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/shop/items/MetaDataComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/shop/pages/pages/ClimbingWallPriceColculatePage.vue":
/*!*************************************************************************************!*\
  !*** ./resources/js/components/shop/pages/pages/ClimbingWallPriceColculatePage.vue ***!
  \*************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ClimbingWallPriceColculatePage_vue_vue_type_template_id_a1bf826e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ClimbingWallPriceColculatePage.vue?vue&type=template&id=a1bf826e& */ "./resources/js/components/shop/pages/pages/ClimbingWallPriceColculatePage.vue?vue&type=template&id=a1bf826e&");
/* harmony import */ var _ClimbingWallPriceColculatePage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ClimbingWallPriceColculatePage.vue?vue&type=script&lang=js& */ "./resources/js/components/shop/pages/pages/ClimbingWallPriceColculatePage.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ClimbingWallPriceColculatePage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ClimbingWallPriceColculatePage_vue_vue_type_template_id_a1bf826e___WEBPACK_IMPORTED_MODULE_0__.render,
  _ClimbingWallPriceColculatePage_vue_vue_type_template_id_a1bf826e___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/shop/pages/pages/ClimbingWallPriceColculatePage.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MetaDataComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/shop/pages/pages/ClimbingWallPriceColculatePage.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************!*\
  !*** ./resources/js/components/shop/pages/pages/ClimbingWallPriceColculatePage.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ClimbingWallPriceColculatePage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ClimbingWallPriceColculatePage.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/pages/ClimbingWallPriceColculatePage.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ClimbingWallPriceColculatePage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=template&id=24818c71&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=template&id=24818c71& ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_template_id_24818c71___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_template_id_24818c71___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_template_id_24818c71___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MetaDataComponent.vue?vue&type=template&id=24818c71& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=template&id=24818c71&");


/***/ }),

/***/ "./resources/js/components/shop/pages/pages/ClimbingWallPriceColculatePage.vue?vue&type=template&id=a1bf826e&":
/*!********************************************************************************************************************!*\
  !*** ./resources/js/components/shop/pages/pages/ClimbingWallPriceColculatePage.vue?vue&type=template&id=a1bf826e& ***!
  \********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ClimbingWallPriceColculatePage_vue_vue_type_template_id_a1bf826e___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ClimbingWallPriceColculatePage_vue_vue_type_template_id_a1bf826e___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ClimbingWallPriceColculatePage_vue_vue_type_template_id_a1bf826e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ClimbingWallPriceColculatePage.vue?vue&type=template&id=a1bf826e& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/pages/ClimbingWallPriceColculatePage.vue?vue&type=template&id=a1bf826e&");


/***/ })

}]);