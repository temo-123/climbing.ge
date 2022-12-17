"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_articles_forms_edit_forms_SectorImagesFormComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/SectorImagesFormComponent.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/edit_forms/SectorImagesFormComponent.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
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
  props: ['category'],
  components: {
    StackModal: (_innologica_vue_stackable_modal__WEBPACK_IMPORTED_MODULE_1___default()),
    SlickItem: vue_slicksort__WEBPACK_IMPORTED_MODULE_0__.SlickItem,
    SlickList: vue_slicksort__WEBPACK_IMPORTED_MODULE_0__.SlickList
  },
  data: function data() {
    return {
      myModal: false,
      // errors: [],
      image_errors: [],
      without_info: false,
      is_add_image: false,
      category: this.$route.params.article_category,
      image_is_refresh: false,
      image_reset_id: 0
    };
  },
  mounted: function mounted() {},
  methods: {
    add_image_modal: function add_image_modal() {
      this.is_add_image = true;
    },
    // add_image(){
    //     var myFormData = new FormData(this.$refs.myForm)
    //     axios({
    //         method: 'post',
    //         url: '/gallery/add/',
    //         data: myFormData,
    //         config: { 
    //             headers: {'Content-Type': 'multipart/form-data' },
    //         },
    //     })
    //     .then((response)=>  {
    //         this.is_add_image = false
    //         // this.get_gallery_data()
    //     });
    // },
    add_spot_rock_image: function add_spot_rock_image() {
      var myFormData = new FormData(this.$refs.myForm);
      axios({
        method: 'post',
        url: '../../api/articles/upload_image/',
        data: myFormData,
        config: {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }
      }).then(function (response) {});
    }
  }
});

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
  return _c(
    "div",
    { staticClass: "row" },
    [
      this.category == "outdoor"
        ? _c("div", { staticClass: "container" }, [
            _c(
              "label",
              {
                staticClass: "col-md-12 control-label",
                attrs: { for: "email" },
              },
              [_vm._v("Upload outdoor climbing area sector image:")]
            ),
            _vm._v(" "),
            _c("div", { staticClass: "container" }, [
              _vm.image_errors.image
                ? _c(
                    "div",
                    {
                      staticClass: "alert alert-danger",
                      attrs: { role: "alert" },
                    },
                    [
                      _vm._v(
                        "\n                " +
                          _vm._s(_vm.image_errors.image[0]) +
                          "\n            "
                      ),
                    ]
                  )
                : _vm._e(),
              _vm._v(" "),
              _c("div", { staticClass: "row" }, [
                _c("div", { staticClass: "form-groupe" }, [
                  _c(
                    "button",
                    {
                      staticClass: "btn btn-primary mb-4",
                      on: {
                        click: function ($event) {
                          return _vm.add_image_modal()
                        },
                      },
                    },
                    [_vm._v("Add image")]
                  ),
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "form-groupe" }, [
                  !_vm.image_is_refresh
                    ? _c(
                        "button",
                        {
                          staticClass: "btn main-btn float-right",
                          on: {
                            click: function ($event) {
                              return _vm.get_spost_sectors_image(
                                _vm.temporary_spost_sectors_id
                              )
                            },
                          },
                        },
                        [_vm._v("Refresh (" + _vm._s(_vm.image_reset_id) + ")")]
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.image_is_refresh
                    ? _c(
                        "span",
                        { staticClass: "badge badge-primare mb-1 float-right" },
                        [_vm._v("Updating...")]
                      )
                    : _vm._e(),
                ]),
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "row" }, [
                _c(
                  "button",
                  {
                    staticClass: "btn btn-secondary float-left",
                    on: {
                      click: function ($event) {
                        return _vm.save_spost_sectors_images_sequence()
                      },
                    },
                  },
                  [_vm._v("Save spost_sectors images sequence")]
                ),
              ]),
            ]),
            _vm._v(" "),
            this.category == "outdoor"
              ? _c(
                  "form",
                  {
                    ref: "sectorRegionImage",
                    on: { submit: _vm.upload_region_image },
                  },
                  [
                    _c("div", { staticClass: "form-group clearfix" }, [
                      _vm._m(0),
                      _vm._v(" "),
                      _c("div", { staticClass: "row" }, [
                        _c("div", { staticClass: "col-md-4" }, [
                          _c("img", {
                            attrs: {
                              src:
                                "/public/images/region_sectors_img/" +
                                this.region_sectors_image_name,
                              alt: "article image",
                            },
                          }),
                        ]),
                      ]),
                    ]),
                  ]
                )
              : _vm._e(),
          ])
        : _vm._e(),
      _vm._v(" "),
      _c(
        "stack-modal",
        {
          attrs: {
            show: _vm.is_add_image,
            title: "Add image",
            saveButton: {
              visible: true,
              title: "Save",
              btnClass: { "btn btn-primary": true },
            },
            cancelButton: {
              visible: false,
              title: "Close",
              btnClass: { "btn btn-danger": true },
            },
          },
          on: {
            close: function ($event) {
              _vm.is_add_image = false
            },
          },
        },
        [
          _c("pre", { staticClass: "language-vue" }, [
            _vm._v("            "),
            _c("form", { ref: "myForm" }, [
              _vm._v("\n                "),
              _c("div", { staticClass: "container" }, [
                _vm._v("\n                    "),
                _c("div", { staticClass: "form-group col-md-12" }, [
                  _vm._v("\n                        "),
                  _c("div", { staticClass: "row" }, [
                    _vm._v("\n                            "),
                    _c("label", { attrs: { for: "image" } }, [
                      _vm._v("Add Spot Image:"),
                    ]),
                    _vm._v("\n                        "),
                  ]),
                  _vm._v("\n                        "),
                  _c("div", { staticClass: "row" }, [
                    _vm._v("\n                            "),
                    _c("input", {
                      attrs: {
                        type: "file",
                        name: "image",
                        id: "image",
                        value: "image",
                      },
                    }),
                    _vm._v("\n                        "),
                  ]),
                  _vm._v("\n                    "),
                ]),
                _vm._v("\n                "),
              ]),
              _vm._v("\n            "),
            ]),
            _vm._v("\n\n            "),
            _vm._v("\n        "),
          ]),
          _vm._v(" "),
          _c("div", { attrs: { slot: "modal-footer" }, slot: "modal-footer" }, [
            _c("div", { staticClass: "modal-footer" }, [
              _c(
                "button",
                {
                  class: { "btn btn-primary": true },
                  attrs: { type: "button" },
                  on: {
                    click: function ($event) {
                      return _vm.add_spot_rock_image()
                    },
                  },
                },
                [_vm._v("\n                Save\n                ")]
              ),
            ]),
          ]),
        ]
      ),
    ],
    1
  )
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "row" }, [
      _c(
        "label",
        { staticClass: "col-md-5 control-label", attrs: { for: "email" } },
        [_vm._v("Update outdoor climbing area sector image:")]
      ),
      _vm._v(" "),
      _c("div", { staticClass: "col-md-4" }, [
        _c("input", {
          attrs: {
            type: "file",
            name: "region_sectors_img",
            id: "region_sectors_img",
          },
        }),
      ]),
    ])
  },
]
render._withStripped = true



/***/ })

}]);