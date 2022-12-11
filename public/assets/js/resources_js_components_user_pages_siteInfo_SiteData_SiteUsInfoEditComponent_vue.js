"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_siteInfo_SiteData_SiteUsInfoEditComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/siteInfo/SiteData/SiteUsInfoEditComponent.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/siteInfo/SiteData/SiteUsInfoEditComponent.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _forms_SiteLocaleDataEditComponent_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./forms/SiteLocaleDataEditComponent.vue */ "./resources/js/components/user/pages/siteInfo/SiteData/forms/SiteLocaleDataEditComponent.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  // props: [
  //     'locale_data_prop',
  // ],
  components: {
    localeInfoForm: _forms_SiteLocaleDataEditComponent_vue__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  data: function data() {
    return {
      tab_num: 2,
      site_us_info: []
    };
  },
  mounted: function mounted() {
    this.get_site_us_data();
  },
  methods: {
    get_site_us_data: function get_site_us_data() {
      var _this = this;

      axios.get('../../api/siteData/get_site_us_data').then(function (response) {
        _this.site_us_info = response.data;
      })["catch"](function (error) {
        return console.log(error);
      });
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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/siteInfo/SiteData/forms/SiteLocaleDataEditComponent.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/siteInfo/SiteData/forms/SiteLocaleDataEditComponent.vue?vue&type=script&lang=js& ***!
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
  props: ['locale_data_prop'],
  data: function data() {
    return {
      // editor: 'editor',
      editorConfig: {},
      locale_data: []
    };
  },
  mounted: function mounted() {
    // console.log(this.locale_data_prop);
    this.locale_data = this.locale_data_prop; // this.$emit('locale_form_data', this.locale_data)
  }
});

/***/ }),

/***/ "./resources/js/components/user/pages/siteInfo/SiteData/SiteUsInfoEditComponent.vue":
/*!******************************************************************************************!*\
  !*** ./resources/js/components/user/pages/siteInfo/SiteData/SiteUsInfoEditComponent.vue ***!
  \******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _SiteUsInfoEditComponent_vue_vue_type_template_id_1db3d110___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SiteUsInfoEditComponent.vue?vue&type=template&id=1db3d110& */ "./resources/js/components/user/pages/siteInfo/SiteData/SiteUsInfoEditComponent.vue?vue&type=template&id=1db3d110&");
/* harmony import */ var _SiteUsInfoEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SiteUsInfoEditComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/siteInfo/SiteData/SiteUsInfoEditComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _SiteUsInfoEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _SiteUsInfoEditComponent_vue_vue_type_template_id_1db3d110___WEBPACK_IMPORTED_MODULE_0__.render,
  _SiteUsInfoEditComponent_vue_vue_type_template_id_1db3d110___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/siteInfo/SiteData/SiteUsInfoEditComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/siteInfo/SiteData/forms/SiteLocaleDataEditComponent.vue":
/*!****************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/siteInfo/SiteData/forms/SiteLocaleDataEditComponent.vue ***!
  \****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _SiteLocaleDataEditComponent_vue_vue_type_template_id_643e3760___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SiteLocaleDataEditComponent.vue?vue&type=template&id=643e3760& */ "./resources/js/components/user/pages/siteInfo/SiteData/forms/SiteLocaleDataEditComponent.vue?vue&type=template&id=643e3760&");
/* harmony import */ var _SiteLocaleDataEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SiteLocaleDataEditComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/siteInfo/SiteData/forms/SiteLocaleDataEditComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _SiteLocaleDataEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _SiteLocaleDataEditComponent_vue_vue_type_template_id_643e3760___WEBPACK_IMPORTED_MODULE_0__.render,
  _SiteLocaleDataEditComponent_vue_vue_type_template_id_643e3760___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/siteInfo/SiteData/forms/SiteLocaleDataEditComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/siteInfo/SiteData/SiteUsInfoEditComponent.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/siteInfo/SiteData/SiteUsInfoEditComponent.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SiteUsInfoEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./SiteUsInfoEditComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/siteInfo/SiteData/SiteUsInfoEditComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SiteUsInfoEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/siteInfo/SiteData/forms/SiteLocaleDataEditComponent.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/siteInfo/SiteData/forms/SiteLocaleDataEditComponent.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SiteLocaleDataEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./SiteLocaleDataEditComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/siteInfo/SiteData/forms/SiteLocaleDataEditComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SiteLocaleDataEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/siteInfo/SiteData/SiteUsInfoEditComponent.vue?vue&type=template&id=1db3d110&":
