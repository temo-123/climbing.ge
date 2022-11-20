"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_favorites_FavoriteOutdoorAreaPageComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/favorites/FavoriteOutdoorAreaPageComponent.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/favorites/FavoriteOutdoorAreaPageComponent.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _items_BreadcrumbComponent_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../items/BreadcrumbComponent.vue */ "./resources/js/components/user/items/BreadcrumbComponent.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  components: {
    breadcrumb: _items_BreadcrumbComponent_vue__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  data: function data() {
    return {
      spots: [],
      MIX_SITE_URL: "climbing.loc",
      MIX_APP_SSH: "http://"
    };
  },
  mounted: function mounted() {
    this.get_faworite_outdoor_region();
  },
  methods: {
    get_faworite_outdoor_region: function get_faworite_outdoor_region() {
      var _this = this;

      axios.get('../api/outdoor/get_faworite_outdoor_region').then(function (response) {
        _this.spots = response.data;
      })["catch"](function (error) {});
    },
    go_to_spots_list: function go_to_spots_list(page) {
      window.open(this.MIX_APP_SSH + '' + this.MIX_SITE_URL + page);
    },
    del_from_faworites: function del_from_faworites(id) {
      var _this2 = this;

      if (confirm('Are you sure, you want delite this area from your faworites?')) {
        axios.post('../../api/outdoor/del_faworite_outdoor_region/' + id, {
          article_id: id,
          _method: 'DELETE'
        }).then(function (Response) {
          _this2.get_faworite_outdoor_region(); // alert("Area delited from your favorite list!");

        })["catch"](function (error) {
          alert("Error");
        });
      }
    }
  }
});

/***/ }),

/***/ "./resources/js/components/user/pages/favorites/FavoriteOutdoorAreaPageComponent.vue":
/*!*******************************************************************************************!*\
  !*** ./resources/js/components/user/pages/favorites/FavoriteOutdoorAreaPageComponent.vue ***!
  \*******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _FavoriteOutdoorAreaPageComponent_vue_vue_type_template_id_b3c73d66_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./FavoriteOutdoorAreaPageComponent.vue?vue&type=template&id=b3c73d66&scoped=true& */ "./resources/js/components/user/pages/favorites/FavoriteOutdoorAreaPageComponent.vue?vue&type=template&id=b3c73d66&scoped=true&");
