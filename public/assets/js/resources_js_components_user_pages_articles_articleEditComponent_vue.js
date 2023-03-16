"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_articles_articleEditComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/articleEditComponent.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/articleEditComponent.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _forms_edit_forms_GlobalDataFormComponent_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./forms/edit_forms/GlobalDataFormComponent.vue */ "./resources/js/components/user/pages/articles/forms/edit_forms/GlobalDataFormComponent.vue");
/* harmony import */ var _forms_edit_forms_LocaleDataFormComponent_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./forms/edit_forms/LocaleDataFormComponent.vue */ "./resources/js/components/user/pages/articles/forms/edit_forms/LocaleDataFormComponent.vue");
/* harmony import */ var _forms_edit_forms_ArticleImageFormComponent_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./forms/edit_forms/ArticleImageFormComponent.vue */ "./resources/js/components/user/pages/articles/forms/edit_forms/ArticleImageFormComponent.vue");
/* harmony import */ var _forms_edit_forms_SectorImagesFormComponent_vue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./forms/edit_forms/SectorImagesFormComponent.vue */ "./resources/js/components/user/pages/articles/forms/edit_forms/SectorImagesFormComponent.vue");
/* harmony import */ var _forms_edit_forms_MountRouteImageFormComponent_vue__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./forms/edit_forms/MountRouteImageFormComponent.vue */ "./resources/js/components/user/pages/articles/forms/edit_forms/MountRouteImageFormComponent.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//





