"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_site_pages_lists_ProjectsList_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/global_components/EmptyPageComponent.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/global_components/EmptyPageComponent.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      num: 0,
      publicPath: window.location.protocol + '//' + window.location.hostname
    };
  },
  components: {},
  mounted: function mounted() {
    this.select_image();
  },
  watch: {
    '$route': function $route(to, from) {
      this.select_image();
    }
  },
  methods: {
    select_image: function select_image() {
      this.num = Math.floor(Math.random() * 4);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/MetaDataComponent.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/MetaDataComponent.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _unhead_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @unhead/vue */ "./node_modules/@unhead/vue/dist/shared/vue.f36acd1f.mjs");
/* harmony import */ var _unhead_vue_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @unhead/vue/components */ "./node_modules/@unhead/vue/dist/components.mjs");


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: ['title', 'description', 'image'],
  components: {
    useHead: _unhead_vue__WEBPACK_IMPORTED_MODULE_0__.u,
    Head: _unhead_vue_components__WEBPACK_IMPORTED_MODULE_1__.Head
  },
  data: function data() {
    return {
      locale: localStorage.getItem('lang'),
      mix_site_url: "climbing.loc",
      mix_app_ssh: "http://",
      mix_site_name: "climbing.ge",
      action_image: "http://" + '' + "climbing.loc" + '/' + this.image
    };
  },
  // useHead: {
  //     title: this.title + ' - ' + this.mix_site_url,
  //     meta: [
  //         {
  //             name: 'description',
  //             content: 'My page description',
  //             image: 'action_image'
  //         },
  //     ],
  // },
  metaInfo: function metaInfo() {
    return {
      title: this.title + ' - ' + this.mix_site_url,
      htmlAttrs: {
        lang: this.locale,
        amp: true
      },
      link: [
      // { rel: 'stylesheet', href: '/css/index.css' },
      {
        rel: 'canonical',
        href: window.location.pathname
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
        titleTemplate: '%s | ' + this.title + ' - ' + this.mix_site_url
      }, {
        canonical: 'Default App Title',
        content: '%s | ' + this.title + ' - ' + this.mix_site_url
      },
      // https://www.digitalocean.com/community/tutorials/vuejs-vue-seo-tips
      // OpenGraph data (Most widely used)
      {
        property: 'og:title',
        content: this.title + ' - ' + this.mix_site_url
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
        content: this.mix_app_ssh + this.mix_site_url
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
        content: this.mix_app_ssh + this.mix_site_url
      }, {
        name: 'twitter:title',
        content: this.title + ' - ' + this.mix_site_url
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
        content: this.title + ' - ' + this.mix_site_url
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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: ['article', 'image_dir', 'route'],
  data: function data() {
    return {
      // 
    };
  },
  components: {},
  mounted: function mounted() {
    // 
  },
  methods: {}
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/lists/ProjectsList.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/lists/ProjectsList.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _items_cards_ArticleCardComponent__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../items/cards/ArticleCardComponent */ "./resources/js/components/site/items/cards/ArticleCardComponent.vue");
/* harmony import */ var _global_components_EmptyPageComponent__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../global_components/EmptyPageComponent */ "./resources/js/components/global_components/EmptyPageComponent.vue");
/* harmony import */ var vue_content_loader__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! vue-content-loader */ "./node_modules/vue-content-loader/dist/vue-content-loader.es.js");
/* harmony import */ var _items_MetaDataComponent__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../items/MetaDataComponent */ "./resources/js/components/site/items/MetaDataComponent.vue");

