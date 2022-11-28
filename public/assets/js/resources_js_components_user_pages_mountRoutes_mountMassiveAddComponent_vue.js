"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_mountRoutes_mountMassiveAddComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  components: {// StackModal,
  },
  props: [// 'back_url',
    // 'category'
  ],
  data: function data() {
    return {
      data: {
        us_data: {
          title: '',
          short_description: '',
          text: '',
          route: '',
          how_get: '',
          best_time: ''
        },
        ru_data: {
          title: '',
          short_description: '',
          text: '',
          route: '',
          how_get: '',
          best_time: ''
        },
        ka_data: {
          title: '',
          short_description: '',
          text: '',
          route: '',
          how_get: '',
          best_time: ''
        },
        global_data: {
          demo_image: "",
          map: "",
          weather: ""
        }
      },
      tab_num: 1,
      error: [],
      is_back_action: false
    };
  },
  mounted: function mounted() {},
  beforeRouteLeave: function beforeRouteLeave(to, from, next) {
    if (this.is_back_action = true) {
      if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
        this.is_back_action = false;
        next();
      } else {
        next(false);
      }
    }
  },
  methods: {
    global_blocks_action: function global_blocks_action(event) {
      this.global_blocks = event;
    },
    add_mount_massive_region: function add_mount_massive_region() {
      var _this = this;

      axios.post('../../api/mount/', {
        data: this.data,
        _method: 'post'
      }).then(function (response) {
        _this.$router.go(-1);
      })["catch"](function (err) {
        console.log(err);
      });
    },
    go_back: function go_back() {
      this.is_back_action = true;
      this.$router.go(-1);
    }
  }
});

/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue?vue&type=style&index=0&id=902298f2&scoped=true&lang=css&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue?vue&type=style&index=0&id=902298f2&scoped=true&lang=css& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
___CSS_LOADER_EXPORT___.push([module.id, "\n.tabs[data-v-902298f2] {\n    /* display: flex; */\n    flex-wrap: wrap;\n}\n.tabs label[data-v-902298f2] {\n    order: 1;\n    display: block;\n    padding: 1rem 2rem;\n    margin-right: 0.2rem;\n    cursor: pointer;\n    background: #ccced0;\n    font-weight: bold;\n    transition: background ease 0.2s;\n}\n.tabs .tab[data-v-902298f2] {\n    order: 99;\n    flex-grow: 1;\n    width: 100%;\n    display: none;\n    padding: 1rem;\n    background: #fff;\n    /* border: 1px solid #ccc!important; */\n}\n.tabs input[type=\"radio\"][data-v-902298f2] {\n    display: none;\n}\n.tabs input[type=\"radio\"]:checked + label[data-v-902298f2] {\n    background: #fff;\n    border: 1px solid #ccc !important;\n}\n.tabs input[type=\"radio\"]:checked + label + .tab[data-v-902298f2] {\n    display: block;\n}\n@media (max-width: 45em) {\n.tabs .tab[data-v-902298f2],\n    .tabs label[data-v-902298f2] {\n        order: initial;\n}\n.tabs label[data-v-902298f2] {\n        width: 100%;\n        margin-right: 0;\n        margin-top: 0.2rem;\n}\n}\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue?vue&type=style&index=0&id=902298f2&scoped=true&lang=css&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue?vue&type=style&index=0&id=902298f2&scoped=true&lang=css& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_mountMassiveAddComponent_vue_vue_type_style_index_0_id_902298f2_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./mountMassiveAddComponent.vue?vue&type=style&index=0&id=902298f2&scoped=true&lang=css& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue?vue&type=style&index=0&id=902298f2&scoped=true&lang=css&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_mountMassiveAddComponent_vue_vue_type_style_index_0_id_902298f2_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_mountMassiveAddComponent_vue_vue_type_style_index_0_id_902298f2_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue":
/*!*************************************************************************************!*\
  !*** ./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue ***!
  \*************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _mountMassiveAddComponent_vue_vue_type_template_id_902298f2_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./mountMassiveAddComponent.vue?vue&type=template&id=902298f2&scoped=true& */ "./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue?vue&type=template&id=902298f2&scoped=true&");
/* harmony import */ var _mountMassiveAddComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./mountMassiveAddComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _mountMassiveAddComponent_vue_vue_type_style_index_0_id_902298f2_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./mountMassiveAddComponent.vue?vue&type=style&index=0&id=902298f2&scoped=true&lang=css& */ "./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue?vue&type=style&index=0&id=902298f2&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _mountMassiveAddComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _mountMassiveAddComponent_vue_vue_type_template_id_902298f2_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _mountMassiveAddComponent_vue_vue_type_template_id_902298f2_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "902298f2",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_mountMassiveAddComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./mountMassiveAddComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_mountMassiveAddComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue?vue&type=style&index=0&id=902298f2&scoped=true&lang=css&":
/*!**********************************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue?vue&type=style&index=0&id=902298f2&scoped=true&lang=css& ***!
  \**********************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_mountMassiveAddComponent_vue_vue_type_style_index_0_id_902298f2_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader/dist/cjs.js!../../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./mountMassiveAddComponent.vue?vue&type=style&index=0&id=902298f2&scoped=true&lang=css& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue?vue&type=style&index=0&id=902298f2&scoped=true&lang=css&");


/***/ }),

