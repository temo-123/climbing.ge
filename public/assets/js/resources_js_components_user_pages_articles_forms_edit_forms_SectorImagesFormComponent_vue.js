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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
      sector_old_images: []
    };
  },
  mounted: function mounted() {
    this.get_old_sector_images();
  },
  methods: {
    get_old_sector_images: function get_old_sector_images() {
      var _this = this;

      axios.get("../../../api/spot_rock_images/get_spot_rock_images/" + this.$route.params.id).then(function (response) {
        _this.mount_route_old_images = response.data.mount_route_images;
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    del_old_sector_image_from_db: function del_old_sector_image_from_db(image_id) {
      var _this2 = this;

      if (confirm('Are you sure, you want delite this image?')) {
        axios["delete"]("../../../api/spot_rock_images/del_spot_rock_images/" + image_id).then(function (response) {
          _this2.get_old_mount_routes_images();
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
      this.$emit("upload_img", this.sector_images);
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
                  _vm._l(_vm.sector_old_images, function (image) {
                    return _c("tr", { key: image.id }, [
                      _c("td", [
                        _c("img", {
                          staticClass: "img-responsive",
                          attrs: {
                            src:
                              "../../../../images/spot_rock_img/" + image.image,
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