// import emptyPageComponent from '../items/EmptyPageComponent'



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      projects: [],
      indoor_article_loading: true
    };
  },
  components: {
    articleComponent: _items_cards_ArticleCardComponent__WEBPACK_IMPORTED_MODULE_0__["default"],
    emptyPageComponent: _global_components_EmptyPageComponent__WEBPACK_IMPORTED_MODULE_1__["default"],
    ContentLoader: vue_content_loader__WEBPACK_IMPORTED_MODULE_3__.ContentLoader,
    metaData: _items_MetaDataComponent__WEBPACK_IMPORTED_MODULE_2__["default"]
  },
  mounted: function mounted() {
    this.get_projects();
  },
  watch: {
    '$route': function $route(to, from) {
      this.get_projects(), window.scrollTo(0, 0);
    }
  },
  methods: {
    get_projects: function get_projects() {
      var _this = this;
      axios.get('/articles/spot_projects/' + localStorage.getItem('lang')).then(function (response) {
        _this.projects = response.data;
      })["catch"](function (error) {})["finally"](function () {
        return _this.indoor_article_loading = false;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/global_components/EmptyPageComponent.vue?vue&type=template&id=77133ec9&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/global_components/EmptyPageComponent.vue?vue&type=template&id=77133ec9& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function render() {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", [_c("h3", {
    staticStyle: {
      "text-align": "center"
    }
  }, [_vm._v(_vm._s(_vm.$t("global.page is empty")))]), _vm._v(" "), _vm.num == 1 ? _c("img", {
    staticClass: "article_404",
    attrs: {
      src: this.publicPath + "/public/images/404/empty_page/1.jpg"
    }
  }) : _vm.num == 2 ? _c("img", {
    staticClass: "article_404",
    attrs: {
      src: this.publicPath + "/public/images/404/empty_page/2.jpg"
    }
  }) : _vm.num == 3 ? _c("img", {
    staticClass: "article_404",
    attrs: {
      src: this.publicPath + "/public/images/404/empty_page/3.jpg"
    }
  }) : _vm.num == 4 ? _c("img", {
    staticClass: "article_404",
    attrs: {
      src: this.publicPath + "/public/images/404/empty_page/4.jpg"
    }
  }) : _c("img", {
    staticClass: "article_404",
    attrs: {
      src: this.publicPath + "/public/images/404/empty_page/3.jpg"
    }
  })]);
};
var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/MetaDataComponent.vue?vue&type=template&id=765add42&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/MetaDataComponent.vue?vue&type=template&id=765add42& ***!
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
  return _c("Head", [_c("title", [_vm._v(_vm._s(this.title + " - " + this.mix_site_url))]), _vm._v(" "), _c("meta", {
    attrs: {
      name: "description",
      content: this.description
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "keywords",
      content: "climbing, georgia, climbing in georgia, rock climbing, rock climing in georgia, mountaineering, mountaineering in georgia"
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "subject",
      content: "Rock climbing and mountaineering in Georgia"
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "copyright",
      content: "Temo Samsonadze"
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "language",
      content: this.locale
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "Classification",
      content: "Sport"
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "author",
      content: "info@" + this.mix_site_url
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "reply-to",
      content: "info@" + this.mix_site_url
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "url",
      content: this.mix_app_ssh + this.mix_site_url
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "og:title",
      content: this.title
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "og:type",
      content: "Climbing"
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "og:url",
      content: this.mix_app_ssh + this.mix_site_url
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "og:image",
      content: this.action_image
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "og:site_name",
      content: this.mix_site_name
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "og:description",
      content: this.description
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "twitter:title",
      content: this.title
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "twitter:type",
      content: "Climbing"
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "twitter:url",
      content: this.mix_app_ssh + this.mix_site_url
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "twitter:image",
      content: this.action_image
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "twitter:site_name",
      content: this.mix_site_name
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "twitter:description",
      content: this.description
    }
  })]);
};
var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=template&id=5d4536ff&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=template&id=5d4536ff& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************/
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
    staticClass: "food col-md-4"
  }, [_c("div", {
    staticClass: "portfolio-img view view-first"
  }, [_vm.article.global_data.new_flag ? _c("div", {
    staticClass: "product-image"
  }, [_c("div", {
    staticClass: "discount-percent-badge discount-badge-fourty"
  }, [_vm._v("NEW")])]) : _vm._e(), _vm._v(" "), _vm.article.global_data.image != null ? _c("site-img", {
    attrs: {
      src: "/public/" + this.image_dir + _vm.article.global_data.image,
      img_class: "img-responsive",
      alt: _vm.article.locale_data.title
    }
  }) : _c("site-img", {
    attrs: {
      src: "/public/images/site_img/image.png",
      img_class: "img-responsive",
      alt: _vm.article.locale_data.title
    }
  }), _vm._v(" "), _c("div", {
    staticClass: "mask"
  }, [_c("router-link", {
    staticClass: "info",
    attrs: {
      to: this.route
    }
  }, [_c("i", {
    staticClass: "fa fa-arrow-right"
  }), _vm._v(" "), _c("p", {
    staticStyle: {
      display: "none"
    }
  }, [_vm._v(_vm._s(_vm.article.locale_data.title))])])], 1), _vm._v(" "), _c("div", {
    staticClass: "isotope-item-dtls"
  }, [_c("h3", [_vm._v(_vm._s(_vm.article.locale_data.title))]), _vm._v(" "), _c("div", {
    staticClass: "article_size"
  }, [_c("span", {
    domProps: {
      innerHTML: _vm._s(_vm.article.locale_data.short_description)
    }
  })])])], 1)]);
};
var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/lists/ProjectsList.vue?vue&type=template&id=564c4c6c&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/lists/ProjectsList.vue?vue&type=template&id=564c4c6c& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************/
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
    staticClass: "h-recent-work",
    attrs: {
      id: "other"
    }
  }, [_c("div", {
    staticClass: "container"
  }, [_c("h1", {
    staticClass: "index_h2"
  }, [_vm._v(_vm._s(_vm.$t("guide.title.spots projects")))]), _vm._v(" "), _vm._m(0), _vm._v(" "), _c("h2", {
    staticClass: "article_list_short_description"
  }, [_vm._v("\n            " + _vm._s(this.$siteData.project_description) + "\n        ")]), _vm._v(" "), _vm.indoor_article_loading ? _c("div", [_c("content-loader", {
    attrs: {
      viewBox: "0 0",
      primaryColor: "#f3f3f3",
      secondaryColor: "#279fbbb0"
    }
  })], 1) : _c("div", [this.projects.length > 0 ? _c("div", {
    staticClass: "article_card_container"
  }, _vm._l(_vm.projects, function (project) {
    return _c("articleComponent", {
      key: project.id,
      attrs: {
        image_dir: "images/project_img/",
        article: project,
        route: "spot_project/" + project.global_data.url_title
      }
    });
  }), 1) : _c("div", [_c("emptyPageComponent")], 1)])]), _vm._v(" "), _c("metaData", {
    attrs: {
      title: _vm.$t("guide.meta.spots projects"),
      description: this.$siteData.project_description,
      image: "/public/images/meta_img/project.jpg"
    }
  })], 1);
};
var staticRenderFns = [function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "bar"
  }, [_c("i", {
    staticClass: "fa fa-dribbble"
  })]);
}];
render._withStripped = true;