/***/ "./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue?vue&type=template&id=902298f2&scoped=true&":
/*!********************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue?vue&type=template&id=902298f2&scoped=true& ***!
  \********************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_mountMassiveAddComponent_vue_vue_type_template_id_902298f2_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_mountMassiveAddComponent_vue_vue_type_template_id_902298f2_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_mountMassiveAddComponent_vue_vue_type_template_id_902298f2_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./mountMassiveAddComponent.vue?vue&type=template&id=902298f2&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue?vue&type=template&id=902298f2&scoped=true&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue?vue&type=template&id=902298f2&scoped=true&":
/*!***********************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/mountRoutes/mountMassiveAddComponent.vue?vue&type=template&id=902298f2&scoped=true& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************/
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
                return _vm.add_mount_massive_region()
              },
            },
          },
          [_vm._v("Save")]
        ),
      ]),
    ]),
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
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "col-md-12" }, [
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
            staticClass: "row width_100",
          },
          [
            _vm._m(0),
            _vm._v(" "),
            _c("form", [
              _c("div", { staticClass: "form-group clearfix row" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-xs-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" Demo name ")]
                ),
                _vm._v(" "),
                _c("div", { staticClass: "col-xs-10" }, [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.data.global_data.demo_name,
                        expression: "data.global_data.demo_name",
                      },
                    ],
                    staticClass: "form-control",
                    attrs: { type: "text", name: "demo name" },
                    domProps: { value: _vm.data.global_data.demo_name },
                    on: {
                      input: function ($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(
                          _vm.data.global_data,
                          "demo_name",
                          $event.target.value
                        )
                      },
                    },
                  }),
                ]),
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "form-group clearfix" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-xs-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" Map ")]
                ),
                _vm._v(" "),
                _c("div", { staticClass: "col-xs-10" }, [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.data.global_data.map,
                        expression: "data.global_data.map",
                      },
                    ],
                    staticClass: "form-control",
                    attrs: { type: "text", name: "map" },
                    domProps: { value: _vm.data.global_data.map },
                    on: {
                      input: function ($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(
                          _vm.data.global_data,
                          "map",
                          $event.target.value
                        )
                      },
                    },
                  }),
                ]),
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "form-group clearfix" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-xs-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" Weather ")]
                ),
                _vm._v(" "),
                _c("div", { staticClass: "col-xs-10" }, [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.data.global_data.weather,
                        expression: "data.global_data.weather",
                      },
                    ],
                    staticClass: "form-control",
                    attrs: { type: "text", name: "weather" },
                    domProps: { value: _vm.data.global_data.weather },
                    on: {
                      input: function ($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(
                          _vm.data.global_data,
                          "weather",
                          $event.target.value
                        )
                      },
                    },
                  }),
                ]),
              ]),
            ]),
          ]
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
            staticClass: "row",
          },
          [
            _vm._m(1),
            _vm._v(" "),
            _c("form", [
              _c("div", { staticClass: "form-group clearfix" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-xs-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" Title english ")]
                ),
                _vm._v(" "),
                _c("div", { staticClass: "col-xs-10" }, [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.data.us_data.name,
                        expression: "data.us_data.name",
                      },
                    ],
                    staticClass: "form-control",
                    attrs: { type: "text", name: "name" },
                    domProps: { value: _vm.data.us_data.name },
                    on: {
                      input: function ($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.data.us_data, "name", $event.target.value)
                      },
                    },
                  }),
                ]),
              ]),
              _vm._v(" "),
              _c("hr"),
              _vm._v(" "),
              _c("div", { staticClass: "form-group clearfix" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-xs-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" English text ")]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "col-xs-10" },
                  [
                    _c("ckeditor", {
                      attrs: { config: _vm.editorConfig },
                      model: {
                        value: _vm.data.us_data.text,
                        callback: function ($$v) {
                          _vm.$set(_vm.data.us_data, "text", $$v)
                        },
                        expression: "data.us_data.text",
                      },
                    }),
                  ],
                  1
                ),
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "form-group clearfix" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-xs-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" English description ")]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "col-xs-10" },
                  [
                    _c("ckeditor", {
                      attrs: { config: _vm.editorConfig },
                      model: {
                        value: _vm.data.us_data.short_description,
                        callback: function ($$v) {
                          _vm.$set(_vm.data.us_data, "short_description", $$v)
                        },
                        expression: "data.us_data.short_description",
                      },
                    }),
                  ],
                  1
                ),
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "form-group clearfix" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-xs-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" English how get hear ")]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "col-xs-10" },
                  [
                    _c("ckeditor", {
                      attrs: { config: _vm.editorConfig },
                      model: {
                        value: _vm.data.us_data.how_get,
                        callback: function ($$v) {
                          _vm.$set(_vm.data.us_data, "how_get", $$v)
                        },
                        expression: "data.us_data.how_get",
                      },
                    }),
                  ],
                  1
                ),
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "form-group clearfix" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-xs-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" English best time ")]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "col-xs-10" },
                  [
                    _c("ckeditor", {
                      attrs: { config: _vm.editorConfig },
                      model: {
                        value: _vm.data.us_data.best_time,
                        callback: function ($$v) {
                          _vm.$set(_vm.data.us_data, "best_time", $$v)
                        },
                        expression: "data.us_data.best_time",
                      },
                    }),
                  ],
                  1
                ),
              ]),
            ]),
          ]
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
            staticClass: "row",
          },
          [
            _vm._m(2),
            _vm._v(" "),
            _c("form", [
              _c("div", { staticClass: "form-group clearfix" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-xs-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" Title rusian ")]
                ),
                _vm._v(" "),
                _c("div", { staticClass: "col-xs-10" }, [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.data.ru_data.name,
                        expression: "data.ru_data.name",
                      },
                    ],
                    staticClass: "form-control",
                    attrs: { type: "text", name: "name_ru" },
                    domProps: { value: _vm.data.ru_data.name },
                    on: {
                      input: function ($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.data.ru_data, "name", $event.target.value)
                      },
                    },
                  }),
                ]),
              ]),
              _vm._v(" "),
              _c("hr"),
              _vm._v(" "),
              _c("div", { staticClass: "form-group clearfix" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-xs-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" Rusian text ")]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "col-xs-10" },
                  [
                    _c("ckeditor", {
                      attrs: { config: _vm.editorConfig },
                      model: {
                        value: _vm.data.ru_data.text,
                        callback: function ($$v) {
                          _vm.$set(_vm.data.ru_data, "text", $$v)
                        },
                        expression: "data.ru_data.text",
                      },
                    }),
                  ],
                  1
                ),
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "form-group clearfix" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-xs-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" Rusian description ")]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "col-xs-10" },
                  [
                    _c("ckeditor", {
                      attrs: { config: _vm.editorConfig },
                      model: {
                        value: _vm.data.ru_data.short_description,
                        callback: function ($$v) {
                          _vm.$set(_vm.data.ru_data, "short_description", $$v)
                        },
                        expression: "data.ru_data.short_description",
                      },
                    }),
                  ],
                  1
                ),
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "form-group clearfix" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-xs-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" Rusian how get hear ")]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "col-xs-10" },
                  [
                    _c("ckeditor", {
                      attrs: { config: _vm.editorConfig },
                      model: {
                        value: _vm.data.ru_data.how_get,
                        callback: function ($$v) {
                          _vm.$set(_vm.data.ru_data, "how_get", $$v)
                        },
                        expression: "data.ru_data.how_get",
                      },
                    }),
                  ],
                  1
                ),
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "form-group clearfix" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-xs-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" Rusian best time ")]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "col-xs-10" },
                  [
                    _c("ckeditor", {
                      attrs: { config: _vm.editorConfig },
                      model: {
                        value: _vm.data.ru_data.best_time,
                        callback: function ($$v) {
                          _vm.$set(_vm.data.ru_data, "best_time", $$v)
                        },
                        expression: "data.ru_data.best_time",
                      },
                    }),
                  ],
                  1
                ),
              ]),
            ]),
          ]
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
            staticClass: "row",
          },
          [
            _vm._m(3),
            _vm._v(" "),
            _c("form", [
              _c("div", { staticClass: "form-group clearfix" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-xs-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" Title georgian ")]
                ),
                _vm._v(" "),
                _c("div", { staticClass: "col-xs-10" }, [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.data.ka_data.name,
                        expression: "data.ka_data.name",
                      },
                    ],
                    staticClass: "form-control",
                    attrs: { type: "text", name: "name_ka" },
                    domProps: { value: _vm.data.ka_data.name },
                    on: {
                      input: function ($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.data.ka_data, "name", $event.target.value)
                      },
                    },
                  }),
                ]),
              ]),
              _vm._v(" "),
              _c("hr"),
              _vm._v(" "),
              _c("div", { staticClass: "form-group clearfix" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-xs-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" Georgian text ")]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "col-xs-10" },
                  [
                    _c("ckeditor", {
                      attrs: { config: _vm.editorConfig },
                      model: {
                        value: _vm.data.ka_data.text,
                        callback: function ($$v) {
                          _vm.$set(_vm.data.ka_data, "text", $$v)
                        },
                        expression: "data.ka_data.text",
                      },
                    }),
                  ],
                  1
                ),
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "form-group clearfix" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-xs-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" Georgian description ")]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "col-xs-10" },
                  [
                    _c("ckeditor", {
                      attrs: { config: _vm.editorConfig },
                      model: {
                        value: _vm.data.ka_data.short_description,
                        callback: function ($$v) {
                          _vm.$set(_vm.data.ka_data, "short_description", $$v)
                        },
                        expression: "data.ka_data.short_description",
                      },
                    }),
                  ],
                  1
                ),
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "form-group clearfix" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-xs-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" Georgian how get hear ")]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "col-xs-10" },
                  [
                    _c("ckeditor", {
                      attrs: { config: _vm.editorConfig },
                      model: {
                        value: _vm.data.ka_data.how_get,
                        callback: function ($$v) {
                          _vm.$set(_vm.data.ka_data, "how_get", $$v)
                        },
                        expression: "data.ka_data.how_get",
                      },
                    }),
                  ],
                  1
                ),
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "form-group clearfix" }, [
                _c(
                  "label",
                  {
                    staticClass: "col-xs-2 control-label",
                    attrs: { for: "name" },
                  },
                  [_vm._v(" Georgian best time ")]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "col-xs-10" },
                  [
                    _c("ckeditor", {
                      attrs: { config: _vm.editorConfig },
                      model: {
                        value: _vm.data.ka_data.best_time,
                        callback: function ($$v) {
                          _vm.$set(_vm.data.ka_data, "best_time", $$v)
                        },
                        expression: "data.ka_data.best_time",
                      },
                    }),
                  ],
                  1
                ),
              ]),
            ]),
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
    return _c("div", { staticClass: "jumbotron width_100" }, [
      _c("div", { staticClass: "row" }, [
        _c("div", { staticClass: "col-md-12" }, [
          _c("h2", { staticClass: "display-4" }, [
            _c("span", [_vm._v("Region global information")]),
          ]),
          _vm._v(" "),
          _c("p", { staticClass: "lead" }, [
            _vm._v("Region global information."),
          ]),
        ]),
      ]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "jumbotron width_100" }, [
      _c("div", { staticClass: "container" }, [
        _c("h2", { staticClass: "display-4" }, [
          _c("span", [_vm._v("Region English information")]),
        ]),
        _vm._v(" "),
        _c("p", { staticClass: "lead" }, [
          _vm._v("Region English information."),
        ]),
      ]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "jumbotron width_100" }, [
      _c("div", { staticClass: "container" }, [
        _c("h2", { staticClass: "display-4" }, [
          _c("span", [_vm._v("Region Georgian information")]),
        ]),
        _vm._v(" "),
        _c("p", { staticClass: "lead" }, [
          _vm._v("Region Georgian information."),
        ]),
      ]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "jumbotron width_100" }, [
      _c("div", { staticClass: "container" }, [
        _c("h2", { staticClass: "display-4" }, [
          _c("span", [_vm._v("Region Russion information")]),
        ]),
        _vm._v(" "),
        _c("p", { staticClass: "lead" }, [
          _vm._v("Region Russion information."),
        ]),
      ]),
    ])
  },
]
render._withStripped = true



/***/ })

}]);