"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_articles_forms_add_forms_GlobalDataFormComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/add_forms/GlobalDataFormComponent.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/add_forms/GlobalDataFormComponent.vue?vue&type=script&lang=js& ***!
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

 //https://innologica.github.io/vue-stackable-modal/#sample-css

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    StackModal: (_innologica_vue_stackable_modal__WEBPACK_IMPORTED_MODULE_1___default()),
    SlickItem: vue_slicksort__WEBPACK_IMPORTED_MODULE_0__.SlickItem,
    SlickList: vue_slicksort__WEBPACK_IMPORTED_MODULE_0__.SlickList
  },
  props: [// 'back_url',
    // 'category'
  ],
  data: function data() {
    return {
      category: this.$route.params.article_category,
      editorConfig: {// toolbar: [ [ 'Bold' ] ]
      },
      error: [],
      regions: [],
      mount_masive: [],
      data: {
        category: this.$route.params.article_category,
        us_title_for_url_title: "",
        published: 0,
        completed: "",
        map: "",
        weather: "",
        open_timen: "",
        closed_time: "",
        price_from: "",
        start_data: "",
        end_data: "",
        fb_link: "",
        twit_link: "",
        google_link: "",
        inst_link: "",
        web_link: "",
        region_id: "select_region",
        mount_id: "select_mount"
      }
    };
  },
  mounted: function mounted() {
    if (this.category == 'outdoor') {
      this.get_regions('outdoor');
    }

    if (this.category == 'mount_route') {
      this.get_mount_massive_data('mount_route');
    }

    this.$emit('global_form_data', this.data);
  },
  methods: {
    get_mount_massive_data: function get_mount_massive_data(category) {
      var _this = this;

      if (category == 'mount_route') {
        axios.get("../../../api/mountaineering/get_mount_data/").then(function (response) {
          _this.mount_masive = response.data;
        })["catch"](function (error) {
          return console.log(error);
        });
      }
    },
    get_regions: function get_regions(category) {
      var _this2 = this;

      if (category == 'outdoor') {
        axios.get("../../../api/region/").then(function (response) {
          _this2.regions = response.data;
        })["catch"](function (error) {
          return console.log(error);
        });
      }
    }
  }
});

/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/add_forms/GlobalDataFormComponent.vue":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/add_forms/GlobalDataFormComponent.vue ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _GlobalDataFormComponent_vue_vue_type_template_id_7ea86010___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./GlobalDataFormComponent.vue?vue&type=template&id=7ea86010& */ "./resources/js/components/user/pages/articles/forms/add_forms/GlobalDataFormComponent.vue?vue&type=template&id=7ea86010&");
/* harmony import */ var _GlobalDataFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./GlobalDataFormComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/articles/forms/add_forms/GlobalDataFormComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _GlobalDataFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _GlobalDataFormComponent_vue_vue_type_template_id_7ea86010___WEBPACK_IMPORTED_MODULE_0__.render,
  _GlobalDataFormComponent_vue_vue_type_template_id_7ea86010___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/articles/forms/add_forms/GlobalDataFormComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/add_forms/GlobalDataFormComponent.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/add_forms/GlobalDataFormComponent.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalDataFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./GlobalDataFormComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/add_forms/GlobalDataFormComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalDataFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/add_forms/GlobalDataFormComponent.vue?vue&type=template&id=7ea86010&":
/*!********************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/add_forms/GlobalDataFormComponent.vue?vue&type=template&id=7ea86010& ***!
  \********************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalDataFormComponent_vue_vue_type_template_id_7ea86010___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalDataFormComponent_vue_vue_type_template_id_7ea86010___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalDataFormComponent_vue_vue_type_template_id_7ea86010___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./GlobalDataFormComponent.vue?vue&type=template&id=7ea86010& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/add_forms/GlobalDataFormComponent.vue?vue&type=template&id=7ea86010&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/add_forms/GlobalDataFormComponent.vue?vue&type=template&id=7ea86010&":
/*!***********************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/add_forms/GlobalDataFormComponent.vue?vue&type=template&id=7ea86010& ***!
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
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-md-12" }, [
        _c("div", { staticClass: "jumbotron width_100" }, [
          _c("div", { staticClass: "container" }, [
            _c("h2", { staticClass: "display-4" }, [
              _c("span", { staticStyle: { "text-transform": "capitalize" } }, [
                _vm._v(_vm._s(this.category)),
              ]),
              _vm._v(" article global information"),
            ]),
            _vm._v(" "),
            _c("p", { staticClass: "lead" }, [
              _vm._v("Article global information."),
            ]),
          ]),
        ]),
        _vm._v(" "),
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
                  staticClass: "col-md-2 control-label ",
                  attrs: { for: "published" },
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
                _vm._v(" "),
                _vm.error.published
                  ? _c(
                      "div",
                      {
                        staticClass: "alert alert-danger",
                        attrs: { role: "alert" },
                      },
                      [
                        _vm._v(
                          "\n                            " +
                            _vm._s(_vm.error.published[0]) +
                            "\n                        "
                        ),
                      ]
                    )
                  : _vm._e(),
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
                          value: _vm.data.open_timen,
                          expression: "data.open_timen",
                        },
                      ],
                      staticClass: "form-control",
                      attrs: { type: "text", name: "open_timen" },
                      domProps: { value: _vm.data.open_timen },
                      on: {
                        input: function ($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.data, "open_timen", $event.target.value)
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
                          value: _vm.data.closed_time,
                          expression: "data.closed_time",
                        },
                      ],
                      staticClass: "form-control",
                      attrs: { type: "text", name: "closed_time" },
                      domProps: { value: _vm.data.closed_time },
                      on: {
                        input: function ($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.data, "closed_time", $event.target.value)
                        },
                      },
                    }),
                  ]),
                ])
              : _vm._e(),
            _vm._v(" "),
            this.category == "event" ? _c("hr") : _vm._e(),
            _vm._v(" "),
            this.category == "indoor"
              ? _c("div", { staticClass: "form-group clearfix row" }, [
                  _c(
                    "label",
                    {
                      staticClass: "col-md-2 control-label",
                      attrs: { for: "name" },
                    },
                    [_vm._v(" Start_data ")]
                  ),
                  _vm._v(" "),
                  _c("div", { staticClass: "col-md-10" }, [
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.data.start_data,
                          expression: "data.start_data",
                        },
                      ],
                      staticClass: "form-control",
                      attrs: { type: "text", name: "start_data" },
                      domProps: { value: _vm.data.start_data },
                      on: {
                        input: function ($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.data, "start_data", $event.target.value)
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
                    [_vm._v(" End data ")]
                  ),
                  _vm._v(" "),
                  _c("div", { staticClass: "col-md-10" }, [
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.data.end_data,
                          expression: "data.end_data",
                        },
                      ],
                      staticClass: "form-control",
                      attrs: { type: "text", name: "end_data" },
                      domProps: { value: _vm.data.end_data },
                      on: {
                        input: function ($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.data, "end_data", $event.target.value)
                        },
                      },
                    }),
                  ]),
                ])
              : _vm._e(),
            _vm._v(" "),
            this.category == "event" ||
            this.category == "partner" ||
            this.category == "indoor"
              ? _c("hr")
              : _vm._e(),
            _vm._v(" "),
            this.category == "event" ||
            this.category == "partner" ||
            this.category == "indoor"
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
            this.category == "event" ||
            this.category == "partner" ||
            this.category == "indoor"
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
            this.category == "event" ||
            this.category == "partner" ||
            this.category == "indoor"
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
var staticRenderFns = []
render._withStripped = true



/***/ })

}]);