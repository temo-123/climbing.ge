"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_HomePageComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/HomePageComponent.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/HomePageComponent.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
 //https://github.com/Jexordexan/vue-slicksort

 //https://innologica.github.io/vue-stackable-modal/#sample-css

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    StackModal: (_innologica_vue_stackable_modal__WEBPACK_IMPORTED_MODULE_1___default()),
    SlickItem: vue_slicksort__WEBPACK_IMPORTED_MODULE_0__.SlickItem,
    SlickList: vue_slicksort__WEBPACK_IMPORTED_MODULE_0__.SlickList
  },
  data: function data() {
    return {
      user: null,
      MIX_SITE_URL: "climbing.loc",
      MIX_APP_SSH: "http://",
      complaint_loader: false,
      complaints: [],
      quick_comment: [],
      comment_decision: 'select_decision',
      selected_comment_complaint: 'This is my comment',
      is_comment_decision_selected: false,
      is_coment_complaint_model: false,
      action_comment_id: 0,
      is_admin_panel_refresh: false,
      admin_refresh_id: 0,
      decision_loader: false,
      user_queries: [],
      is_coment_model: false,
      is_user_comment_complaint_model: false,
      complaint_comment_id: 0,
      complaint_query_id: 0
    };
  },
  mounted: function mounted() {
    this.refresh();
  },
  methods: {
    refresh: function refresh() {
      this.admin_refresh_id++;
      this.get_user_data();
      this.get_comments_complaints();
    },
    get_user_data: function get_user_data() {
      var _this = this;

      axios.get('/api/auth_user').then(function (response) {
        _this.user = response.data;

        _this.get_user_queries(_this.user.id);
      });
    },
    get_user_queries: function get_user_queries(user_id) {
      var _this2 = this;

      axios.get('../api/get_user_queries/' + user_id).then(function (response) {
        _this2.user_queries = response.data;
      });
    },
    make_complaint: function make_complaint() {
      var _this3 = this;

      this.complaint_loader = true;
      axios.post('../api/add_comment_complaint/', {
        comment_id: this.complaint_comment_id,
        comment_complaint: this.selected_comment_complaint,
        email: this.complainter_email
      }).then(function (response) {
        _this3.is_user_comment_complaint_model = false;
        _this3.selected_comment_complaint = 'Hostile remarks';

        _this3.refresh();
      })["catch"]()["finally"](function () {
        return _this3.complaint_loader = false;
      });
    },
    query_response: function query_response(response, query_id, comment_id) {
      var _this4 = this;

      axios.post('../api/query_response/', {
        query_id: query_id,
        comment_id: comment_id,
        response: response
      }).then(function (response) {
        _this4.refresh;

        if (_this4.is_coment_model == true) {
          _this4.is_coment_model = false;
        }
      });
    },
    show_complaint_modal: function show_complaint_modal(comment_id) {
      this.is_coment_model = false;
      this.complaint_comment_id = comment_id;
      this.is_user_comment_complaint_model = true;
    },
    coment_model: function coment_model(comment_id, query_id) {
      this.get_action_comment(comment_id);
      this.complaint_query_id = query_id;
      this.is_coment_model = true;
    },
    quick_wiev_action: function quick_wiev_action(comment_id, complaint_id) {
      this.is_coment_complaint_model = true;
      this.get_action_comment(comment_id);
      this.action_comment_id = comment_id;
      this.complaint_id = complaint_id;
    },
    get_action_comment: function get_action_comment(comment_id) {
      var _this5 = this;

      this.quick_comment = [];
      axios.get("../api/get_quick_comment/" + comment_id).then(function (response) {
        _this5.quick_comment = response.data;
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    get_comments_complaints: function get_comments_complaints() {
      var _this6 = this;

      axios.get('./api/get_comments_complaints').then(function (response) {
        _this6.complaints = response.data;
      });
    },
    make_decision: function make_decision() {
      var _this7 = this;

      if (this.comment_decision == 'select_decision') {
        this.is_comment_decision_selected = true;
      } else {
        this.decision_loader = true;
        axios.post('./api/make_decision', {
          decision: this.comment_decision,
          comment_id: this.action_comment_id,
          complaint_id: this.complaint_id
        }).then(function (response) {
          _this7.is_coment_complaint_model = false;

          _this7.refresh();
        })["finally"](function () {
          return _this7.decision_loader = false;
        });
      }
    },
    go_to_service: function go_to_service(service) {
      if (service == "shop") {
        window.open(this.MIX_APP_SSH + 'shop.' + this.MIX_SITE_URL + '');
      } else if (service == "guid") {
        window.open(this.MIX_APP_SSH + this.MIX_SITE_URL + '/');
      } else if (service == "films") {
        window.open(this.MIX_APP_SSH + 'films.' + this.MIX_SITE_URL + '/');
      }
    }
  }
});

/***/ }),