/***/ }),

/***/ "./resources/js/components/global_components/EmptyPageComponent.vue":
/*!**************************************************************************!*\
  !*** ./resources/js/components/global_components/EmptyPageComponent.vue ***!
  \**************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _EmptyPageComponent_vue_vue_type_template_id_77133ec9___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./EmptyPageComponent.vue?vue&type=template&id=77133ec9& */ "./resources/js/components/global_components/EmptyPageComponent.vue?vue&type=template&id=77133ec9&");
/* harmony import */ var _EmptyPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./EmptyPageComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/global_components/EmptyPageComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _EmptyPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _EmptyPageComponent_vue_vue_type_template_id_77133ec9___WEBPACK_IMPORTED_MODULE_0__.render,
  _EmptyPageComponent_vue_vue_type_template_id_77133ec9___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/global_components/EmptyPageComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/site/items/MetaDataComponent.vue":
/*!******************************************************************!*\
  !*** ./resources/js/components/site/items/MetaDataComponent.vue ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _MetaDataComponent_vue_vue_type_template_id_765add42___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MetaDataComponent.vue?vue&type=template&id=765add42& */ "./resources/js/components/site/items/MetaDataComponent.vue?vue&type=template&id=765add42&");
/* harmony import */ var _MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MetaDataComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/site/items/MetaDataComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _MetaDataComponent_vue_vue_type_template_id_765add42___WEBPACK_IMPORTED_MODULE_0__.render,
  _MetaDataComponent_vue_vue_type_template_id_765add42___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/site/items/MetaDataComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/site/items/cards/ArticleCardComponent.vue":