/* harmony import */ var _FavoriteOutdoorAreaPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./FavoriteOutdoorAreaPageComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/favorites/FavoriteOutdoorAreaPageComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _FavoriteOutdoorAreaPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _FavoriteOutdoorAreaPageComponent_vue_vue_type_template_id_b3c73d66_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _FavoriteOutdoorAreaPageComponent_vue_vue_type_template_id_b3c73d66_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "b3c73d66",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/favorites/FavoriteOutdoorAreaPageComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/favorites/FavoriteOutdoorAreaPageComponent.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/favorites/FavoriteOutdoorAreaPageComponent.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_FavoriteOutdoorAreaPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./FavoriteOutdoorAreaPageComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/favorites/FavoriteOutdoorAreaPageComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_FavoriteOutdoorAreaPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/favorites/FavoriteOutdoorAreaPageComponent.vue?vue&type=template&id=b3c73d66&scoped=true&":
/*!**************************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/favorites/FavoriteOutdoorAreaPageComponent.vue?vue&type=template&id=b3c73d66&scoped=true& ***!
  \**************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_FavoriteOutdoorAreaPageComponent_vue_vue_type_template_id_b3c73d66_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_FavoriteOutdoorAreaPageComponent_vue_vue_type_template_id_b3c73d66_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_FavoriteOutdoorAreaPageComponent_vue_vue_type_template_id_b3c73d66_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./FavoriteOutdoorAreaPageComponent.vue?vue&type=template&id=b3c73d66&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/favorites/FavoriteOutdoorAreaPageComponent.vue?vue&type=template&id=b3c73d66&scoped=true&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/favorites/FavoriteOutdoorAreaPageComponent.vue?vue&type=template&id=b3c73d66&scoped=true&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/favorites/FavoriteOutdoorAreaPageComponent.vue?vue&type=template&id=b3c73d66&scoped=true& ***!
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
  return _c("div", { staticClass: "row" }, [
    _c("div", { staticClass: "col-sm-3" }, [_c("left-menu")], 1),
    _vm._v(" "),
    _c("div", { staticClass: "col-sm-9" }, [
      _c("div", { staticClass: "row" }, [
        _c("div", { staticClass: "col-md-12" }, [_c("breadcrumb")], 1),
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "row font-italic" }, [
        _vm._m(0),
        _vm._v(" "),
        _c("div", { staticClass: "col-md-6" }, [
          _c(
            "button",
            {
              staticClass: "btn btn-primary float-right",
              on: {
                click: function ($event) {
                  return _vm.go_to_spots_list("/outdoor")
                },
              },
            },
            [
              _vm._v(
                "\n                    Go to outdoor spot list\n                "
              ),
            ]
          ),
        ]),
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "row" }, [
        _c("div", { staticClass: "col-md-12" }, [
          _c(
            "button",
            {
              staticClass: "btn btn-success float-right",
              on: {
                click: function ($event) {
                  return _vm.get_faworite_outdoor_region()
                },
              },
            },
            [_vm._v("\n                    Refresh\n                ")]
          ),
        ]),
      ]),
      _vm._v(" "),
      _vm.spots.length
        ? _c(
            "div",
            { staticClass: "row" },
            _vm._l(_vm.spots, function (spot) {
              return _c("div", { key: spot.id, staticClass: "col-md-4" }, [
                _c("div", { staticClass: "card" }, [
                  _c(
                    "span",
                    {
                      on: {
                        click: function ($event) {
                          return _vm.go_to_spots_list(
                            "/outdoor/" + spot.url_title
                          )
                        },
                      },
                    },
                    [
                      spot.image != _vm.NULL
                        ? _c("site-img", {
                            attrs: {
                              src:
                                "../../../public/images/outdoor_img/" +
                                spot.image,
                              img_class: "card-img-top",
                              alt: spot[0][0].title,
                            },
                          })
                        : _c("site-img", {
                            attrs: {
                              src: "../../../public/images/site_img/image.png",
                              img_class: "card-img-top",
                              alt: spot[0][0].title,
                            },
                          }),
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c("div", { staticClass: "card-body" }, [
                    _c(
                      "a",
                      {
                        on: {
                          click: function ($event) {
                            return _vm.go_to_spots_list(
                              "/outdoor/" + spot.url_title
                            )
                          },
                        },
                      },
                      [
                        _c(
                          "h5",
                          { staticClass: "card-title border-bottom pb-3" },
                          [
                            _vm._v(
                              "\n                                " +
                                _vm._s(spot[0][0].title) +
                                "\n                            "
                            ),
                          ]
                        ),
                      ]
                    ),
                    _vm._v(" "),
                    _c(
                      "a",
                      {
                        staticClass: "btn btn-danger",
                        on: {
                          click: function ($event) {
                            return _vm.del_from_faworites(spot.id)
                          },
                        },
                      },
                      [
                        _vm._v("X "),
                        _c("i", { staticClass: "fas fa-angle-double-right" }),
                      ]
                    ),
                  ]),
                ]),
              ])
            }),
            0
          )
        : _c("div", { staticClass: "row" }, [
            _c("h2", [_vm._v("You dont have faworite climbing spots")]),
          ]),
    ]),
  ])
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "col-md-6" }, [
      _c("h3", { staticClass: "mt-3 pb-3 mb-4 " }, [
        _vm._v(
          "\n                    Faworite climbing spots\n                "
        ),
      ]),
    ])
  },
]
render._withStripped = true



/***/ })

}]);