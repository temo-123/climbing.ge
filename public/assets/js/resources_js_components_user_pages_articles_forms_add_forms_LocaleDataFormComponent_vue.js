"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_articles_forms_add_forms_LocaleDataFormComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  props: [// 'info_block_prop',
  // 'routes_info_prop',
  // 'what_need_info_prop',
  // 'best_time_prop',
  'global_blocks_prop', 'title', 'description'],
  data: function data() {
    return {
      // category: 'this.$route.params.article_category',
      category: this.$route.params.article_category,
      general_infos: [],
      errors: [],
      error: [],
      editorConfig: {
        // toolbar: [ [ 'Bold' ] ]
        filebrowserUploadUrl: "{{route('upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form',
        removeButtons: 'Link,Unlink,Anchor'
      },
      // CKEDITOR.replace( 'description', {
      //     filebrowserUploadUrl: "{{route('upload', ['_token' => csrf_token() ])}}",
      //     filebrowserUploadMethod: 'form'
      // });
      data: {
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
    this.global_blocks = this.global_blocks_prop; // this.routes_info = this.routes_info_prop
    // this.what_need_info = this.what_need_info_prop
    // this.best_time = this.best_time_prop

    this.get_general_info();
    this.$emit('locale_form_data', this.data);
  },
  watch: {
    global_block: function global_block() {
      this.global_blocks = this.global_blocks_prop;
    } // routes_info_prop: function(){
    //     this.routes_info = this.routes_info_prop
    // },
    // what_need_info_prop: function(){
    //     this.what_need_info_prop = this.what_need_info_prop
    // },
    // best_time_prop: function(){
    //     this.best_time = this.best_time_prop
    // }

  },
  methods: {
    uploader: function uploader(editor) {
      editor.plugins.get('FileRepository').createUploadAdapter = function (loader) {
        return new UploadAdapter(loader);
      };
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

/***/ "./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _LocaleDataFormComponent_vue_vue_type_template_id_1204048e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./LocaleDataFormComponent.vue?vue&type=template&id=1204048e& */ "./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=template&id=1204048e&");
/* harmony import */ var _LocaleDataFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./LocaleDataFormComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _LocaleDataFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _LocaleDataFormComponent_vue_vue_type_template_id_1204048e___WEBPACK_IMPORTED_MODULE_0__.render,
  _LocaleDataFormComponent_vue_vue_type_template_id_1204048e___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LocaleDataFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./LocaleDataFormComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LocaleDataFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=template&id=1204048e&":
/*!********************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=template&id=1204048e& ***!
  \********************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LocaleDataFormComponent_vue_vue_type_template_id_1204048e___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LocaleDataFormComponent_vue_vue_type_template_id_1204048e___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LocaleDataFormComponent_vue_vue_type_template_id_1204048e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./LocaleDataFormComponent.vue?vue&type=template&id=1204048e& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=template&id=1204048e&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=template&id=1204048e&":
/*!***********************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=template&id=1204048e& ***!
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
  return _c("div", { staticClass: "col-md-12" }, [
    _c("div", { staticClass: "col-md-12" }, [
      _c("div", { staticClass: "jumbotron width_100" }, [
        _c("div", { staticClass: "container" }, [
          _c("h2", { staticClass: "display-4" }, [
            _c("span", { staticStyle: { "text-transform": "capitalize" } }, [
              _vm._v(_vm._s(this.category)),
            ]),
            _vm._v(" " + _vm._s(this.title)),
          ]),
          _vm._v(" "),
          _c("p", { staticClass: "lead" }, [_vm._v(_vm._s(this.description))]),
        ]),
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "wrapper container-fluid container" }, [
        _c(
          "form",
          {
            attrs: { method: "POST" },
            on: {
              submit: function ($event) {
                $event.preventDefault()
                return _vm.add_article.apply(null, arguments)
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
                _vm._v(" "),
                _vm.errors.title
                  ? _c(
                      "div",
                      {
                        staticClass: "alert alert-danger",
                        attrs: { role: "alert" },
                      },
                      [
                        _vm._v(
                          "\n                            " +
                            _vm._s(_vm.errors.title[0]) +
                            "\n                        "
                        ),
                      ]
                    )
                  : _vm._e(),
              ]),
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "form-group clearfix row" }, [
              _c(
                "label",
                {
                  staticClass: "col-md-2 control-label",
                  attrs: { for: "name" },
                },
                [_vm._v(" Short description ")]
              ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "col-md-10" },
                [
                  _c("ckeditor", {
                    attrs: { config: this.$shortDescriptionEditorConfig },
                    model: {
                      value: _vm.data.short_description,
                      callback: function ($$v) {
                        _vm.$set(_vm.data, "short_description", $$v)
                      },
                      expression: "data.short_description",
                    },
                  }),
                  _vm._v(" "),
                  _vm.errors.short_description
                    ? _c(
                        "div",
                        {
                          staticClass: "alert alert-danger",
                          attrs: { role: "alert" },
                        },
                        [
                          _vm._v(
                            "\n                            " +
                              _vm._s(_vm.errors.short_description[0]) +
                              "\n                        "
                          ),
                        ]
                      )
                    : _vm._e(),
                ],
                1
              ),
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "form-group clearfix row" }, [
              _c(
                "label",
                {
                  staticClass: "col-md-2 control-label",
                  attrs: { for: "name" },
                },
                [_vm._v(" text ")]
              ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "col-md-10" },
                [
                  _c("ckeditor", {
                    attrs: { config: this.$editorConfig },
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
                                      var val =
                                        "_value" in o ? o._value : o.value
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
                              attrs: { config: this.$editorConfig },
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
                                      var val =
                                        "_value" in o ? o._value : o.value
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
                        attrs: { config: this.$editorConfig },
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
                                      var val =
                                        "_value" in o ? o._value : o.value
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
                              attrs: { config: this.$editorConfig },
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
                                      var val =
                                        "_value" in o ? o._value : o.value
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
                                      var val =
                                        "_value" in o ? o._value : o.value
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
                              attrs: { config: this.$editorConfig },
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
                                      var val =
                                        "_value" in o ? o._value : o.value
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
                      _c("label", { attrs: { for: "befor" } }, [
                        _vm._v("Befor"),
                      ]),
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
                      _c("label", { attrs: { for: "after" } }, [
                        _vm._v("After"),
                      ]),
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
                {
                  staticClass: "col-md-2 control-label",
                  attrs: { for: "name" },
                },
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
                          attrs: { config: this.$editorConfig },
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
                        attrs: { config: this.$editorConfig },
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
          ]
        ),
      ]),
    ]),
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ })

}]);