/*!***************************************************************************!*\
  !*** ./resources/js/components/site/items/cards/ArticleCardComponent.vue ***!
  \***************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ArticleCardComponent_vue_vue_type_template_id_5d4536ff___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ArticleCardComponent.vue?vue&type=template&id=5d4536ff& */ "./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=template&id=5d4536ff&");
/* harmony import */ var _ArticleCardComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ArticleCardComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ArticleCardComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ArticleCardComponent_vue_vue_type_template_id_5d4536ff___WEBPACK_IMPORTED_MODULE_0__.render,
  _ArticleCardComponent_vue_vue_type_template_id_5d4536ff___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/site/items/cards/ArticleCardComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/site/pages/lists/ProjectsList.vue":
/*!*******************************************************************!*\
  !*** ./resources/js/components/site/pages/lists/ProjectsList.vue ***!
  \*******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ProjectsList_vue_vue_type_template_id_564c4c6c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ProjectsList.vue?vue&type=template&id=564c4c6c& */ "./resources/js/components/site/pages/lists/ProjectsList.vue?vue&type=template&id=564c4c6c&");
/* harmony import */ var _ProjectsList_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ProjectsList.vue?vue&type=script&lang=js& */ "./resources/js/components/site/pages/lists/ProjectsList.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ProjectsList_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ProjectsList_vue_vue_type_template_id_564c4c6c___WEBPACK_IMPORTED_MODULE_0__.render,
  _ProjectsList_vue_vue_type_template_id_564c4c6c___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/site/pages/lists/ProjectsList.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/global_components/EmptyPageComponent.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************!*\
  !*** ./resources/js/components/global_components/EmptyPageComponent.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EmptyPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./EmptyPageComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/global_components/EmptyPageComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EmptyPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/site/items/MetaDataComponent.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/components/site/items/MetaDataComponent.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MetaDataComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/MetaDataComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************!*\
  !*** ./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ArticleCardComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ArticleCardComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ArticleCardComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/site/pages/lists/ProjectsList.vue?vue&type=script&lang=js&":
/*!********************************************************************************************!*\
  !*** ./resources/js/components/site/pages/lists/ProjectsList.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProjectsList_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ProjectsList.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/lists/ProjectsList.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProjectsList_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/global_components/EmptyPageComponent.vue?vue&type=template&id=77133ec9&":
/*!*********************************************************************************************************!*\
  !*** ./resources/js/components/global_components/EmptyPageComponent.vue?vue&type=template&id=77133ec9& ***!
  \*********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_EmptyPageComponent_vue_vue_type_template_id_77133ec9___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_EmptyPageComponent_vue_vue_type_template_id_77133ec9___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_EmptyPageComponent_vue_vue_type_template_id_77133ec9___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./EmptyPageComponent.vue?vue&type=template&id=77133ec9& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/global_components/EmptyPageComponent.vue?vue&type=template&id=77133ec9&");


/***/ }),

/***/ "./resources/js/components/site/items/MetaDataComponent.vue?vue&type=template&id=765add42&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/site/items/MetaDataComponent.vue?vue&type=template&id=765add42& ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_template_id_765add42___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_template_id_765add42___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_template_id_765add42___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MetaDataComponent.vue?vue&type=template&id=765add42& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/MetaDataComponent.vue?vue&type=template&id=765add42&");


/***/ }),

/***/ "./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=template&id=5d4536ff&":
/*!**********************************************************************************************************!*\
  !*** ./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=template&id=5d4536ff& ***!
  \**********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ArticleCardComponent_vue_vue_type_template_id_5d4536ff___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ArticleCardComponent_vue_vue_type_template_id_5d4536ff___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ArticleCardComponent_vue_vue_type_template_id_5d4536ff___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ArticleCardComponent.vue?vue&type=template&id=5d4536ff& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=template&id=5d4536ff&");


/***/ }),