/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: [// 'back_url',
    // 'category',
    // 'editing_article_id'
  ],
  components: {
    GlobalDataForm: _forms_edit_forms_GlobalDataFormComponent_vue__WEBPACK_IMPORTED_MODULE_0__["default"],
    LocaleDataForm: _forms_edit_forms_LocaleDataFormComponent_vue__WEBPACK_IMPORTED_MODULE_1__["default"],
    ArticleImage: _forms_edit_forms_ArticleImageFormComponent_vue__WEBPACK_IMPORTED_MODULE_2__["default"],
    SectorsImagesForm: _forms_edit_forms_SectorImagesFormComponent_vue__WEBPACK_IMPORTED_MODULE_3__["default"],
    MountRouteImagesForm: _forms_edit_forms_MountRouteImageFormComponent_vue__WEBPACK_IMPORTED_MODULE_4__["default"]
  },
  data: function data() {
    return {
      article_id: this.$route.params.id,
      tab_num: 1,
      error: [],
      editing_data: [],
      article_image: '',
      area_rocks_images: [],
      mount_route_images: [],
      region_id: 0,
      mount_id: 0,
      article_old_image: '',
      is_dala_geting: true,
      global_blocks: {
        info_block: 'new_info',
        routes_info: "new_info",
        what_need_info: 'new_info',
        best_time: 'new_info',
        info_block_id: 0,
        routes_info_id: 0,
        what_need_info_id: 0,
        best_time_id: 0
      }
    };
  },
  mounted: function mounted() {
    this.get_editing_data();
  },
  methods: {
    get_editing_data: function get_editing_data() {
      var _this = this;

      axios.get('../../../api/articles/get_editing_data/' + this.$route.params.id).then(function (response) {
        _this.editing_data = response.data;
        _this.category = response.data.global_article.category;
        _this.article_old_image = _this.editing_data.global_article.image;
        _this.region_id = _this.editing_data.region_id;
        _this.mount_id = _this.editing_data.mount_id;

        _this.editing_data.general_data.forEach(function (general_text) {
          if (general_text.position.block == 'info_block') {
            _this.global_blocks.info_block = general_text.position.block_action;
            _this.global_blocks.info_block_id = general_text.data.id;
          } else if (general_text.position.block == 'routes_info') {
            _this.global_blocks.routes_info = general_text.position.block_action;
            _this.global_blocks.routes_info_id = general_text.data.id;
          } else if (general_text.position.block == 'what_need_info') {
            _this.global_blocks.what_need_info = general_text.position.block_action;
            _this.global_blocks.what_need_info_id = general_text.data.id;
          } else if (general_text.position.block == 'best_time') {
            _this.global_blocks.best_time = general_text.position.block_action;
            _this.global_blocks.best_time_id = general_text.data.id;
          }
        });
      })["catch"](function (error) {
        return console.log(error);
      })["finally"](function () {
        return _this.is_dala_geting = false;
      });
    },
    edit_article: function edit_article() {
      var _this2 = this;

      this.errors = [];
      var formData = new FormData();
      formData.append('image', this.article_image);
      formData.append('data', JSON.stringify(this.editing_data));
      formData.append('global_blocks', JSON.stringify(this.global_blocks));

      if (this.category == 'outdoor') {
        if (this.area_rocks_images) {
          var loop_num = 0;
          this.area_rocks_images.forEach(function (area_image) {
            formData.append('outdoor_area_images[' + loop_num + ']', area_image.image);
            loop_num++;
          });
          loop_num = 0;
        }
      } else if (this.category == 'mount_route') {
        if (this.mount_route_images) {
          var loop_num = 0;
          this.mount_route_images.forEach(function (mount_route_image) {
            formData.append('mountain_route_images[' + loop_num + ']', mount_route_image.image);
            loop_num++;
          });
          loop_num = 0;
        }
      }

      axios // .post('/api/article/add_article/' + this.category, 
      .post('../../../api/article/edit_article/' + this.article_id, formData).then(function (response) {
        _this2.go_back(true);
      })["catch"](function (error) {
        if (error.response.status == 422) {
          _this2.error = error.response.data.validation;
        }
      });
    },
    global_blocks_action: function global_blocks_action(event) {
      this.global_blocks = event;
    },
    go_back: function go_back() {
      var back_action = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;

      if (back_action == false) {
        if (confirm('Are you sure, you want go back?')) {
          this.$router.go(-1);
        }
      } else {
        this.$router.go(-1);
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/ArticleImageFormComponent.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/ArticleImageFormComponent.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {//
  },
  props: ['image_prop', 'category_prop'],
  data: function data() {
    return {
      image: ''
    };
  },
  mounted: function mounted() {// 
  },
  methods: {
    onFileChange: function onFileChange(e) {
      // this.image = e.target.files[0];
      this.upload_img(e.target.files[0]);
    },
    upload_img: function upload_img(event) {
      this.$emit("upload_img", event);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/GlobalDataFormComponent.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/GlobalDataFormComponent.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var vue_slicksort__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-slicksort */ "./node_modules/vue-slicksort/dist/vue-slicksort.umd.js");
/* harmony import */ var vue_slicksort__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_slicksort__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _innologica_vue_stackable_modal__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @innologica/vue-stackable-modal */ "./node_modules/@innologica/vue-stackable-modal/dist/vue-stackable-modal.umd.min.js");
/* harmony import */ var _innologica_vue_stackable_modal__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_innologica_vue_stackable_modal__WEBPACK_IMPORTED_MODULE_1__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

 //https://innologica.github.io/vue-stackable-modal/#sample-css

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    StackModal: (_innologica_vue_stackable_modal__WEBPACK_IMPORTED_MODULE_1___default()),
    SlickItem: vue_slicksort__WEBPACK_IMPORTED_MODULE_0__.SlickItem,
    SlickList: vue_slicksort__WEBPACK_IMPORTED_MODULE_0__.SlickList
  },
  props: ['global_data_prop', 'category_prop', 'title_prop', 'description_prop', 'region_id_prop', 'mount_id_prop'],
  data: function data() {
    return {
      editorConfig: {// toolbar: [ [ 'Bold' ] ]
      },
      data: [],
      region_id: 'select_region',
      mount_id: 'select_mount',
      category: '',
      error: [],
      regions: [],
      mount_masive: []
    };
  },
  watch: {
    global_data_prop: function global_data_prop(newVal, oldVal) {
      // console.log('Prop changed: ', newVal, ' | was: ', oldVal)
      this.data = this.global_data_prop;
    },
    category_prop: function category_prop(newVal, oldVal) {
      this.category = this.category_prop;

      if (this.category == 'outdoor') {
        this.get_regions();
      }

      if (this.category == 'mount_route') {
        this.get_mount_massive_data();
      }
    },
    region_id_prop: function region_id_prop(newVal, oldVal) {
      this.data.region_id = this.region_id_prop;
    },
    mount_id_prop: function mount_id_prop(newVal, oldVal) {
      this.data.mount_id = this.mount_id_prop;
    }
  },
  mounted: function mounted() {
    if (this.category == 'outdoor') {
      this.get_regions();
    }

    if (this.category == 'mount_route') {
      this.get_mount_massive_data();
    } // if(!this.region_id == 'select_region'){
    //     this.data.region_id = this.region_id
    // }
    // if(!this.mount_id == 'select_mount'){
    //     this.data.mount_id = this.mount_id
    // }


    this.$emit('global_form_data', this.data);
  },
  methods: {
    get_mount_massive_data: function get_mount_massive_data() {
      var _this = this;

      axios.get("../../../api/mountaineering/get_mount_data/").then(function (response) {
        _this.mount_masive = response.data;
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    get_regions: function get_regions() {
      var _this2 = this;

      axios.get("../../../api/region/").then(function (response) {
        _this2.regions = response.data;
      })["catch"](function (error) {
        return console.log(error);
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/LocaleDataFormComponent.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/LocaleDataFormComponent.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _innologica_vue_stackable_modal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @innologica/vue-stackable-modal */ "./node_modules/@innologica/vue-stackable-modal/dist/vue-stackable-modal.umd.min.js");
/* harmony import */ var _innologica_vue_stackable_modal__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_innologica_vue_stackable_modal__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../../../mixins/editor/editor_config_mixin.js */ "./resources/js/mixins/editor/editor_config_mixin.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
// import { SlickList, SlickItem } from 'vue-slicksort';
 //https://innologica.github.io/vue-stackable-modal/#sample-css
// import { exampleMixin } from '../../../../../services/editor/editor_config.js'


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    StackModal: (_innologica_vue_stackable_modal__WEBPACK_IMPORTED_MODULE_0___default()) // SlickItem,
    // SlickList,

  },
  mixins: [_mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_1__.editor_config],
  props: ['global_blocks_prop', 'locale_data_prop', 'category_prop', 'locale_prop', 'title_prop', 'description_prop'],
  data: function data() {
    return {
      // category: 'this.$route.params.article_category',
      category: this.category_prop,
      general_infos: [],
      is_change_url_title: false,
      // error: [],
      short_description_text_editor: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_1__.editor_config.get_small_editor_config(),
      text_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_1__.editor_config.get_big_editor_config(),
      route_description_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_1__.editor_config.get_big_editor_config(),
      how_get_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_1__.editor_config.get_big_editor_config(),
      best_time_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_1__.editor_config.get_big_editor_config(),
      what_need_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_1__.editor_config.get_big_editor_config(),
      info_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_1__.editor_config.get_big_editor_config(),
      price_text_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_1__.editor_config.get_big_editor_config(),
      data: {
        change_url_title: false,
        title: '',
        short_description: '',
        text: '',
        route: '',
        how_get: '',
        best_time: '',
        what_need: '',
        info: '',
        time: ''
      },
      global_blocks: {
        info_block: '',
        routes_info: '',
        what_need_info: '',
        best_time: '',
        info_block_id: 0,
        routes_info_id: 0,
        what_need_info_id: 0,
        best_time_id: 0
      }
    };
  },
  mounted: function mounted() {
    this.global_blocks = this.global_blocks_prop;
    this.get_general_info(); // this.text_editor_config = this.get_config()
    // this.route_description_editor_config = this.get_config()
  },
  watch: {
    global_block: function global_block() {
      this.global_blocks = this.global_blocks_prop;
    },
    category_prop: function category_prop(newVal, oldVal) {
      // console.log('Prop changed: ', newVal, ' | was: ', oldVal)
      this.category = this.category_prop;
    },
    locale_data_prop: function locale_data_prop(newVal, oldVal) {
      // console.log('Prop changed: ', newVal, ' | was: ', oldVal)
      this.data = this.locale_data_prop;
    }
  },
  methods: {
    get_config: function get_config() {
      return {
        filebrowserUploadUrl: "../../../../api/ckeditor/upload",
        extraPlugins: 'embed,autoembed',
        embed_provider: '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',
        image2_alignClasses: ['image-align-left', 'image-align-center', 'image-align-right'],
        image2_disableResizer: true,
        removeButtons: 'PasteFromWord'
      };
    },
    change_url_title_in_global_bisnes: function change_url_title_in_global_bisnes() {
      if (!this.is_change_url_title) {
        if (confirm('Are you sure, you want change URL title? It vhile bad for SEO potimization')) {
          this.is_change_url_title = true;
        } else {
          this.is_change_url_title = false;
        }
      } else {
        this.is_change_url_title = false;
      }

      this.data.is_change_url_title = this.is_change_url_title;
    },
    get_general_info: function get_general_info() {
      var _this = this;

      axios.get('../../../api/general_info/').then(function (response) {
        _this.general_infos = response.data;
      })["catch"](function (errors) {
        return console.log(errors);
      });
    },
    info_block_action: function info_block_action(status) {
      this.global_blocks.info_block = status;
      this.$emit('global_blocks', this.global_blocks);
    },
    best_time_action: function best_time_action(status) {
      this.global_blocks.best_time = status;
      this.$emit('global_blocks', this.global_blocks);
    },
    routes_action: function routes_action(status) {
      this.global_blocks.routes_info = status;
      this.$emit('global_blocks', this.global_blocks);
    },
    what_need_block_action: function what_need_block_action(status) {
      this.global_blocks.what_need_info = status;
      this.$emit('global_blocks', this.global_blocks);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/MountRouteImageFormComponent.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/MountRouteImageFormComponent.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
// import { SlickList, SlickItem } from 'vue-slicksort';
// import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: [// 'category'
  ],
  components: {// StackModal,
    // SlickItem,
    // SlickList,
  },
  data: function data() {
    return {
      mount_route_images: [],
      mount_route_old_images: []
    };
  },
  mounted: function mounted() {
    this.get_old_mount_routes_images();
  },
  methods: {
    get_old_mount_routes_images: function get_old_mount_routes_images() {
      var _this = this;

      axios.get("../../../api/mount_route/get_mount_routes_images/" + this.$route.params.id).then(function (response) {
        _this.mount_route_old_images = response.data.mount_route_images;
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    del_old_mount_routes_image_from_db: function del_old_mount_routes_image_from_db(image_id) {
      var _this2 = this;

      if (confirm('Are you sure, you want delite this image?')) {
        axios["delete"]("../../../api/mount_route/del_mount_routes_images/" + image_id).then(function (response) {
          _this2.get_old_mount_routes_images();
        })["catch"](function (error) {
          return console.log(error);
        });
      }
    },
    onFileChange: function onFileChange(event, item_id) {
      var image = event.target.files[0];
      var id = item_id - 1;
      this.mount_route_images[id]['image'] = image;
      this.upload_img();
    },
    upload_img: function upload_img(event) {
      this.$emit("upload_img", this.mount_route_images);
    },
    add_new_mount_route_image_value: function add_new_mount_route_image_value() {
      var new_item_id = this.mount_route_images.length + 1;
      this.mount_route_images.push({
        id: new_item_id,
        image: ''
      });
    },
    del_mount_route_image: function del_mount_route_image(id) {
      this.removeObjectWithId(this.mount_route_images, id);
    },
    removeObjectWithId: function removeObjectWithId(arr, id) {
      var objWithIdIndex = arr.findIndex(function (obj) {
        return obj.id === id;
      });
      arr.splice(objWithIdIndex, 1);
      return arr;
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/SectorImagesFormComponent.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/SectorImagesFormComponent.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
// import { SlickList, SlickItem } from 'vue-slicksort';
// import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: [// 'category'
  ],
  components: {// StackModal,
    // SlickItem,
    // SlickList,
  },
  data: function data() {
    return {
      sector_images: [],
      spot_rocks_old_images: []
    };
  },
  mounted: function mounted() {
    this.get_old_sector_images();
  },
  methods: {
    get_old_sector_images: function get_old_sector_images() {
      var _this = this;

      axios.get("../../../api/spot_rock_images/get_spot_rock_images/" + this.$route.params.id).then(function (response) {
        _this.spot_rocks_old_images = response.data;
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    del_old_sector_image_from_db: function del_old_sector_image_from_db(image_id) {
      var _this2 = this;

      if (confirm('Are you sure, you want delite this image?')) {
        axios["delete"]("../../../api/spot_rock_images/del_spot_rock_image/" + image_id).then(function (response) {
          _this2.get_old_sector_images();
        })["catch"](function (error) {
          return console.log(error);
        });
      }
    },
    onFileChange: function onFileChange(event, item_id) {
      var image = event.target.files[0];
      var id = item_id - 1;
      this.sector_images[id]['image'] = image;
      this.upload_img();
    },
    upload_img: function upload_img(event) {
      this.$emit("area_images", this.sector_images);
    },
    add_new_sector_image_value: function add_new_sector_image_value() {
      var new_item_id = this.sector_images.length + 1;
      this.sector_images.push({
        id: new_item_id,
        image: ''
      });
    },
    del_sector_image: function del_sector_image(id) {
      this.removeObjectWithId(this.sector_images, id);
    },
    removeObjectWithId: function removeObjectWithId(arr, id) {
      var objWithIdIndex = arr.findIndex(function (obj) {
        return obj.id === id;
      });
      arr.splice(objWithIdIndex, 1);
      return arr;
    }
  }
});

/***/ }),

/***/ "./resources/js/mixins/editor/editor_config_mixin.js":
/*!***********************************************************!*\
  !*** ./resources/js/mixins/editor/editor_config_mixin.js ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "editor_config": () => (/* binding */ editor_config)
/* harmony export */ });
var editor_config = {
  //Editor configuration description -> https://ckeditor.com/latest/samples/toolbarconfigurator/#basic
  get_big_editor_config: function get_big_editor_config() {
    return {
      // https://www.tutsmake.com/laravel-8-ckeditor-image-upload-tutorial-example/
      filebrowserUploadUrl: "../../../../api/ckeditor/upload",
      // https://ckeditor.com/docs/ckeditor4/latest/examples/mediaembed.html
      extraPlugins: 'embed,autoembed',
      // contentsCss: [
      //     'http://cdn.ckeditor.com/4.20.2/full-all/contents.css',
      //     'https://ckeditor.com/docs/ckeditor4/4.20.2/examples/assets/css/widgetstyles.css'
      // ],
      embed_provider: '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',
      image2_alignClasses: ['image-align-left', 'image-align-center', 'image-align-right'],
      image2_disableResizer: true,
      removeButtons: 'PasteFromWord'
    };
  },
  get_small_editor_config: function get_small_editor_config() {
    return {
      removeButtons: 'Link,Unlink,Anchor,Image,Table,Underline,Strike,Subscript,Superscript,RemoveFormat,NumberedList,BulletedList,Outdent,Indent,Blockquote,Scayt,HorizontalRule,SpecialChar,Styles,Format,Font,FontSize,About'
    };
  }
};

/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/articleEditComponent.vue?vue&type=style&index=0&id=20626d6a&scoped=true&lang=css&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/articleEditComponent.vue?vue&type=style&index=0&id=20626d6a&scoped=true&lang=css& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js */ "./node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "\n.tabs[data-v-20626d6a] {\n    /* display: flex; */\n    flex-wrap: wrap;\n}\n.tabs label[data-v-20626d6a] {\n    order: 1;\n    display: block;\n    padding: 1rem 2rem;\n    margin-right: 0.2rem;\n    cursor: pointer;\n    background: #ccced0;\n    font-weight: bold;\n    transition: background ease 0.2s;\n}\n.tabs .tab[data-v-20626d6a] {\n    order: 99;\n    flex-grow: 1;\n    width: 100%;\n    display: none;\n    padding: 1rem;\n    background: #fff;\n    /* border: 1px solid #ccc!important; */\n}\n.tabs input[type=\"radio\"][data-v-20626d6a] {\n    display: none;\n}\n.tabs input[type=\"radio\"]:checked + label[data-v-20626d6a] {\n    background: #fff;\n    border: 1px solid #ccc !important;\n}\n.tabs input[type=\"radio\"]:checked + label + .tab[data-v-20626d6a] {\n    display: block;\n}\n@media (max-width: 45em) {\n.tabs .tab[data-v-20626d6a],\n    .tabs label[data-v-20626d6a] {\n        order: initial;\n}\n.tabs label[data-v-20626d6a] {\n        width: 100%;\n        margin-right: 0;\n        margin-top: 0.2rem;\n}\n}\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/articleEditComponent.vue?vue&type=style&index=0&id=20626d6a&scoped=true&lang=css&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/articleEditComponent.vue?vue&type=style&index=0&id=20626d6a&scoped=true&lang=css& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_articleEditComponent_vue_vue_type_style_index_0_id_20626d6a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./articleEditComponent.vue?vue&type=style&index=0&id=20626d6a&scoped=true&lang=css& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/articleEditComponent.vue?vue&type=style&index=0&id=20626d6a&scoped=true&lang=css&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_articleEditComponent_vue_vue_type_style_index_0_id_20626d6a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_articleEditComponent_vue_vue_type_style_index_0_id_20626d6a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./resources/js/components/user/pages/articles/articleEditComponent.vue":
/*!******************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/articleEditComponent.vue ***!
  \******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _articleEditComponent_vue_vue_type_template_id_20626d6a_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./articleEditComponent.vue?vue&type=template&id=20626d6a&scoped=true& */ "./resources/js/components/user/pages/articles/articleEditComponent.vue?vue&type=template&id=20626d6a&scoped=true&");
/* harmony import */ var _articleEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./articleEditComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/articles/articleEditComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _articleEditComponent_vue_vue_type_style_index_0_id_20626d6a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./articleEditComponent.vue?vue&type=style&index=0&id=20626d6a&scoped=true&lang=css& */ "./resources/js/components/user/pages/articles/articleEditComponent.vue?vue&type=style&index=0&id=20626d6a&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _articleEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _articleEditComponent_vue_vue_type_template_id_20626d6a_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _articleEditComponent_vue_vue_type_template_id_20626d6a_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "20626d6a",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/articles/articleEditComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/edit_forms/ArticleImageFormComponent.vue":
/*!****************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/edit_forms/ArticleImageFormComponent.vue ***!
  \****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ArticleImageFormComponent_vue_vue_type_template_id_45671b4d___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ArticleImageFormComponent.vue?vue&type=template&id=45671b4d& */ "./resources/js/components/user/pages/articles/forms/edit_forms/ArticleImageFormComponent.vue?vue&type=template&id=45671b4d&");
/* harmony import */ var _ArticleImageFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ArticleImageFormComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/articles/forms/edit_forms/ArticleImageFormComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ArticleImageFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ArticleImageFormComponent_vue_vue_type_template_id_45671b4d___WEBPACK_IMPORTED_MODULE_0__.render,
  _ArticleImageFormComponent_vue_vue_type_template_id_45671b4d___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/articles/forms/edit_forms/ArticleImageFormComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/edit_forms/GlobalDataFormComponent.vue":
/*!**************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/edit_forms/GlobalDataFormComponent.vue ***!
  \**************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _GlobalDataFormComponent_vue_vue_type_template_id_224334a5___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./GlobalDataFormComponent.vue?vue&type=template&id=224334a5& */ "./resources/js/components/user/pages/articles/forms/edit_forms/GlobalDataFormComponent.vue?vue&type=template&id=224334a5&");
/* harmony import */ var _GlobalDataFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./GlobalDataFormComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/articles/forms/edit_forms/GlobalDataFormComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _GlobalDataFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _GlobalDataFormComponent_vue_vue_type_template_id_224334a5___WEBPACK_IMPORTED_MODULE_0__.render,
  _GlobalDataFormComponent_vue_vue_type_template_id_224334a5___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/articles/forms/edit_forms/GlobalDataFormComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/edit_forms/LocaleDataFormComponent.vue":
/*!**************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/edit_forms/LocaleDataFormComponent.vue ***!
  \**************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _LocaleDataFormComponent_vue_vue_type_template_id_cace5b64___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./LocaleDataFormComponent.vue?vue&type=template&id=cace5b64& */ "./resources/js/components/user/pages/articles/forms/edit_forms/LocaleDataFormComponent.vue?vue&type=template&id=cace5b64&");
/* harmony import */ var _LocaleDataFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./LocaleDataFormComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/articles/forms/edit_forms/LocaleDataFormComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _LocaleDataFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _LocaleDataFormComponent_vue_vue_type_template_id_cace5b64___WEBPACK_IMPORTED_MODULE_0__.render,
  _LocaleDataFormComponent_vue_vue_type_template_id_cace5b64___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/articles/forms/edit_forms/LocaleDataFormComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/edit_forms/MountRouteImageFormComponent.vue":
/*!*******************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/edit_forms/MountRouteImageFormComponent.vue ***!
  \*******************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _MountRouteImageFormComponent_vue_vue_type_template_id_10c0dca5___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MountRouteImageFormComponent.vue?vue&type=template&id=10c0dca5& */ "./resources/js/components/user/pages/articles/forms/edit_forms/MountRouteImageFormComponent.vue?vue&type=template&id=10c0dca5&");
/* harmony import */ var _MountRouteImageFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MountRouteImageFormComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/articles/forms/edit_forms/MountRouteImageFormComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _MountRouteImageFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _MountRouteImageFormComponent_vue_vue_type_template_id_10c0dca5___WEBPACK_IMPORTED_MODULE_0__.render,
  _MountRouteImageFormComponent_vue_vue_type_template_id_10c0dca5___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/articles/forms/edit_forms/MountRouteImageFormComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/edit_forms/SectorImagesFormComponent.vue":
/*!****************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/edit_forms/SectorImagesFormComponent.vue ***!
  \****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _SectorImagesFormComponent_vue_vue_type_template_id_1b4fd0f4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SectorImagesFormComponent.vue?vue&type=template&id=1b4fd0f4& */ "./resources/js/components/user/pages/articles/forms/edit_forms/SectorImagesFormComponent.vue?vue&type=template&id=1b4fd0f4&");
/* harmony import */ var _SectorImagesFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SectorImagesFormComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/articles/forms/edit_forms/SectorImagesFormComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _SectorImagesFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _SectorImagesFormComponent_vue_vue_type_template_id_1b4fd0f4___WEBPACK_IMPORTED_MODULE_0__.render,
  _SectorImagesFormComponent_vue_vue_type_template_id_1b4fd0f4___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/articles/forms/edit_forms/SectorImagesFormComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/articles/articleEditComponent.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/articleEditComponent.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_articleEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./articleEditComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/articleEditComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_articleEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/edit_forms/ArticleImageFormComponent.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/edit_forms/ArticleImageFormComponent.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ArticleImageFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ArticleImageFormComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/ArticleImageFormComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ArticleImageFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/edit_forms/GlobalDataFormComponent.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/edit_forms/GlobalDataFormComponent.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalDataFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./GlobalDataFormComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/GlobalDataFormComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalDataFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/edit_forms/LocaleDataFormComponent.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/edit_forms/LocaleDataFormComponent.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LocaleDataFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./LocaleDataFormComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/LocaleDataFormComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LocaleDataFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/edit_forms/MountRouteImageFormComponent.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/edit_forms/MountRouteImageFormComponent.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MountRouteImageFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MountRouteImageFormComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/MountRouteImageFormComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MountRouteImageFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/edit_forms/SectorImagesFormComponent.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/edit_forms/SectorImagesFormComponent.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SectorImagesFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./SectorImagesFormComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/SectorImagesFormComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SectorImagesFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/articles/articleEditComponent.vue?vue&type=style&index=0&id=20626d6a&scoped=true&lang=css&":
/*!***************************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/articleEditComponent.vue?vue&type=style&index=0&id=20626d6a&scoped=true&lang=css& ***!
  \***************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_articleEditComponent_vue_vue_type_style_index_0_id_20626d6a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader/dist/cjs.js!../../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./articleEditComponent.vue?vue&type=style&index=0&id=20626d6a&scoped=true&lang=css& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/articleEditComponent.vue?vue&type=style&index=0&id=20626d6a&scoped=true&lang=css&");


/***/ }),

/***/ "./resources/js/components/user/pages/articles/articleEditComponent.vue?vue&type=template&id=20626d6a&scoped=true&":
/*!*************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/articleEditComponent.vue?vue&type=template&id=20626d6a&scoped=true& ***!
  \*************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_articleEditComponent_vue_vue_type_template_id_20626d6a_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_articleEditComponent_vue_vue_type_template_id_20626d6a_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_articleEditComponent_vue_vue_type_template_id_20626d6a_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./articleEditComponent.vue?vue&type=template&id=20626d6a&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/articleEditComponent.vue?vue&type=template&id=20626d6a&scoped=true&");


/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/edit_forms/ArticleImageFormComponent.vue?vue&type=template&id=45671b4d&":
/*!***********************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/edit_forms/ArticleImageFormComponent.vue?vue&type=template&id=45671b4d& ***!
  \***********************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ArticleImageFormComponent_vue_vue_type_template_id_45671b4d___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ArticleImageFormComponent_vue_vue_type_template_id_45671b4d___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ArticleImageFormComponent_vue_vue_type_template_id_45671b4d___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ArticleImageFormComponent.vue?vue&type=template&id=45671b4d& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/ArticleImageFormComponent.vue?vue&type=template&id=45671b4d&");


/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/edit_forms/GlobalDataFormComponent.vue?vue&type=template&id=224334a5&":
/*!*********************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/edit_forms/GlobalDataFormComponent.vue?vue&type=template&id=224334a5& ***!
  \*********************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalDataFormComponent_vue_vue_type_template_id_224334a5___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalDataFormComponent_vue_vue_type_template_id_224334a5___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalDataFormComponent_vue_vue_type_template_id_224334a5___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./GlobalDataFormComponent.vue?vue&type=template&id=224334a5& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/GlobalDataFormComponent.vue?vue&type=template&id=224334a5&");


/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/edit_forms/LocaleDataFormComponent.vue?vue&type=template&id=cace5b64&":
/*!*********************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/edit_forms/LocaleDataFormComponent.vue?vue&type=template&id=cace5b64& ***!
  \*********************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LocaleDataFormComponent_vue_vue_type_template_id_cace5b64___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LocaleDataFormComponent_vue_vue_type_template_id_cace5b64___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LocaleDataFormComponent_vue_vue_type_template_id_cace5b64___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./LocaleDataFormComponent.vue?vue&type=template&id=cace5b64& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/LocaleDataFormComponent.vue?vue&type=template&id=cace5b64&");


/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/edit_forms/MountRouteImageFormComponent.vue?vue&type=template&id=10c0dca5&":
/*!**************************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/edit_forms/MountRouteImageFormComponent.vue?vue&type=template&id=10c0dca5& ***!
  \**************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MountRouteImageFormComponent_vue_vue_type_template_id_10c0dca5___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MountRouteImageFormComponent_vue_vue_type_template_id_10c0dca5___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MountRouteImageFormComponent_vue_vue_type_template_id_10c0dca5___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MountRouteImageFormComponent.vue?vue&type=template&id=10c0dca5& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/MountRouteImageFormComponent.vue?vue&type=template&id=10c0dca5&");


/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/edit_forms/SectorImagesFormComponent.vue?vue&type=template&id=1b4fd0f4&":
/*!***********************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/edit_forms/SectorImagesFormComponent.vue?vue&type=template&id=1b4fd0f4& ***!
  \***********************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SectorImagesFormComponent_vue_vue_type_template_id_1b4fd0f4___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SectorImagesFormComponent_vue_vue_type_template_id_1b4fd0f4___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SectorImagesFormComponent_vue_vue_type_template_id_1b4fd0f4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./SectorImagesFormComponent.vue?vue&type=template&id=1b4fd0f4& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/SectorImagesFormComponent.vue?vue&type=template&id=1b4fd0f4&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/articleEditComponent.vue?vue&type=template&id=20626d6a&scoped=true&":
/*!****************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/articleEditComponent.vue?vue&type=template&id=20626d6a&scoped=true& ***!
  \****************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "tabs" }, [
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "form-group" }, [
        _c(
          "button",
          {
            staticClass: "btn btn-primary",
            attrs: { type: "submit" },
            on: {
              click: function ($event) {
                return _vm.go_back()
              },
            },
          },
          [_vm._v("Beck")]
        ),
      ]),
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "form-group" }, [
        _c(
          "button",
          {
            staticClass: "btn btn-primary",
            attrs: { type: "submit" },
            on: {
              click: function ($event) {
                return _vm.edit_article()
              },
            },
          },
          [_vm._v("Save")]
        ),
      ]),
    ]),
    _vm._v(" "),
    _vm.error.length != 0
      ? _c("div", { staticClass: "row" }, [
          _c("div", { staticClass: "col-md-12" }, [
            _vm.error.global_info_validation.published
              ? _c(
                  "div",
                  {
                    staticClass: "alert alert-danger",
                    attrs: { role: "alert" },
                  },
                  [
                    _vm._v(
                      "\n                  Published - " +
                        _vm._s(_vm.error.global_info_validation.published[0]) +
                        "\n              "
                    ),
                  ]
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.error.us_info_validation.title
              ? _c(
                  "div",
                  {
                    staticClass: "alert alert-danger",
                    attrs: { role: "alert" },
                  },
                  [
                    _vm._v(
                      "\n                  English title - " +
                        _vm._s(_vm.error.us_info_validation.title[0]) +
                        "\n              "
                    ),
                  ]
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.error.us_info_validation.short_description
              ? _c(
                  "div",
                  {
                    staticClass: "alert alert-danger",
                    attrs: { role: "alert" },
                  },
                  [
                    _vm._v(
                      "\n                  English description - " +
                        _vm._s(
                          _vm.error.us_info_validation.short_description[0]
                        ) +
                        "\n              "
                    ),
                  ]
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.error.us_info_validation.text
              ? _c(
                  "div",
                  {
                    staticClass: "alert alert-danger",
                    attrs: { role: "alert" },
                  },
                  [
                    _vm._v(
                      "\n                  English text - " +
                        _vm._s(_vm.error.us_info_validation.text[0]) +
                        "\n              "
                    ),
                  ]
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.error.ka_info_validation.title
              ? _c(
                  "div",
                  {
                    staticClass: "alert alert-danger",
                    attrs: { role: "alert" },
                  },
                  [
                    _vm._v(
                      "\n                  Georgian title - " +
                        _vm._s(_vm.error.ka_info_validation.title[0]) +
                        "\n              "
                    ),
                  ]
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.error.ka_info_validation.short_description
              ? _c(
                  "div",
                  {
                    staticClass: "alert alert-danger",
                    attrs: { role: "alert" },
                  },
                  [
                    _vm._v(
                      "\n                  Georgian description - " +
                        _vm._s(
                          _vm.error.ka_info_validation.short_description[0]
                        ) +
                        "\n              "
                    ),
                  ]
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.error.ka_info_validation.text
              ? _c(
                  "div",
                  {
                    staticClass: "alert alert-danger",
                    attrs: { role: "alert" },
                  },
                  [
                    _vm._v(
                      "\n                  Georgian text - " +
                        _vm._s(_vm.error.ka_info_validation.text[0]) +
                        "\n              "
                    ),
                  ]
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.error.ru_info_validation.title
              ? _c(
                  "div",
                  {
                    staticClass: "alert alert-danger",
                    attrs: { role: "alert" },
                  },
                  [
                    _vm._v(
                      "\n                  Russion title - " +
                        _vm._s(_vm.error.ru_info_validation.title[0]) +
                        "\n              "
                    ),
                  ]
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.error.ru_info_validation.short_description
              ? _c(
                  "div",
                  {
                    staticClass: "alert alert-danger",
                    attrs: { role: "alert" },
                  },
                  [
                    _vm._v(
                      "\n                  Russiondescription - " +
                        _vm._s(
                          _vm.error.ru_info_validation.short_description[0]
                        ) +
                        "\n              "
                    ),
                  ]
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.error.ru_info_validation.text
              ? _c(
                  "div",
                  {
                    staticClass: "alert alert-danger",
                    attrs: { role: "alert" },
                  },
                  [
                    _vm._v(
                      "\n                  Russion text - " +
                        _vm._s(_vm.error.ru_info_validation.text[0]) +
                        "\n              "
                    ),
                  ]
                )
              : _vm._e(),
          ]),
        ])
      : _vm._e(),
    _vm._v(" "),
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-md-12" }, [
        _c("div", { staticClass: "row" }, [
          _c("div", { staticClass: "col" }, [
            _c("input", {
              directives: [
                {
                  name: "model",
                  rawName: "v-model",
                  value: _vm.tab_num,
                  expression: "tab_num",
                },
              ],
              attrs: { type: "radio", id: "1" },
              domProps: { value: 1, checked: _vm._q(_vm.tab_num, 1) },
              on: {
                change: function ($event) {
                  _vm.tab_num = 1
                },
              },
            }),
            _vm._v(" "),
            _c("label", { attrs: { for: "1" } }, [_vm._v("Global info")]),
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "col" }, [
            _c("input", {
              directives: [
                {
                  name: "model",
                  rawName: "v-model",
                  value: _vm.tab_num,
                  expression: "tab_num",
                },
              ],
              attrs: { type: "radio", id: "2" },
              domProps: { value: 2, checked: _vm._q(_vm.tab_num, 2) },
              on: {
                change: function ($event) {
                  _vm.tab_num = 2
                },
              },
            }),
            _vm._v(" "),
            _c("label", { attrs: { for: "2" } }, [_vm._v("English text")]),
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "col" }, [
            _c("input", {
              directives: [
                {
                  name: "model",
                  rawName: "v-model",
                  value: _vm.tab_num,
                  expression: "tab_num",
                },
              ],
              attrs: { type: "radio", id: "3" },
              domProps: { value: 3, checked: _vm._q(_vm.tab_num, 3) },
              on: {
                change: function ($event) {
                  _vm.tab_num = 3
                },
              },
            }),
            _vm._v(" "),
            _c("label", { attrs: { for: "3" } }, [_vm._v("Georgian text")]),
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "col" }, [
            _c("input", {
              directives: [
                {
                  name: "model",
                  rawName: "v-model",
                  value: _vm.tab_num,
                  expression: "tab_num",
                },
              ],
              attrs: { type: "radio", id: "4" },
              domProps: { value: 4, checked: _vm._q(_vm.tab_num, 4) },
              on: {
                change: function ($event) {
                  _vm.tab_num = 4
                },
              },
            }),
            _vm._v(" "),
            _c("label", { attrs: { for: "4" } }, [_vm._v("Russion text")]),
          ]),
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "row" }),
      ]),
      _vm._v(" "),
      _c(
        "div",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value: _vm.tab_num == 1,
              expression: "tab_num == 1",
            },
          ],
          staticClass: "col-md-12",
        },
        [
          _c("GlobalDataForm", {
            attrs: {
              global_data_prop: _vm.editing_data.global_article,
              category_prop: this.category,
              region_id_prop: _vm.region_id,
              mount_id_prop: _vm.mount_id,
              title_prop: _vm.$t("user edit en article title"),
              description_prop: _vm.$t("user edit en article description"),
            },
            on: {
              global_form_data: function ($event) {
                _vm.article_data.global_data = $event
              },
            },
          }),
          _vm._v(" "),
          _c("ArticleImage", {
            attrs: {
              image_prop: _vm.article_old_image,
              category_prop: this.category,
            },
            on: {
              upload_img: function ($event) {
                _vm.article_image = $event
              },
            },
          }),
          _vm._v(" "),
          this.category == "outdoor"
            ? _c("SectorsImagesForm", {
                on: {
                  area_images: function ($event) {
                    _vm.area_rocks_images = $event
                  },
                },
              })
            : _vm._e(),
          _vm._v(" "),
          this.category == "mount_route"
            ? _c("MountRouteImagesForm", {
                on: {
                  mount_route_images: function ($event) {
                    _vm.article_image = $event
                  },
                },
              })
            : _vm._e(),
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "div",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value: _vm.tab_num == 2,
              expression: "tab_num == 2",
            },
          ],
          staticClass: "col-md-12",
        },
        [
          _c("LocaleDataForm", {
            attrs: {
              global_blocks_prop: _vm.global_blocks,
              locale_data_prop: _vm.editing_data.us_article,
              category_prop: this.category,
              locale_prop: "us",
              title_prop: _vm.$t("user edit en article title"),
              description_prop: _vm.$t("user edit en article description"),
            },
            on: {
              locale_form_data: function ($event) {
                _vm.article_data.us_data = $event
              },
              global_blocks: _vm.global_blocks_action,
            },
          }),
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "div",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value: _vm.tab_num == 3,
              expression: "tab_num == 3",
            },
          ],
          staticClass: "col-md-12",
        },
        [
          _c("LocaleDataForm", {
            attrs: {
              global_blocks_prop: _vm.global_blocks,
              locale_data_prop: _vm.editing_data.ka_article,
              category_prop: this.category,
              locale_prop: "ka",
              title_prop: _vm.$t("user edit ka article title"),
              description_prop: _vm.$t("user edit ka article description"),
            },
            on: {
              locale_form_data: function ($event) {
                _vm.article_data.ka_data = $event
              },
              global_blocks: _vm.global_blocks_action,
            },
          }),
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "div",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value: _vm.tab_num == 4,
              expression: "tab_num == 4",
            },
          ],
          staticClass: "col-md-12",
        },
        [
          _c("LocaleDataForm", {
            attrs: {
              global_blocks_prop: _vm.global_blocks,
              locale_data_prop: _vm.editing_data.ru_article,
              category_prop: this.category,
              locale_prop: "ru",
              title_prop: _vm.$t("user edit ru article title"),
              description_prop: _vm.$t("user edit ru article description"),
            },
            on: {
              locale_form_data: function ($event) {
                _vm.article_data.ru_data = $event
              },
              global_blocks: _vm.global_blocks_action,
            },
          }),
        ],
        1
      ),
    ]),
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/ArticleImageFormComponent.vue?vue&type=template&id=45671b4d&":
/*!**************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/ArticleImageFormComponent.vue?vue&type=template&id=45671b4d& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "col-md-12" }, [
    _c(
      "form",
      {
        ref: "myForm",
        attrs: { id: "myForm", enctype: "multipart/form-data" },
      },
      [
        _c("div", { staticClass: "form-group clearfix row" }, [
          _c(
            "label",
            {
              staticClass: "col-md-2 control-label",
              attrs: { for: "article image" },
            },
            [_vm._v("Active image:")]
          ),
          _vm._v(" "),
          _c("div", { staticClass: "col-md-5" }, [
            _c("img", {
              attrs: {
                src:
                  "/public/images/" +
                  _vm.category_prop +
                  "_img/" +
                  _vm.image_prop,
                alt: "Locale sectors image",
              },
            }),
          ]),
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "form-group clearfix row" }, [
          _c(
            "label",
            {
              staticClass: "col-md-2 control-label",
              attrs: { for: "new image" },
            },
            [_vm._v("Upload new image:")]
          ),
          _vm._v(" "),
          _c("div", { staticClass: "col-md-10" }, [
            _c("input", {
              attrs: { type: "file", name: "image", id: "image" },
              on: { change: _vm.onFileChange },
            }),
          ]),
        ]),
      ]
    ),
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/GlobalDataFormComponent.vue?vue&type=template&id=224334a5&":
/*!************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/GlobalDataFormComponent.vue?vue&type=template&id=224334a5& ***!
  \************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "colm-md-12" }, [
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-md-12" }, [
        _vm._m(0),
        _vm._v(" "),
        _c(
          "form",
          {
            staticStyle: { "margin-top": "5%" },
            on: {
              submit: function ($event) {
                $event.preventDefault()
                return _vm.edit_article.apply(null, arguments)
              },
            },
          },
          [
            _c("div", { staticClass: "form-group clearfix row" }, [
              _c(
                "label",
                {
                  staticClass: "col-md-2 control-label",
                  attrs: { for: "name" },
                },
                [_vm._v(" Publish ")]
              ),
              _vm._v(" "),
              _c("div", { staticClass: "col-md-10" }, [
                _c(
                  "select",
                  {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.data.published,
                        expression: "data.published",
                      },
                    ],
                    staticClass: "form-control",
                    attrs: { name: "published" },
                    on: {
                      change: function ($event) {
                        var $$selectedVal = Array.prototype.filter
                          .call($event.target.options, function (o) {
                            return o.selected
                          })
                          .map(function (o) {
                            var val = "_value" in o ? o._value : o.value
                            return val
                          })
                        _vm.$set(
                          _vm.data,
                          "published",
                          $event.target.multiple
                            ? $$selectedVal
                            : $$selectedVal[0]
                        )
                      },
                    },
                  },
                  [
                    _c("option", { attrs: { value: "0" } }, [
                      _vm._v("Not public"),
                    ]),
                    _vm._v(" "),
                    _c("option", { attrs: { value: "1" } }, [_vm._v("Public")]),
                  ]
                ),
              ]),
            ]),
            _vm._v(" "),
            this.category == "outdoor"
              ? _c("div", { staticClass: "form-group clearfix row" }, [
                  _c(
                    "label",
                    {
                      staticClass: "col-md-2 control-label ",
                      attrs: { for: "region" },
                    },
                    [_vm._v(" Regions ")]
                  ),
                  _vm._v(" "),
                  _c("div", { staticClass: "col-md-10" }, [
                    _c(
                      "select",
                      {
                        directives: [
                          {
                            name: "model",
                            rawName: "v-model",
                            value: _vm.data.region_id,
                            expression: "data.region_id",
                          },
                        ],
                        staticClass: "form-control",
                        attrs: { name: "region" },
                        on: {
                          change: function ($event) {
                            var $$selectedVal = Array.prototype.filter
                              .call($event.target.options, function (o) {
                                return o.selected
                              })
                              .map(function (o) {
                                var val = "_value" in o ? o._value : o.value
                                return val
                              })
                            _vm.$set(
                              _vm.data,
                              "region_id",
                              $event.target.multiple
                                ? $$selectedVal
                                : $$selectedVal[0]
                            )
                          },
                        },
                      },
                      [
                        _c(
                          "option",
                          {
                            attrs: { disabled: "" },
                            domProps: { value: "select_region" },
                          },
                          [_vm._v("Select region")]
                        ),
                        _vm._v(" "),
                        _vm._l(_vm.regions, function (region) {
                          return _c(
                            "option",
                            { key: region.id, domProps: { value: region.id } },
                            [_vm._v(_vm._s(region.us_name))]
                          )
                        }),
                      ],
                      2
                    ),
                  ]),
                ])
              : _vm._e(),
            _vm._v(" "),
            this.category == "mount_route"
              ? _c("div", { staticClass: "form-group clearfix row" }, [
                  _c(
                    "label",
                    {
                      staticClass: "col-md-2 control-label",
                      attrs: { for: "name" },
                    },
                    [_vm._v(" Mountain ")]
                  ),
                  _vm._v(" "),
                  _c("div", { staticClass: "col-md-10" }, [
                    _c(
                      "select",
                      {
                        directives: [
                          {
                            name: "model",
                            rawName: "v-model",
                            value: _vm.data.mount_id,
                            expression: "data.mount_id",
                          },
                        ],
                        staticClass: "form-control",
                        attrs: { name: "mount_id" },
                        on: {
                          change: function ($event) {
                            var $$selectedVal = Array.prototype.filter
                              .call($event.target.options, function (o) {
                                return o.selected
                              })
                              .map(function (o) {
                                var val = "_value" in o ? o._value : o.value
                                return val
                              })
                            _vm.$set(
                              _vm.data,
                              "mount_id",
                              $event.target.multiple
                                ? $$selectedVal
                                : $$selectedVal[0]
                            )
                          },
                        },
                      },
                      [
                        _c(
                          "option",
                          {
                            attrs: { disabled: "" },
                            domProps: { value: "select_mount" },
                          },
                          [_vm._v("Select mount")]
                        ),
                        _vm._v(" "),
                        _vm._l(_vm.mount_masive, function (mount) {
                          return _c(
                            "option",
                            { key: mount.id, domProps: { value: mount.id } },
                            [_vm._v(_vm._s(mount.name))]
                          )
                        }),
                      ],
                      2
                    ),
                  ]),
                ])
              : _vm._e(),
            _vm._v(" "),
            this.category != "mount_route"
              ? _c("div", { staticClass: "form-group clearfix row" }, [
                  _c(
                    "label",
                    {
                      staticClass: "col-md-2 control-label",
                      attrs: { for: "name" },
                    },
                    [_vm._v(" Map ")]
                  ),
                  _vm._v(" "),
                  _c("div", { staticClass: "col-md-10" }, [
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.data.map,
                          expression: "data.map",
                        },
                      ],
                      staticClass: "form-control",
                      attrs: { type: "text", name: "map" },
                      domProps: { value: _vm.data.map },
                      on: {
                        input: function ($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.data, "map", $event.target.value)
                        },
                      },
                    }),
                  ]),
                ])
              : _vm._e(),
            _vm._v(" "),
            this.category == "outdoor" || this.category == "ice"
              ? _c("div", { staticClass: "form-group clearfix row" }, [
                  _c(
                    "label",
                    {
                      staticClass: "col-md-2 control-label",
                      attrs: { for: "name" },
                    },
                    [_vm._v(" Weather ")]
                  ),
                  _vm._v(" "),
                  _c("div", { staticClass: "col-md-10" }, [
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.data.weather,
                          expression: "data.weather",
                        },
                      ],
                      staticClass: "form-control",
                      attrs: { type: "text", name: "weather" },
                      domProps: { value: _vm.data.weather },
                      on: {
                        input: function ($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.data, "weather", $event.target.value)
                        },
                      },
                    }),
                  ]),
                ])
              : _vm._e(),
            _vm._v(" "),
            _c("hr"),
            _vm._v(" "),
            this.category == "indoor"
              ? _c("div", { staticClass: "form-group clearfix row" }, [
                  _c(
                    "label",
                    {
                      staticClass: "col-md-2 control-label",
                      attrs: { for: "name" },
                    },
                    [_vm._v(" Minimal price ")]
                  ),
                  _vm._v(" "),
                  _c("div", { staticClass: "col-md-10" }, [
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.data.price_from,
                          expression: "data.price_from",
                        },
                      ],
                      staticClass: "form-control",
                      attrs: { type: "text", name: "price_from" },
                      domProps: { value: _vm.data.price_from },
                      on: {
                        input: function ($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.data, "price_from", $event.target.value)
                        },
                      },
                    }),
                  ]),
                ])
              : _vm._e(),
            _vm._v(" "),
            this.category == "indoor"
              ? _c("div", { staticClass: "form-group clearfix row" }, [
                  _c(
                    "label",
                    {
                      staticClass: "col-md-2 control-label",
                      attrs: { for: "name" },
                    },
                    [_vm._v(" Working time ")]
                  ),
                  _vm._v(" "),
                  _c("div", { staticClass: "col-md-10" }, [
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.data.working_time,
                          expression: "data.working_time",
                        },
                      ],
                      staticClass: "form-control",
                      attrs: { type: "text", name: "time" },
                      domProps: { value: _vm.data.working_time },
                      on: {
                        input: function ($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(
                            _vm.data,
                            "working_time",
                            $event.target.value
                          )
                        },
                      },
                    }),
                  ]),
                ])
              : _vm._e(),
            _vm._v(" "),
            this.category == "partner" || this.category == "indoor"
              ? _c("hr")
              : _vm._e(),
            _vm._v(" "),
            this.category == "partner" || this.category == "indoor"
              ? _c("div", { staticClass: "form-group clearfix row" }, [
                  _c(
                    "label",
                    {
                      staticClass: "col-md-2 control-label",
                      attrs: { for: "name" },
                    },
                    [_vm._v(" facebook / twitter ")]
                  ),
                  _vm._v(" "),
                  _c("div", { staticClass: "col-md-4" }, [
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.data.fb_link,
                          expression: "data.fb_link",
                        },
                      ],
                      staticClass: "form-control",
                      attrs: { type: "text", name: "fb_link" },
                      domProps: { value: _vm.data.fb_link },
                      on: {
                        input: function ($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.data, "fb_link", $event.target.value)
                        },
                      },
                    }),
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "col-md-4" }, [
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.data.twit_link,
                          expression: "data.twit_link",
                        },
                      ],
                      staticClass: "form-control",
                      attrs: { type: "text", name: "twit_link" },
                      domProps: { value: _vm.data.twit_link },
                      on: {
                        input: function ($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.data, "twit_link", $event.target.value)
                        },
                      },
                    }),
                  ]),
                ])
              : _vm._e(),
            _vm._v(" "),
            this.category == "partner" || this.category == "indoor"
              ? _c("div", { staticClass: "form-group clearfix row" }, [
                  _c(
                    "label",
                    {
                      staticClass: "col-md-2 control-label",
                      attrs: { for: "name" },
                    },
                    [_vm._v(" google / instagram ")]
                  ),
                  _vm._v(" "),
                  _c("div", { staticClass: "col-md-4" }, [
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.data.google_link,
                          expression: "data.google_link",
                        },
                      ],
                      staticClass: "form-control",
                      attrs: { type: "text", name: "google_link" },
                      domProps: { value: _vm.data.google_link },
                      on: {
                        input: function ($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.data, "google_link", $event.target.value)
                        },
                      },
                    }),
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "col-md-4" }, [
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.data.inst_link,
                          expression: "data.inst_link",
                        },
                      ],
                      staticClass: "form-control",
                      attrs: { type: "text", name: "inst_link" },
                      domProps: { value: _vm.data.inst_link },
                      on: {
                        input: function ($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.data, "inst_link", $event.target.value)
                        },
                      },
                    }),
                  ]),
                ])
              : _vm._e(),
            _vm._v(" "),
            this.category == "partner" || this.category == "indoor"
              ? _c("div", { staticClass: "form-group clearfix row" }, [
                  _c(
                    "label",
                    {
                      staticClass: "col-md-2 control-label",
                      attrs: { for: "name" },
                    },
                    [_vm._v(" website ")]
                  ),
                  _vm._v(" "),
                  _c("div", { staticClass: "col-md-10" }, [
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.data.web_link,
                          expression: "data.web_link",
                        },
                      ],
                      staticClass: "form-control",
                      attrs: { type: "text", name: "web_link" },
                      domProps: { value: _vm.data.web_link },
                      on: {
                        input: function ($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.data, "web_link", $event.target.value)
                        },
                      },
                    }),
                  ]),
                ])
              : _vm._e(),
          ]
        ),
      ]),
    ]),
  ])
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "jumbotron jumbotron-fluid" }, [
      _c("div", { staticClass: "container" }, [
        _c("h2", { staticClass: "display-4" }, [
          _vm._v("Edit article global information"),
        ]),
        _vm._v(" "),
        _c("p", { staticClass: "lead" }, [
          _vm._v("Edit article global information."),
        ]),
      ]),
    ])
  },
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/LocaleDataFormComponent.vue?vue&type=template&id=cace5b64&":
/*!************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/LocaleDataFormComponent.vue?vue&type=template&id=cace5b64& ***!
  \************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "col-md-12" }, [
    _c("div", { staticClass: "col-md-12" }, [
      _c("div", { staticClass: "jumbotron width_100" }, [
        _c("div", { staticClass: "container" }, [
          _c("h2", { staticClass: "display-4" }, [
            _c("span", { staticStyle: { "text-transform": "capitalize" } }, [
              _vm._v(_vm._s(this.category_prop)),
            ]),
            _vm._v(" " + _vm._s(this.title_prop)),
          ]),
          _vm._v(" "),
          _c("p", { staticClass: "lead" }, [
            _vm._v(_vm._s(this.description_prop)),
          ]),
        ]),
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "wrapper container-fluid container" }, [
        _c("form", { attrs: { method: "POST" } }, [
          _c("div", { staticClass: "form-group clearfix row" }, [
            _c(
              "label",
              { staticClass: "col-md-2 control-label", attrs: { for: "name" } },
              [_vm._v(" Title ")]
            ),
            _vm._v(" "),
            _c("div", { staticClass: "col-md-10" }, [
              _c("input", {
                directives: [
                  {
                    name: "model",
                    rawName: "v-model",
                    value: _vm.data.title,
                    expression: "data.title",
                  },
                ],
                staticClass: "form-control",
                attrs: { type: "text", name: "name" },
                domProps: { value: _vm.data.title },
                on: {
                  input: function ($event) {
                    if ($event.target.composing) {
                      return
                    }
                    _vm.$set(_vm.data, "title", $event.target.value)
                  },
                },
              }),
            ]),
          ]),
          _vm._v(" "),
          _vm.locale_prop == "us"
            ? _c("div", { staticClass: "form-group clearfix row" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-md-4 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" Change URL title ")]
                ),
                _vm._v(" "),
                _c("div", { staticClass: "col-md-8" }, [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.is_change_url_title,
                        expression: "is_change_url_title",
                      },
                    ],
                    attrs: { type: "checkbox", id: "scales", name: "scales" },
                    domProps: {
                      checked: Array.isArray(_vm.is_change_url_title)
                        ? _vm._i(_vm.is_change_url_title, null) > -1
                        : _vm.is_change_url_title,
                    },
                    on: {
                      click: function ($event) {
                        return _vm.change_url_title_in_global_bisnes()
                      },
                      change: function ($event) {
                        var $$a = _vm.is_change_url_title,
                          $$el = $event.target,
                          $$c = $$el.checked ? true : false
                        if (Array.isArray($$a)) {
                          var $$v = null,
                            $$i = _vm._i($$a, $$v)
                          if ($$el.checked) {
                            $$i < 0 &&
                              (_vm.is_change_url_title = $$a.concat([$$v]))
                          } else {
                            $$i > -1 &&
                              (_vm.is_change_url_title = $$a
                                .slice(0, $$i)
                                .concat($$a.slice($$i + 1)))
                          }
                        } else {
                          _vm.is_change_url_title = $$c
                        }
                      },
                    },
                  }),
                ]),
              ])
            : _vm._e(),
          _vm._v(" "),
          _c("div", { staticClass: "form-group clearfix row" }, [
            _c(
              "label",
              { staticClass: "col-md-2 control-label", attrs: { for: "name" } },
              [_vm._v(" Short description ")]
            ),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "col-md-10" },
              [
                _c("ckeditor", {
                  attrs: { config: _vm.short_description_text_editor },
                  model: {
                    value: _vm.data.short_description,
                    callback: function ($$v) {
                      _vm.$set(_vm.data, "short_description", $$v)
                    },
                    expression: "data.short_description",
                  },
                }),
              ],
              1
            ),
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "form-group clearfix row" }, [
            _c(
              "label",
              { staticClass: "col-md-2 control-label", attrs: { for: "name" } },
              [_vm._v(" text ")]
            ),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "col-md-10" },
              [
                _c("ckeditor", {
                  attrs: { config: _vm.text_editor_config },
                  model: {
                    value: _vm.data.text,
                    callback: function ($$v) {
                      _vm.$set(_vm.data, "text", $$v)
                    },
                    expression: "data.text",
                  },
                }),
              ],
              1
            ),
          ]),
          _vm._v(" "),
          this.category == "outdoor" ? _c("hr") : _vm._e(),
          _vm._v(" "),
          _vm.general_infos.length
            ? _c("div", [
                this.category == "outdoor"
                  ? _c("div", { staticClass: "row" }, [
                      _c("div", { staticClass: "col-md-2" }, [
                        _c("input", {
                          attrs: {
                            type: "radio",
                            id: "routes_new_info",
                            name: "fav_language",
                            value: "new_info",
                          },
                          on: {
                            click: function ($event) {
                              return _vm.routes_action("new_info")
                            },
                          },
                        }),
                        _vm._v(" "),
                        _c("label", { attrs: { for: "routes_new_info" } }, [
                          _vm._v("New info"),
                        ]),
                        _c("br"),
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "col-md-2" }, [
                        _c("input", {
                          attrs: {
                            type: "radio",
                            id: "routes_befor",
                            name: "fav_language",
                            value: "befor",
                          },
                          on: {
                            click: function ($event) {
                              return _vm.routes_action("befor")
                            },
                          },
                        }),
                        _vm._v(" "),
                        _c("label", { attrs: { for: "routes_befor" } }, [
                          _vm._v("Befor"),
                        ]),
                        _c("br"),
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "col-md-2" }, [
                        _c("input", {
                          attrs: {
                            type: "radio",
                            id: "routes_after",
                            name: "fav_language",
                            value: "after",
                          },
                          on: {
                            click: function ($event) {
                              return _vm.routes_action("after")
                            },
                          },
                        }),
                        _vm._v(" "),
                        _c("label", { attrs: { for: "routes_after" } }, [
                          _vm._v("After"),
                        ]),
                        _c("br"),
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "col-md-2" }, [
                        _c("input", {
                          attrs: {
                            type: "radio",
                            id: "routes_instead",
                            name: "fav_language",
                            value: "instead",
                          },
                          on: {
                            click: function ($event) {
                              return _vm.routes_action("instead")
                            },
                          },
                        }),
                        _vm._v(" "),
                        _c("label", { attrs: { for: "routes_instead" } }, [
                          _vm._v("Instead"),
                        ]),
                      ]),
                    ])
                  : _vm._e(),
              ])
            : _vm._e(),
          _vm._v(" "),
          this.category == "outdoor"
            ? _c("div", { staticClass: "form-group clearfix row" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-md-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" Routes description ")]
                ),
                _vm._v(" "),
                _c("div", { staticClass: "col-md-10" }, [
                  _vm.global_blocks.routes_info == "befor"
                    ? _c("div", { staticClass: "col-md-12" }, [
                        _c(
                          "select",
                          {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.global_blocks.routes_info_id,
                                expression: "global_blocks.routes_info_id",
                              },
                            ],
                            staticClass: "form-control",
                            on: {
                              change: function ($event) {
                                var $$selectedVal = Array.prototype.filter
                                  .call($event.target.options, function (o) {
                                    return o.selected
                                  })
                                  .map(function (o) {
                                    var val = "_value" in o ? o._value : o.value
                                    return val
                                  })
                                _vm.$set(
                                  _vm.global_blocks,
                                  "routes_info_id",
                                  $event.target.multiple
                                    ? $$selectedVal
                                    : $$selectedVal[0]
                                )
                              },
                            },
                          },
                          _vm._l(_vm.general_infos, function (general_info) {
                            return _c(
                              "option",
                              {
                                key: general_info.id,
                                domProps: { value: general_info.id },
                              },
                              [_vm._v(_vm._s(general_info.title))]
                            )
                          }),
                          0
                        ),
                      ])
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.global_blocks.routes_info == "befor" ||
                  _vm.global_blocks.routes_info == "after" ||
                  _vm.global_blocks.routes_info == "new_info"
                    ? _c(
                        "div",
                        { staticClass: "col-md-12" },
                        [
                          _c("ckeditor", {
                            attrs: {
                              config: _vm.route_description_editor_config,
                            },
                            model: {
                              value: _vm.data.route,
                              callback: function ($$v) {
                                _vm.$set(_vm.data, "route", $$v)
                              },
                              expression: "data.route",
                            },
                          }),
                        ],
                        1
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.global_blocks.routes_info == "after" ||
                  _vm.global_blocks.routes_info == "instead"
                    ? _c("div", { staticClass: "col-md-12" }, [
                        _c(
                          "select",
                          {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.global_blocks.routes_info_id,
                                expression: "global_blocks.routes_info_id",
                              },
                            ],
                            staticClass: "form-control",
                            on: {
                              change: function ($event) {
                                var $$selectedVal = Array.prototype.filter
                                  .call($event.target.options, function (o) {
                                    return o.selected
                                  })
                                  .map(function (o) {
                                    var val = "_value" in o ? o._value : o.value
                                    return val
                                  })
                                _vm.$set(
                                  _vm.global_blocks,
                                  "routes_info_id",
                                  $event.target.multiple
                                    ? $$selectedVal
                                    : $$selectedVal[0]
                                )
                              },
                            },
                          },
                          _vm._l(_vm.general_infos, function (general_info) {
                            return _c(
                              "option",
                              {
                                key: general_info.id,
                                domProps: { value: general_info.id },
                              },
                              [_vm._v(_vm._s(general_info.title))]
                            )
                          }),
                          0
                        ),
                      ])
                    : _vm._e(),
                ]),
              ])
            : _vm._e(),
          _vm._v(" "),
          this.category != "mount_route"
            ? _c("div", { staticClass: "form-group clearfix row" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-md-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" How to get hear ")]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "col-md-10" },
                  [
                    _c("ckeditor", {
                      attrs: { config: _vm.how_get_editor_config },
                      model: {
                        value: _vm.data.how_get,
                        callback: function ($$v) {
                          _vm.$set(_vm.data, "how_get", $$v)
                        },
                        expression: "data.how_get",
                      },
                    }),
                  ],
                  1
                ),
              ])
            : _vm._e(),
          _vm._v(" "),
          this.category == "outdoor" || this.category == "ice"
            ? _c("hr")
            : _vm._e(),
          _vm._v(" "),
          _vm.general_infos.length
            ? _c("div", [
                this.category == "outdoor" || this.category == "ice"
                  ? _c("div", { staticClass: "row" }, [
                      _c("div", { staticClass: "col-md-2" }, [
                        _c("input", {
                          attrs: {
                            type: "radio",
                            id: "time_new_info",
                            name: "fav_language",
                            value: "new_info",
                          },
                          on: {
                            click: function ($event) {
                              return _vm.best_time_action("new_info")
                            },
                          },
                        }),
                        _vm._v(" "),
                        _c("label", { attrs: { for: "time_new_info" } }, [
                          _vm._v("New info"),
                        ]),
                        _c("br"),
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "col-md-2" }, [
                        _c("input", {
                          attrs: {
                            type: "radio",
                            id: "time_befor",
                            name: "fav_language",
                            value: "befor",
                          },
                          on: {
                            click: function ($event) {
                              return _vm.best_time_action("befor")
                            },
                          },
                        }),
                        _vm._v(" "),
                        _c("label", { attrs: { for: "time_befor" } }, [
                          _vm._v("Befor"),
                        ]),
                        _c("br"),
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "col-md-2" }, [
                        _c("input", {
                          attrs: {
                            type: "radio",
                            id: "time_after",
                            name: "fav_language",
                            value: "after",
                          },
                          on: {
                            click: function ($event) {
                              return _vm.best_time_action("after")
                            },
                          },
                        }),
                        _vm._v(" "),
                        _c("label", { attrs: { for: "time_after" } }, [
                          _vm._v("After"),
                        ]),
                        _c("br"),
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "col-md-2" }, [
                        _c("input", {
                          attrs: {
                            type: "radio",
                            id: "time_instead",
                            name: "fav_language",
                            value: "instead",
                          },
                          on: {
                            click: function ($event) {
                              return _vm.best_time_action("instead")
                            },
                          },
                        }),
                        _vm._v(" "),
                        _c("label", { attrs: { for: "time_instead" } }, [
                          _vm._v("Instead"),
                        ]),
                      ]),
                    ])
                  : _vm._e(),
              ])
            : _vm._e(),
          _vm._v(" "),
          this.category == "outdoor" || this.category == "ice"
            ? _c("div", { staticClass: "form-group clearfix row" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-md-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" Best time for climbing ")]
                ),
                _vm._v(" "),
                _c("div", { staticClass: "col-md-10" }, [
                  _vm.global_blocks.best_time == "befor"
                    ? _c("div", { staticClass: "col-md-12" }, [
                        _c(
                          "select",
                          {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.global_blocks.best_time_id,
                                expression: "global_blocks.best_time_id",
                              },
                            ],
                            staticClass: "form-control",
                            on: {
                              change: function ($event) {
                                var $$selectedVal = Array.prototype.filter
                                  .call($event.target.options, function (o) {
                                    return o.selected
                                  })
                                  .map(function (o) {
                                    var val = "_value" in o ? o._value : o.value
                                    return val
                                  })
                                _vm.$set(
                                  _vm.global_blocks,
                                  "best_time_id",
                                  $event.target.multiple
                                    ? $$selectedVal
                                    : $$selectedVal[0]
                                )
                              },
                            },
                          },
                          _vm._l(_vm.general_infos, function (general_info) {
                            return _c(
                              "option",
                              {
                                key: general_info.id,
                                domProps: { value: general_info.id },
                              },
                              [_vm._v(_vm._s(general_info.title))]
                            )
                          }),
                          0
                        ),
                      ])
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.global_blocks.best_time == "befor" ||
                  _vm.global_blocks.best_time == "after" ||
                  _vm.global_blocks.best_time == "new_info"
                    ? _c(
                        "div",
                        { staticClass: "col-md-12" },
                        [
                          _c("ckeditor", {
                            attrs: { config: _vm.best_time_editor_config },
                            model: {
                              value: _vm.data.best_time,
                              callback: function ($$v) {
                                _vm.$set(_vm.data, "best_time", $$v)
                              },
                              expression: "data.best_time",
                            },
                          }),
                        ],
                        1
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.global_blocks.best_time == "after" ||
                  _vm.global_blocks.best_time == "instead"
                    ? _c("div", { staticClass: "col-md-12" }, [
                        _c(
                          "select",
                          {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.global_blocks.best_time_id,
                                expression: "global_blocks.best_time_id",
                              },
                            ],
                            staticClass: "form-control",
                            on: {
                              change: function ($event) {
                                var $$selectedVal = Array.prototype.filter
                                  .call($event.target.options, function (o) {
                                    return o.selected
                                  })
                                  .map(function (o) {
                                    var val = "_value" in o ? o._value : o.value
                                    return val
                                  })
                                _vm.$set(
                                  _vm.global_blocks,
                                  "best_time_id",
                                  $event.target.multiple
                                    ? $$selectedVal
                                    : $$selectedVal[0]
                                )
                              },
                            },
                          },
                          _vm._l(_vm.general_infos, function (general_info) {
                            return _c(
                              "option",
                              {
                                key: general_info.id,
                                domProps: { value: general_info.id },
                              },
                              [_vm._v(_vm._s(general_info.title))]
                            )
                          }),
                          0
                        ),
                      ])
                    : _vm._e(),
                ]),
              ])
            : _vm._e(),
          _vm._v(" "),
          this.category == "outdoor" ||
          this.category == "ice" ||
          this.category == "mount_route"
            ? _c("hr")
            : _vm._e(),
          _vm._v(" "),
          _vm.general_infos.length
            ? _c("div", [
                this.category == "outdoor" ||
                this.category == "ice" ||
                this.category == "mount_route"
                  ? _c("div", { staticClass: "row" }, [
                      _c("div", { staticClass: "col-md-2" }, [
                        _c("input", {
                          attrs: {
                            type: "radio",
                            id: "need_new_info",
                            name: "fav_language",
                            value: "new_info",
                          },
                          on: {
                            click: function ($event) {
                              return _vm.what_need_block_action("new_info")
                            },
                          },
                        }),
                        _vm._v(" "),
                        _c("label", { attrs: { for: "need_new_info" } }, [
                          _vm._v("New info"),
                        ]),
                        _c("br"),
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "col-md-2" }, [
                        _c("input", {
                          attrs: {
                            type: "radio",
                            id: "need_befor",
                            name: "fav_language",
                            value: "befor",
                          },
                          on: {
                            click: function ($event) {
                              return _vm.what_need_block_action("befor")
                            },
                          },
                        }),
                        _vm._v(" "),
                        _c("label", { attrs: { for: "need_befor" } }, [
                          _vm._v("Befor"),
                        ]),
                        _c("br"),
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "col-md-2" }, [
                        _c("input", {
                          attrs: {
                            type: "radio",
                            id: "need_after",
                            name: "fav_language",
                            value: "after",
                          },
                          on: {
                            click: function ($event) {
                              return _vm.what_need_block_action("after")
                            },
                          },
                        }),
                        _vm._v(" "),
                        _c("label", { attrs: { for: "need_after" } }, [
                          _vm._v("After"),
                        ]),
                        _c("br"),
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "col-md-2" }, [
                        _c("input", {
                          attrs: {
                            type: "radio",
                            id: "need_instead",
                            name: "fav_language",
                            value: "instead",
                          },
                          on: {
                            click: function ($event) {
                              return _vm.what_need_block_action("instead")
                            },
                          },
                        }),
                        _vm._v(" "),
                        _c("label", { attrs: { for: "need_instead" } }, [
                          _vm._v("Instead"),
                        ]),
                      ]),
                    ])
                  : _vm._e(),
              ])
            : _vm._e(),
          _vm._v(" "),
          this.category == "outdoor" ||
          this.category == "ice" ||
          this.category == "mount_route"
            ? _c("div", { staticClass: "form-group clearfix row" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-md-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" what you need ")]
                ),
                _vm._v(" "),
                _c("div", { staticClass: "col-md-10" }, [
                  _vm.global_blocks.what_need_info == "befor"
                    ? _c("div", { staticClass: "col-md-12" }, [
                        _c(
                          "select",
                          {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.global_blocks.what_need_info_id,
                                expression: "global_blocks.what_need_info_id",
                              },
                            ],
                            staticClass: "form-control",
                            on: {
                              change: function ($event) {
                                var $$selectedVal = Array.prototype.filter
                                  .call($event.target.options, function (o) {
                                    return o.selected
                                  })
                                  .map(function (o) {
                                    var val = "_value" in o ? o._value : o.value
                                    return val
                                  })
                                _vm.$set(
                                  _vm.global_blocks,
                                  "what_need_info_id",
                                  $event.target.multiple
                                    ? $$selectedVal
                                    : $$selectedVal[0]
                                )
                              },
                            },
                          },
                          _vm._l(_vm.general_infos, function (general_info) {
                            return _c(
                              "option",
                              {
                                key: general_info.id,
                                domProps: { value: general_info.id },
                              },
                              [_vm._v(_vm._s(general_info.title))]
                            )
                          }),
                          0
                        ),
                      ])
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.global_blocks.what_need_info == "befor" ||
                  _vm.global_blocks.what_need_info == "after" ||
                  _vm.global_blocks.what_need_info == "new_info"
                    ? _c(
                        "div",
                        { staticClass: "col-md-12" },
                        [
                          _c("ckeditor", {
                            attrs: { config: _vm.what_need_editor_config },
                            model: {
                              value: _vm.data.what_need,
                              callback: function ($$v) {
                                _vm.$set(_vm.data, "what_need", $$v)
                              },
                              expression: "data.what_need",
                            },
                          }),
                        ],
                        1
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.global_blocks.what_need_info == "after" ||
                  _vm.global_blocks.what_need_info == "instead"
                    ? _c("div", { staticClass: "col-md-12" }, [
                        _c(
                          "select",
                          {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.global_blocks.what_need_info_id,
                                expression: "global_blocks.what_need_info_id",
                              },
                            ],
                            staticClass: "form-control",
                            on: {
                              change: function ($event) {
                                var $$selectedVal = Array.prototype.filter
                                  .call($event.target.options, function (o) {
                                    return o.selected
                                  })
                                  .map(function (o) {
                                    var val = "_value" in o ? o._value : o.value
                                    return val
                                  })
                                _vm.$set(
                                  _vm.global_blocks,
                                  "what_need_info_id",
                                  $event.target.multiple
                                    ? $$selectedVal
                                    : $$selectedVal[0]
                                )
                              },
                            },
                          },
                          _vm._l(_vm.general_infos, function (general_info) {
                            return _c(
                              "option",
                              {
                                key: general_info.id,
                                domProps: { value: general_info.id },
                              },
                              [_vm._v(_vm._s(general_info.title))]
                            )
                          }),
                          0
                        ),
                      ])
                    : _vm._e(),
                ]),
              ])
            : _vm._e(),
          _vm._v(" "),
          _c("hr"),
          _vm._v(" "),
          _vm.general_infos.length
            ? _c("div", [
                _c("div", { staticClass: "row" }, [
                  _c("div", { staticClass: "col-md-2" }, [
                    _c("input", {
                      attrs: {
                        type: "radio",
                        id: "new_info",
                        name: "fav_language",
                        value: "new_info",
                      },
                      on: {
                        click: function ($event) {
                          return _vm.info_block_action("new_info")
                        },
                      },
                    }),
                    _vm._v(" "),
                    _c("label", { attrs: { for: "new_info" } }, [
                      _vm._v("New info"),
                    ]),
                    _c("br"),
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "col-md-2" }, [
                    _c("input", {
                      attrs: {
                        type: "radio",
                        id: "befor",
                        name: "fav_language",
                        value: "befor",
                      },
                      on: {
                        click: function ($event) {
                          return _vm.info_block_action("befor")
                        },
                      },
                    }),
                    _vm._v(" "),
                    _c("label", { attrs: { for: "befor" } }, [_vm._v("Befor")]),
                    _c("br"),
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "col-md-2" }, [
                    _c("input", {
                      attrs: {
                        type: "radio",
                        id: "after",
                        name: "fav_language",
                        value: "after",
                      },
                      on: {
                        click: function ($event) {
                          return _vm.info_block_action("after")
                        },
                      },
                    }),
                    _vm._v(" "),
                    _c("label", { attrs: { for: "after" } }, [_vm._v("After")]),
                    _c("br"),
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "col-md-2" }, [
                    _c("input", {
                      attrs: {
                        type: "radio",
                        id: "instead",
                        name: "fav_language",
                        value: "instead",
                      },
                      on: {
                        click: function ($event) {
                          return _vm.info_block_action("instead")
                        },
                      },
                    }),
                    _vm._v(" "),
                    _c("label", { attrs: { for: "instead" } }, [
                      _vm._v("Instead"),
                    ]),
                  ]),
                ]),
              ])
            : _vm._e(),
          _vm._v(" "),
          _c("div", { staticClass: "form-group clearfix row" }, [
            _c(
              "label",
              { staticClass: "col-md-2 control-label", attrs: { for: "name" } },
              [_vm._v(" Info / contact ")]
            ),
            _vm._v(" "),
            _c("div", { staticClass: "col-md-10" }, [
              _vm.global_blocks.info_block == "befor"
                ? _c("div", { staticClass: "col-md-12" }, [
                    _c(
                      "select",
                      {
                        directives: [
                          {
                            name: "model",
                            rawName: "v-model",
                            value: _vm.global_blocks.info_block_id,
                            expression: "global_blocks.info_block_id",
                          },
                        ],
                        staticClass: "form-control",
                        on: {
                          change: function ($event) {
                            var $$selectedVal = Array.prototype.filter
                              .call($event.target.options, function (o) {
                                return o.selected
                              })
                              .map(function (o) {
                                var val = "_value" in o ? o._value : o.value
                                return val
                              })
                            _vm.$set(
                              _vm.global_blocks,
                              "info_block_id",
                              $event.target.multiple
                                ? $$selectedVal
                                : $$selectedVal[0]
                            )
                          },
                        },
                      },
                      _vm._l(_vm.general_infos, function (general_info) {
                        return _c(
                          "option",
                          {
                            key: general_info.id,
                            domProps: { value: general_info.id },
                          },
                          [_vm._v(_vm._s(general_info.title))]
                        )
                      }),
                      0
                    ),
                  ])
                : _vm._e(),
              _vm._v(" "),
              _vm.global_blocks.info_block == "befor" ||
              _vm.global_blocks.info_block == "after" ||
              _vm.global_blocks.info_block == "new_info"
                ? _c(
                    "div",
                    { staticClass: "col-md-12" },
                    [
                      _c("ckeditor", {
                        attrs: { config: this.info_editor_config },
                        model: {
                          value: _vm.data.info,
                          callback: function ($$v) {
                            _vm.$set(_vm.data, "info", $$v)
                          },
                          expression: "data.info",
                        },
                      }),
                    ],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.global_blocks.info_block == "after" ||
              _vm.global_blocks.info_block == "instead"
                ? _c("div", { staticClass: "col-md-12" }, [
                    _c(
                      "select",
                      {
                        directives: [
                          {
                            name: "model",
                            rawName: "v-model",
                            value: _vm.global_blocks.info_block_id,
                            expression: "global_blocks.info_block_id",
                          },
                        ],
                        staticClass: "form-control",
                        on: {
                          change: function ($event) {
                            var $$selectedVal = Array.prototype.filter
                              .call($event.target.options, function (o) {
                                return o.selected
                              })
                              .map(function (o) {
                                var val = "_value" in o ? o._value : o.value
                                return val
                              })
                            _vm.$set(
                              _vm.global_blocks,
                              "info_block_id",
                              $event.target.multiple
                                ? $$selectedVal
                                : $$selectedVal[0]
                            )
                          },
                        },
                      },
                      _vm._l(_vm.general_infos, function (general_info) {
                        return _c(
                          "option",
                          {
                            key: general_info.id,
                            domProps: { value: general_info.id },
                          },
                          [_vm._v(_vm._s(general_info.title))]
                        )
                      }),
                      0
                    ),
                  ])
                : _vm._e(),
            ]),
          ]),
          _vm._v(" "),
          this.category == "indoor"
            ? _c("div", { staticClass: "form-group clearfix row" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-md-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" Price description ")]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "col-md-10" },
                  [
                    _c("ckeditor", {
                      attrs: { config: this.price_text_editor_config },
                      model: {
                        value: _vm.data.price_text,
                        callback: function ($$v) {
                          _vm.$set(_vm.data, "price_text", $$v)
                        },
                        expression: "data.price_text",
                      },
                    }),
                  ],
                  1
                ),
              ])
            : _vm._e(),
        ]),
      ]),
    ]),
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/MountRouteImageFormComponent.vue?vue&type=template&id=10c0dca5&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/MountRouteImageFormComponent.vue?vue&type=template&id=10c0dca5& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "col-md-12" }, [
    _c("div", { staticClass: "row" }, [
      _vm._m(0),
      _vm._v(" "),
      _c("div", { staticClass: "col-md-12" }, [
        _c("div", { staticClass: "row" }, [
          _c("div", { staticClass: "col-md-12" }, [
            _c(
              "table",
              { staticClass: "table table-hover", attrs: { id: "dev-table" } },
              [
                _vm._m(1),
                _vm._v(" "),
                _c(
                  "tbody",
                  _vm._l(_vm.mount_route_old_images, function (image) {
                    return _c("tr", { key: image.id }, [
                      _c("td", [
                        _c("img", {
                          staticClass: "img-responsive",
                          attrs: {
                            src:
                              "../../../../images/suport_local_bisnes_img/" +
                              image.image,
                            alt: image.image,
                          },
                        }),
                      ]),
                      _vm._v(" "),
                      _c("td", [_vm._v("|")]),
                      _vm._v(" "),
                      _c("td", [
                        _c(
                          "button",
                          {
                            staticClass: "btn btn-danger",
                            on: {
                              click: function ($event) {
                                return _vm.del_old_mount_routes_image_from_db(
                                  image.id
                                )
                              },
                            },
                          },
                          [_vm._v("Delete")]
                        ),
                      ]),
                    ])
                  }),
                  0
                ),
              ]
            ),
          ]),
        ]),
      ]),
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-md-12" }, [
        _c("div", { staticClass: "form-groupe" }, [
          _c(
            "button",
            {
              staticClass: "btn btn-primary float-left",
              on: {
                click: function ($event) {
                  return _vm.add_new_mount_route_image_value()
                },
              },
            },
            [_vm._v("Add new mount route image")]
          ),
        ]),
      ]),
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-md-12" }, [
        _c(
          "table",
          { staticClass: "table table-hover", attrs: { id: "dev-table" } },
          [
            _vm._m(2),
            _vm._v(" "),
            _c(
              "tbody",
              _vm._l(_vm.mount_route_images, function (mount_route_image) {
                return _c("tr", { key: mount_route_image.id }, [
                  _c("td", [
                    _c("form", { ref: "myForm", refInFor: true }, [
                      _c("input", {
                        attrs: { type: "file", name: "image", id: "image" },
                        on: {
                          change: function ($event) {
                            return _vm.onFileChange(
                              $event,
                              mount_route_image.id
                            )
                          },
                        },
                      }),
                    ]),
                  ]),
                  _vm._v(" "),
                  _c("td", [_vm._v("|")]),
                  _vm._v(" "),
                  _c("td", [
                    _c(
                      "button",
                      {
                        staticClass: "btn btn-danger",
                        on: {
                          click: function ($event) {
                            return _vm.del_mount_route_image(
                              mount_route_image.id
                            )
                          },
                        },
                      },
                      [_vm._v("Delete")]
                    ),
                  ]),
                ])
              }),
              0
            ),
          ]
        ),
      ]),
    ]),
  ])
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "col-md-12" }, [
      _c("div", { staticClass: "row" }, [
        _vm._v("\n                Olredy added\n            "),
      ]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c("tr", [
        _c("th", [_vm._v("Image")]),
        _vm._v(" "),
        _c("th", [_vm._v("|")]),
        _vm._v(" "),
        _c("th", [_vm._v("Delite")]),
      ]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c("tr", [
        _c("th", [_vm._v("Image")]),
        _vm._v(" "),
        _c("th", [_vm._v("|")]),
        _vm._v(" "),
        _c("th", [_vm._v("Delite")]),
      ]),
    ])
  },
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/SectorImagesFormComponent.vue?vue&type=template&id=1b4fd0f4&":
/*!**************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/SectorImagesFormComponent.vue?vue&type=template&id=1b4fd0f4& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "col-md-12" }, [
    _c("div", { staticClass: "row" }, [
      _vm._m(0),
      _vm._v(" "),
      _c("div", { staticClass: "col-md-12" }, [
        _c("div", { staticClass: "row" }, [
          _c("div", { staticClass: "col-md-12" }, [
            _c(
              "table",
              { staticClass: "table table-hover", attrs: { id: "dev-table" } },
              [
                _vm._m(1),
                _vm._v(" "),
                _c(
                  "tbody",
                  _vm._l(_vm.spot_rocks_old_images, function (image) {
                    return _c("tr", { key: image.id }, [
                      _c("td", [
                        _c("img", {
                          staticClass: "img-responsive",
                          attrs: {
                            src:
                              "../../../../images/spot_rocks_img/" +
                              image.image,
                            alt: image.image,
                          },
                        }),
                      ]),
                      _vm._v(" "),
                      _c("td", [_vm._v("|")]),
                      _vm._v(" "),
                      _c("td", [
                        _c(
                          "button",
                          {
                            staticClass: "btn btn-danger",
                            on: {
                              click: function ($event) {
                                return _vm.del_old_sector_image_from_db(
                                  image.id
                                )
                              },
                            },
                          },
                          [_vm._v("Delete")]
                        ),
                      ]),
                    ])
                  }),
                  0
                ),
              ]
            ),
          ]),
        ]),
      ]),
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-md-12" }, [
        _c("div", { staticClass: "form-groupe" }, [
          _c(
            "button",
            {
              staticClass: "btn btn-primary float-left",
              on: {
                click: function ($event) {
                  return _vm.add_new_sector_image_value()
                },
              },
            },
            [_vm._v("Add new sector image")]
          ),
        ]),
      ]),
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-md-12" }, [
        _c(
          "table",
          { staticClass: "table table-hover", attrs: { id: "dev-table" } },
          [
            _vm._m(2),
            _vm._v(" "),
            _c(
              "tbody",
              _vm._l(_vm.sector_images, function (sector_image) {
                return _c("tr", { key: sector_image.id }, [
                  _c("td", [
                    _c("form", { ref: "myForm", refInFor: true }, [
                      _c("input", {
                        attrs: { type: "file", name: "image", id: "image" },
                        on: {
                          change: function ($event) {
                            return _vm.onFileChange($event, sector_image.id)
                          },
                        },
                      }),
                    ]),
                  ]),
                  _vm._v(" "),
                  _c("td", [_vm._v("|")]),
                  _vm._v(" "),
                  _c("td", [
                    _c(
                      "button",
                      {
                        staticClass: "btn btn-danger",
                        on: {
                          click: function ($event) {
                            return _vm.del_sector_image(sector_image.id)
                          },
                        },
                      },
                      [_vm._v("Delete")]
                    ),
                  ]),
                ])
              }),
              0
            ),
          ]
        ),
      ]),
    ]),
  ])
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "col-md-12" }, [
      _c("div", { staticClass: "row" }, [
        _vm._v("\n                Olredy added\n            "),
      ]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c("tr", [
        _c("th", [_vm._v("Image")]),
        _vm._v(" "),
        _c("th", [_vm._v("|")]),
        _vm._v(" "),
        _c("th", [_vm._v("Delite")]),
      ]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c("tr", [
        _c("th", [_vm._v("Image")]),
        _vm._v(" "),
        _c("th", [_vm._v("|")]),
        _vm._v(" "),
        _c("th", [_vm._v("Delite")]),
      ]),
    ])
  },
]
render._withStripped = true



/***/ })

}]);