/***/ "./resources/js/components/user/pages/HomePageComponent.vue":
/*!******************************************************************!*\
  !*** ./resources/js/components/user/pages/HomePageComponent.vue ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _HomePageComponent_vue_vue_type_template_id_07287b2a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./HomePageComponent.vue?vue&type=template&id=07287b2a& */ "./resources/js/components/user/pages/HomePageComponent.vue?vue&type=template&id=07287b2a&");
/* harmony import */ var _HomePageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./HomePageComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/HomePageComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _HomePageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _HomePageComponent_vue_vue_type_template_id_07287b2a___WEBPACK_IMPORTED_MODULE_0__.render,
  _HomePageComponent_vue_vue_type_template_id_07287b2a___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/HomePageComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/HomePageComponent.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/components/user/pages/HomePageComponent.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_HomePageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./HomePageComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/HomePageComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_HomePageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/HomePageComponent.vue?vue&type=template&id=07287b2a&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/HomePageComponent.vue?vue&type=template&id=07287b2a& ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_HomePageComponent_vue_vue_type_template_id_07287b2a___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_HomePageComponent_vue_vue_type_template_id_07287b2a___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_HomePageComponent_vue_vue_type_template_id_07287b2a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./HomePageComponent.vue?vue&type=template&id=07287b2a& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/HomePageComponent.vue?vue&type=template&id=07287b2a&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/HomePageComponent.vue?vue&type=template&id=07287b2a&":