/***/ "./resources/js/components/site/pages/lists/ProjectsList.vue?vue&type=template&id=564c4c6c&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/components/site/pages/lists/ProjectsList.vue?vue&type=template&id=564c4c6c& ***!
  \**************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ProjectsList_vue_vue_type_template_id_564c4c6c___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ProjectsList_vue_vue_type_template_id_564c4c6c___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ProjectsList_vue_vue_type_template_id_564c4c6c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ProjectsList.vue?vue&type=template&id=564c4c6c& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/lists/ProjectsList.vue?vue&type=template&id=564c4c6c&");


/***/ }),

/***/ "./node_modules/@unhead/vue/dist/components.mjs":
/*!******************************************************!*\
  !*** ./node_modules/@unhead/vue/dist/components.mjs ***!
  \******************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Head": () => (/* binding */ Head)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm.js");
/* harmony import */ var _shared_vue_cf295fb1_mjs__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./shared/vue.cf295fb1.mjs */ "./node_modules/@unhead/vue/dist/shared/vue.cf295fb1.mjs");





function addVNodeToHeadObj(node, obj) {
  const nodeType = !_shared_vue_cf295fb1_mjs__WEBPACK_IMPORTED_MODULE_0__.V ? node.tag : node.type;
  const type = nodeType === "html" ? "htmlAttrs" : nodeType === "body" ? "bodyAttrs" : nodeType;
  if (typeof type !== "string" || !(type in obj))
    return;
  const nodeData = !_shared_vue_cf295fb1_mjs__WEBPACK_IMPORTED_MODULE_0__.V ? node.data : node;
  const props = (!_shared_vue_cf295fb1_mjs__WEBPACK_IMPORTED_MODULE_0__.V ? nodeData.attrs : node.props) || {};
  if (!_shared_vue_cf295fb1_mjs__WEBPACK_IMPORTED_MODULE_0__.V) {
    if (nodeData.staticClass)
      props.class = nodeData.staticClass;
    if (nodeData.staticStyle)
      props.style = Object.entries(nodeData.staticStyle).map(([key, value]) => `${key}:${value}`).join(";");
  }
  if (node.children) {
    const childrenAttr = !_shared_vue_cf295fb1_mjs__WEBPACK_IMPORTED_MODULE_0__.V ? "text" : "children";
    props.children = Array.isArray(node.children) ? node.children[0][childrenAttr] : node[childrenAttr];
  }
  if (Array.isArray(obj[type]))
    obj[type].push(props);
  else if (type === "title")
    obj.title = props.children;
  else
    obj[type] = props;
}
function vnodesToHeadObj(nodes) {
  const obj = {
    title: void 0,
    htmlAttrs: void 0,
    bodyAttrs: void 0,
    base: void 0,
    meta: [],
    link: [],
    style: [],
    script: [],
    noscript: []
  };
  for (const node of nodes) {
    if (typeof node.type === "symbol" && Array.isArray(node.children)) {
      for (const childNode of node.children)
        addVNodeToHeadObj(childNode, obj);
    } else {
      addVNodeToHeadObj(node, obj);
    }
  }
  return obj;
}
const Head = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_1__.defineComponent)({
  name: "Head",
  setup(_, { slots }) {
    const head = (0,_shared_vue_cf295fb1_mjs__WEBPACK_IMPORTED_MODULE_0__.i)();
    const obj = (0,vue__WEBPACK_IMPORTED_MODULE_1__.ref)({});
    const entry = head.push(obj);
    (0,vue__WEBPACK_IMPORTED_MODULE_1__.onBeforeUnmount)(() => {
      entry.dispose();
    });
    return () => {
      (0,vue__WEBPACK_IMPORTED_MODULE_1__.watchEffect)(() => {
        if (!slots.default)
          return;
        entry.patch(vnodesToHeadObj(slots.default()));
      });
      return null;
    };
  }
});




/***/ })

}]);