/*!*************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/siteInfo/SiteData/SiteUsInfoEditComponent.vue?vue&type=template&id=1db3d110& ***!
  \*************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SiteUsInfoEditComponent_vue_vue_type_template_id_1db3d110___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SiteUsInfoEditComponent_vue_vue_type_template_id_1db3d110___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SiteUsInfoEditComponent_vue_vue_type_template_id_1db3d110___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./SiteUsInfoEditComponent.vue?vue&type=template&id=1db3d110& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/siteInfo/SiteData/SiteUsInfoEditComponent.vue?vue&type=template&id=1db3d110&");


/***/ }),

/***/ "./resources/js/components/user/pages/siteInfo/SiteData/forms/SiteLocaleDataEditComponent.vue?vue&type=template&id=643e3760&":
/*!***********************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/siteInfo/SiteData/forms/SiteLocaleDataEditComponent.vue?vue&type=template&id=643e3760& ***!
  \***********************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SiteLocaleDataEditComponent_vue_vue_type_template_id_643e3760___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SiteLocaleDataEditComponent_vue_vue_type_template_id_643e3760___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SiteLocaleDataEditComponent_vue_vue_type_template_id_643e3760___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./SiteLocaleDataEditComponent.vue?vue&type=template&id=643e3760& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/siteInfo/SiteData/forms/SiteLocaleDataEditComponent.vue?vue&type=template&id=643e3760&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/siteInfo/SiteData/SiteUsInfoEditComponent.vue?vue&type=template&id=1db3d110&":
/*!****************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/siteInfo/SiteData/SiteUsInfoEditComponent.vue?vue&type=template&id=1db3d110& ***!
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
    _vm._m(0),
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
                value: _vm.tab_num == 2,
                expression: "tab_num == 2",
              },
            ],
            staticClass: "row",
          },
          [
            _vm._m(1),
            _vm._v(" "),
            _c("localeInfoForm", {
              attrs: { locale_data_prop: _vm.site_us_info },
            }),
          ],
          1
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
    return _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "form-group" }),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "width_100 jumbotron jumbotron-fluid" }, [
      _c("div", { staticClass: "container" }, [
        _c("h2", { staticClass: "display-4" }, [
          _vm._v("Site english version"),
        ]),
        _vm._v(" "),
        _c("p", { staticClass: "lead" }, [
          _vm._v("Site english version for site localisation."),
        ]),
      ]),
    ])
  },
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/siteInfo/SiteData/forms/SiteLocaleDataEditComponent.vue?vue&type=template&id=643e3760&":
/*!**************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/siteInfo/SiteData/forms/SiteLocaleDataEditComponent.vue?vue&type=template&id=643e3760& ***!
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
  return _c(
    "form",
    {
      staticClass: "width_100",
      staticStyle: { "margin-top": "5%" },
      attrs: {
        id: "us_form",
        name: "contact-form",
        method: "POST",
        enctyp: "multipart/form-data",
      },
      on: {
        submit: function ($event) {
          $event.preventDefault()
          return _vm.edit_article.apply(null, arguments)
        },
      },
    },
    [
      _c("div", { staticClass: "form-group clearfix" }, [
        _c(
          "label",
          { staticClass: "col-xs-2 control-label", attrs: { for: "name" } },
          [_vm._v(" Title ")]
        ),
        _vm._v(" "),
        _c("div", { staticClass: "col-xs-8" }, [
          _c("input", {
            directives: [
              {
                name: "model",
                rawName: "v-model",
                value: _vm.locale_data.title,
                expression: "locale_data.title",
              },
            ],
            staticClass: "form-control",
            attrs: { type: "text", name: "value name" },
            domProps: { value: _vm.locale_data.title },
            on: {
              input: function ($event) {
                if ($event.target.composing) {
                  return
                }
                _vm.$set(_vm.locale_data, "title", $event.target.value)
              },
            },
          }),
        ]),
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "form-group clearfix" }, [
        _c(
          "label",
          { staticClass: "col-xs-2 control-label", attrs: { for: "name" } },
          [_vm._v(" Short description ")]
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "col-xs-8" },
          [
            _c("ckeditor", {
              attrs: { config: _vm.editorConfig },
              model: {
                value: _vm.locale_data.short_description,
                callback: function ($$v) {
                  _vm.$set(_vm.locale_data, "short_description", $$v)
                },
                expression: "locale_data.short_description",
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
          { staticClass: "col-xs-2 control-label", attrs: { for: "name" } },
          [_vm._v(" text ")]
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "col-xs-8" },
          [
            _c("ckeditor", {
              attrs: { config: _vm.editorConfig },
              model: {
                value: _vm.locale_data.text,
                callback: function ($$v) {
                  _vm.$set(_vm.locale_data, "text", $$v)
                },
                expression: "locale_data.text",
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
          { staticClass: "col-xs-2 control-label", attrs: { for: "name" } },
          [_vm._v(" ice_description ")]
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "col-xs-8" },
          [
            _c("ckeditor", {
              attrs: { config: _vm.editorConfig },
              model: {
                value: _vm.locale_data.ice_description,
                callback: function ($$v) {
                  _vm.$set(_vm.locale_data, "ice_description", $$v)
                },
                expression: "locale_data.ice_description",
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
          { staticClass: "col-xs-2 control-label", attrs: { for: "name" } },
          [_vm._v(" mount_description ")]
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "col-xs-8" },
          [
            _c("ckeditor", {
              attrs: { config: _vm.editorConfig },
              model: {
                value: _vm.locale_data.mount_description,
                callback: function ($$v) {
                  _vm.$set(_vm.locale_data, "mount_description", $$v)
                },
                expression: "locale_data.mount_description",
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
          { staticClass: "col-xs-2 control-label", attrs: { for: "name" } },
          [_vm._v(" outdoor_description ")]
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "col-xs-8" },
          [
            _c("ckeditor", {
              attrs: { config: _vm.editorConfig },
              model: {
                value: _vm.locale_data.outdoor_description,
                callback: function ($$v) {
                  _vm.$set(_vm.locale_data, "outdoor_description", $$v)
                },
                expression: "locale_data.outdoor_description",
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
          { staticClass: "col-xs-2 control-label", attrs: { for: "name" } },
          [_vm._v(" indoor_description ")]
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "col-xs-8" },
          [
            _c("ckeditor", {
              attrs: { config: _vm.editorConfig },
              model: {
                value: _vm.locale_data.indoor_description,
                callback: function ($$v) {
                  _vm.$set(_vm.locale_data, "indoor_description", $$v)
                },
                expression: "locale_data.indoor_description",
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
          { staticClass: "col-xs-2 control-label", attrs: { for: "name" } },
          [_vm._v(" topo_description ")]
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "col-xs-8" },
          [
            _c("ckeditor", {
              attrs: { config: _vm.editorConfig },
              model: {
                value: _vm.locale_data.topo_description,
                callback: function ($$v) {
                  _vm.$set(_vm.locale_data, "topo_description", $$v)
                },
                expression: "locale_data.topo_description",
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
          { staticClass: "col-xs-2 control-label", attrs: { for: "name" } },
          [_vm._v(" what_we_do_description ")]
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "col-xs-8" },
          [
            _c("ckeditor", {
              attrs: { config: _vm.editorConfig },
              model: {
                value: _vm.locale_data.what_we_do_description,
                callback: function ($$v) {
                  _vm.$set(_vm.locale_data, "what_we_do_description", $$v)
                },
                expression: "locale_data.what_we_do_description",
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
          { staticClass: "col-xs-2 control-label", attrs: { for: "name" } },
          [_vm._v(" Shop short description ")]
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "col-xs-8" },
          [
            _c("ckeditor", {
              attrs: { config: _vm.editorConfig },
              model: {
                value: _vm.locale_data.shop_short_description,
                callback: function ($$v) {
                  _vm.$set(_vm.locale_data, "shop_short_description", $$v)
                },
                expression: "locale_data.shop_short_description",
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
          { staticClass: "col-xs-2 control-label", attrs: { for: "name" } },
          [_vm._v(" Shop description ")]
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "col-xs-8" },
          [
            _c("ckeditor", {
              attrs: { config: _vm.editorConfig },
              model: {
                value: _vm.locale_data.shop_description,
                callback: function ($$v) {
                  _vm.$set(_vm.locale_data, "shop_description", $$v)
                },
                expression: "locale_data.shop_description",
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
          { staticClass: "col-xs-2 control-label", attrs: { for: "name" } },
          [_vm._v(" Service description ")]
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "col-xs-8" },
          [
            _c("ckeditor", {
              attrs: { config: _vm.editorConfig },
              model: {
                value: _vm.locale_data.services_description,
                callback: function ($$v) {
                  _vm.$set(_vm.locale_data, "services_description", $$v)
                },
                expression: "locale_data.services_description",
              },
            }),
          ],
          1
        ),
      ]),
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ })

}]);