/*!****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/HomePageComponent.vue?vue&type=template&id=07287b2a& ***!
  \****************************************************************************************************************************************************************************************************************************************/
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
      _c("div", { staticClass: "col-sm-3" }, [_c("left-menu")], 1),
      _vm._v(" "),
      _c("div", { staticClass: "col-sm-9" }, [
        _c("div", { staticClass: "row justify-content-center" }, [
          _c("div", { staticClass: "col-md-12" }, [
            _c(
              "div",
              { staticClass: "alert alert-success", attrs: { role: "alert" } },
              [_c("h1", [_vm._v("Hi " + _vm._s(this.user["name"]))])]
            ),
          ]),
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "row justify-content-center" }, [
          _c("div", { staticClass: "col-md-12" }, [
            _c("div", { staticClass: "card" }, [
              _c("div", { staticClass: "card-header" }, [
                _vm._v("Sites index pages"),
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "card-body" }, [
                _c("div", { staticClass: "row" }, [
                  _c("div", { staticClass: "col-sm-12 col-md-4" }, [
                    _c(
                      "button",
                      {
                        staticClass: "btn btn-succcess",
                        on: {
                          click: function ($event) {
                            return _vm.go_to_service("guid")
                          },
                        },
                      },
                      [_vm._v("Climbing Guidbook")]
                    ),
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "col-sm-12 col-md-4" }, [
                    _c(
                      "button",
                      {
                        staticClass: "btn btn-succcess",
                        on: {
                          click: function ($event) {
                            return _vm.go_to_service("shop")
                          },
                        },
                      },
                      [_vm._v("Climbing Shop")]
                    ),
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "col-sm-12 col-md-4" }, [
                    _c(
                      "button",
                      {
                        staticClass: "btn btn-succcess",
                        on: {
                          click: function ($event) {
                            return _vm.go_to_service("films")
                          },
                        },
                      },
                      [_vm._v("Climbing Films")]
                    ),
                  ]),
                ]),
              ]),
            ]),
          ]),
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "row" }, [
          _c("div", { staticClass: "col-md-12" }, [
            _c("div", { staticClass: "form-groupe" }, [
              !_vm.is_admin_panel_refresh
                ? _c(
                    "button",
                    {
                      staticClass: "btn btn-success float-right",
                      on: {
                        click: function ($event) {
                          return _vm.refresh()
                        },
                      },
                    },
                    [
                      _vm._v(
                        "Refresh notifications (" +
                          _vm._s(_vm.admin_refresh_id) +
                          ")"
                      ),
                    ]
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.is_admin_panel_refresh
                ? _c(
                    "span",
                    { staticClass: "badge badge-primare mb-1 float-right" },
                    [_vm._v("Updating...")]
                  )
                : _vm._e(),
            ]),
          ]),
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "row" }, [
          _c(
            "div",
            { staticClass: "col-md-12" },
            [
              !this.user["name"] ||
              !this.user["surname"] ||
              !this.user["country"] ||
              !this.user["city"] ||
              !this.user["email"]
                ? _c("span", [_vm._m(0)])
                : _vm._e(),
              _vm._v(" "),
              !this.user["email_verified_at"]
                ? _c("span", [_vm._m(1)])
                : _vm._e(),
              _vm._v(" "),
              !this.user["image"] ? _c("span", [_vm._m(2)]) : _vm._e(),
              _vm._v(" "),
              _vm._l(_vm.complaints, function (complaint) {
                return _c("span", { key: complaint.id }, [
                  _c(
                    "div",
                    {
                      staticClass: "alert alert-warning",
                      attrs: { role: "alert" },
                    },
                    [
                      _c("strong", [_vm._v("Warning!")]),
                      _vm._v(
                        " one comment are a complainted. Please check it and make decision!\n\n                        "
                      ),
                      _c("div", { staticClass: "row" }, [
                        _c("div", { staticClass: "col-md-6" }, [
                          _c(
                            "button",
                            {
                              staticClass: "btn btn-primary ",
                              on: {
                                click: function ($event) {
                                  return _vm.quick_wiev_action(
                                    complaint.comment_id,
                                    complaint.id
                                  )
                                },
                              },
                            },
                            [_vm._v("Check comment")]
                          ),
                          _vm._v(".\n                            "),
                        ]),
                      ]),
                    ]
                  ),
                ])
              }),
              _vm._v(" "),
              _vm._l(_vm.user_queries, function (query) {
                return _c("span", { key: query.id }, [
                  _c(
                    "div",
                    {
                      staticClass: "alert alert-warning",
                      attrs: { role: "alert" },
                    },
                    [
                      _c("strong", [_vm._v("Warning!")]),
                      _vm._v(
                        " We fined yor email in one of the article comment. Maybe it's your comment. Plees check it end click to ansver. Is it your comment?\n                        \n                        "
                      ),
                      _c("div", { staticClass: "row" }, [
                        _c("div", { staticClass: "col-md-6" }, [
                          _c(
                            "button",
                            {
                              staticClass: "btn btn-success ",
                              on: {
                                click: function ($event) {
                                  return _vm.coment_model(
                                    query.comment_id,
                                    query.id
                                  )
                                },
                              },
                            },
                            [_vm._v("Check comment!")]
                          ),
                          _vm._v(".\n                            "),
                        ]),
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "row" }, [
                        _c("div", { staticClass: "col-md-6" }, [
                          _c(
                            "button",
                            {
                              staticClass: "btn btn-primary",
                              on: {
                                click: function ($event) {
                                  return _vm.query_response(
                                    true,
                                    query.id,
                                    query.comment_id
                                  )
                                },
                              },
                            },
                            [_vm._v("Yes, it is my comment!")]
                          ),
                          _vm._v(".\n                            "),
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "col-md-6" }, [
                          _c(
                            "button",
                            {
                              staticClass: "btn btn-danger float-right",
                              on: {
                                click: function ($event) {
                                  return _vm.query_response(
                                    false,
                                    query.id,
                                    query.comment_id
                                  )
                                },
                              },
                            },
                            [_vm._v("No, it is not my comment!")]
                          ),
                          _vm._v(".\n                            "),
                        ]),
                      ]),
                    ]
                  ),
                ])
              }),
            ],
            2
          ),
        ]),
      ]),
      _vm._v(" "),
      _c(
        "stack-modal",
        {
          attrs: {
            show: _vm.is_coment_complaint_model,
            title: "Show coment",
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
              _vm.is_coment_complaint_model = false
            },
          },
        },
        [
          _c("pre", { staticClass: "language-vue" }, [
            _vm._v("            "),
            _vm.decision_loader
              ? _c("div", { staticClass: "row justify-content-center" }, [
                  _vm._v("\n                "),
                  _c("div", { staticClass: "col-md-4" }, [
                    _vm._v("\n                    "),
                    _c("img", {
                      attrs: {
                        src: "../public/images/site_img/loading.gif",
                        alt: "loading",
                      },
                    }),
                    _vm._v("\n                "),
                  ]),
                  _vm._v("\n            "),
                ])
              : _vm._e(),
            _vm._v("\n\n            "),
            !_vm.decision_loader
              ? _c("span", [
                  _vm._v("\n                "),
                  _c("h1", [_vm._v("Show Comment")]),
                  _vm._v(
                    "\n\n                Comentator - " +
                      _vm._s(_vm.quick_comment.name) +
                      " " +
                      _vm._s(_vm.quick_comment.suenmae) +
                      "\n                Email - " +
                      _vm._s(_vm.quick_comment.email) +
                      "\n                Data to comment - " +
                      _vm._s(_vm.quick_comment.created_at) +
                      "\n\n                " +
                      _vm._s(_vm.quick_comment.text) +
                      "\n\n                "
                  ),
                  _c("h1", [_vm._v("Comment complaint")]),
                  _vm._v(
                    "\n                Email - " +
                      _vm._s(_vm.quick_comment.email) +
                      "\n                Complaint status - " +
                      _vm._s(_vm.quick_comment.complaint) +
                      "\n\n                "
                  ),
                  _c("h1", [_vm._v("Make decision")]),
                  _vm._v("\n                \n                "),
                  _c(
                    "form",
                    {
                      staticClass: "form",
                      attrs: { id: "make_decision" },
                      on: {
                        submit: function ($event) {
                          $event.preventDefault()
                          return _vm.make_decision.apply(null, arguments)
                        },
                      },
                    },
                    [
                      _vm._v("\n                    "),
                      _c(
                        "select",
                        {
                          directives: [
                            {
                              name: "model",
                              rawName: "v-model",
                              value: _vm.comment_decision,
                              expression: "comment_decision",
                            },
                          ],
                          staticClass: "form-control",
                          attrs: { name: "comment delete cause" },
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
                              _vm.comment_decision = $event.target.multiple
                                ? $$selectedVal
                                : $$selectedVal[0]
                            },
                          },
                        },
                        [
                          _vm._v(" \n                        "),
                          _c(
                            "option",
                            {
                              attrs: { value: "select_decision", disabled: "" },
                            },
                            [_vm._v("Select decision")]
                          ),
                          _vm._v("\n                        "),
                          _c(
                            "option",
                            { attrs: { value: "approve_request" } },
                            [_vm._v("Approve the request")]
                          ),
                          _vm._v("\n                        "),
                          _c("option", { attrs: { value: "reject_request" } }, [
                            _vm._v("Reject the request"),
                          ]),
                          _vm._v("\n                    "),
                        ]
                      ),
                      _vm._v(" \n                    "),
                      _vm.is_comment_decision_selected
                        ? _c(
                            "div",
                            {
                              staticClass: "alert alert-danger",
                              attrs: { role: "alert" },
                            },
                            [_vm._v("Please select decision!!!")]
                          )
                        : _vm._e(),
                      _vm._v("\n                "),
                    ]
                  ),
                  _vm._v("\n            "),
                ])
              : _vm._e(),
            _vm._v("\n        "),
          ]),
          _vm._v(" "),
          _c("div", { attrs: { slot: "modal-footer" }, slot: "modal-footer" }, [
            _c("div", { staticClass: "modal-footer" }, [
              _c(
                "button",
                {
                  class: { "btn btn-primary": true },
                  attrs: { type: "submit", form: "make_decision" },
                },
                [_vm._v("\n                Make a decision\n                ")]
              ),
            ]),
          ]),
        ]
      ),
      _vm._v(" "),
      _c(
        "stack-modal",
        {
          attrs: {
            show: _vm.is_user_comment_complaint_model,
            title: "Please select a reason for deleting the comment",
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
              _vm.is_user_comment_complaint_model = false
            },
          },
        },
        [
          _c("pre", { staticClass: "language-vue" }, [
            _vm._v("            "),
            _vm.complaint_loader
              ? _c("div", { staticClass: "row justify-content-center" }, [
                  _vm._v("\n                "),
                  _c("div", { staticClass: "col-md-4" }, [
                    _vm._v("\n                    "),
                    _c("img", {
                      attrs: {
                        src: "../public/images/site_img/loading.gif",
                        alt: "loading",
                      },
                    }),
                    _vm._v("\n                "),
                  ]),
                  _vm._v("\n            "),
                ])
              : _vm._e(),
            _vm._v("\n\n            "),
            !_vm.complaint_loader
              ? _c("span", [
                  _vm._v("\n                "),
                  _c("h1", [
                    _vm._v("You can file a complaint for this comment"),
                  ]),
                  _vm._v("\n                "),
                  _c("p", [
                    _vm._v(
                      "Please select a reason for deleting the comment!!!"
                    ),
                  ]),
                  _vm._v("\n                \n                "),
                  _c(
                    "form",
                    {
                      staticClass: "form",
                      attrs: { id: "make_complaint" },
                      on: {
                        submit: function ($event) {
                          $event.preventDefault()
                          return _vm.make_complaint.apply(null, arguments)
                        },
                      },
                    },
                    [
                      _vm._v("\n                    "),
                      _vm.user.length == 0
                        ? _c("input", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.complainter_email,
                                expression: "complainter_email",
                              },
                            ],
                            staticClass: "form-control textarea",
                            attrs: {
                              type: "email",
                              name: "complainter email",
                              placeholder: "Your email",
                            },
                            domProps: { value: _vm.complainter_email },
                            on: {
                              input: function ($event) {
                                if ($event.target.composing) {
                                  return
                                }
                                _vm.complainter_email = $event.target.value
                              },
                            },
                          })
                        : _vm._e(),
                      _vm._v("\n\n                    "),
                      _c(
                        "select",
                        {
                          directives: [
                            {
                              name: "model",
                              rawName: "v-model",
                              value: _vm.selected_comment_complaint,
                              expression: "selected_comment_complaint",
                            },
                          ],
                          staticClass: "form-control",
                          attrs: { name: "comment delete cause" },
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
                              _vm.selected_comment_complaint = $event.target
                                .multiple
                                ? $$selectedVal
                                : $$selectedVal[0]
                            },
                          },
                        },
                        [
                          _vm._v(" \n                        "),
                          _c(
                            "option",
                            { attrs: { value: "This is my comment" } },
                            [_vm._v("This is my comment")]
                          ),
                          _vm._v("\n                        "),
                          _c(
                            "option",
                            { attrs: { value: "Hostile remarks" } },
                            [_vm._v("Hostile remarks")]
                          ),
                          _vm._v("\n                        "),
                          _c(
                            "option",
                            {
                              attrs: {
                                value: "Does not match the theme of the site",
                              },
                            },
                            [_vm._v("Does not match the theme of the site")]
                          ),
                          _vm._v("\n                        "),
                          _c("option", { attrs: { value: "Spam" } }, [
                            _vm._v("Spam"),
                          ]),
                          _vm._v("\n                        "),
                          _c("option", { attrs: { value: "Sexual content" } }, [
                            _vm._v("Sexual content"),
                          ]),
                          _vm._v("\n                        "),
                          _c(
                            "option",
                            { attrs: { value: "Expression of anger" } },
                            [_vm._v("Expression of anger")]
                          ),
                          _vm._v("\n                        "),
                          _c(
                            "option",
                            {
                              attrs: {
                                value:
                                  "Conflict with other members of the site",
                              },
                            },
                            [_vm._v("Conflict with other members of the site")]
                          ),
                          _vm._v("\n                        "),
                          _c(
                            "option",
                            {
                              attrs: {
                                value:
                                  "The language of the comments does not match the requirements of the site",
                              },
                            },
                            [
                              _vm._v(
                                "The language of the comments does not match the requirements of the site"
                              ),
                            ]
                          ),
                          _vm._v("\n                    "),
                        ]
                      ),
                      _vm._v("\n                "),
                    ]
                  ),
                  _vm._v("\n            "),
                ])
              : _vm._e(),
            _vm._v("\n        "),
          ]),
          _vm._v(" "),
          _c("div", { attrs: { slot: "modal-footer" }, slot: "modal-footer" }, [
            _c("div", { staticClass: "modal-footer" }, [
              _c(
                "button",
                {
                  class: { "btn btn-primary": true },
                  attrs: { type: "submit", form: "make_complaint" },
                },
                [_vm._v("\n                Submit\n                ")]
              ),
            ]),
          ]),
        ]
      ),
      _vm._v(" "),
      _c(
        "stack-modal",
        {
          attrs: {
            show: _vm.is_coment_model,
            title: "Show coment",
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
              _vm.is_coment_model = false
            },
          },
        },
        [
          _c("pre", { staticClass: "language-vue" }, [
            _vm._v("            "),
            _vm.decision_loader
              ? _c("div", { staticClass: "row justify-content-center" }, [
                  _vm._v("\n                "),
                  _c("div", { staticClass: "col-md-4" }, [
                    _vm._v("\n                    "),
                    _c("img", {
                      attrs: {
                        src: "../public/images/site_img/loading.gif",
                        alt: "loading",
                      },
                    }),
                    _vm._v("\n                "),
                  ]),
                  _vm._v("\n            "),
                ])
              : _vm._e(),
            _vm._v("\n\n            "),
            !_vm.decision_loader
              ? _c("span", [
                  _vm._v("\n                "),
                  _c("h1", [_vm._v("Show Comment")]),
                  _vm._v(
                    "\n\n                Comentator - " +
                      _vm._s(_vm.quick_comment.name) +
                      " " +
                      _vm._s(_vm.quick_comment.suenmae) +
                      "\n                Email - " +
                      _vm._s(_vm.quick_comment.email) +
                      "\n                Data to comment - " +
                      _vm._s(_vm.quick_comment.created_at) +
                      "\n\n                " +
                      _vm._s(_vm.quick_comment.text) +
                      "\n\n                "
                  ),
                  _c(
                    "button",
                    {
                      staticClass: "btn btn-primary",
                      on: {
                        click: function ($event) {
                          return _vm.query_response(
                            true,
                            _vm.complaint_query_id,
                            _vm.quick_comment.id
                          )
                        },
                      },
                    },
                    [_vm._v("Yes, it is my comment!")]
                  ),
                  _vm._v("\n                "),
                  _c(
                    "button",
                    {
                      staticClass: "btn btn-danger",
                      on: {
                        click: function ($event) {
                          return _vm.query_response(
                            false,
                            _vm.complaint_query_id,
                            _vm.quick_comment.id
                          )
                        },
                      },
                    },
                    [_vm._v("No, it is not my comment!")]
                  ),
                  _vm._v("\n\n            "),
                ])
              : _vm._e(),
            _vm._v("\n        "),
          ]),
          _vm._v(" "),
          _c("div", { attrs: { slot: "modal-footer" }, slot: "modal-footer" }, [
            _c("div", { staticClass: "modal-footer" }, [
              _c(
                "button",
                {
                  class: { "btn btn-primary": true },
                  attrs: { type: "submit" },
                  on: {
                    click: function ($event) {
                      return _vm.show_complaint_modal(_vm.quick_comment.id)
                    },
                  },
                },
                [
                  _vm._v(
                    "\n                Make file a complaint\n                "
                  ),
                ]
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
    return _c(
      "div",
      { staticClass: "alert alert-danger", attrs: { role: "alert" } },
      [
        _c("strong", [_vm._v("Danger!")]),
        _vm._v(
          ' Your personal data is missing, this can lead to rolling problems. check page "option", and add missing information.\n                    '
        ),
      ]
    )
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "div",
      { staticClass: "alert alert-danger", attrs: { role: "alert" } },
      [
        _c("strong", [_vm._v("Danger!")]),
        _vm._v(
          " We sent you an email for verification, please check your email and confirm it.\n                    "
        ),
      ]
    )
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "div",
      { staticClass: "alert alert-warning", attrs: { role: "alert" } },
      [
        _c("strong", [_vm._v("Warning!")]),
        _vm._v(
          " Add your profil image and piple faind you isier\n                    "
        ),
      ]
    )
  },
]
render._withStripped = true



/***/ })

}]);