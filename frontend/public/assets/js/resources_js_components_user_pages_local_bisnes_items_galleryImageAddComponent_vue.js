"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_local_bisnes_items_galleryImageAddComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/local_bisnes/items/galleryImageAddComponent.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/local_bisnes/items/galleryImageAddComponent.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
// import { editor_config } from '../../../../mixins/editor/editor_config_mixin.js'
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  mixins: [
    // editor_config
  ],
  props: [
  // 'old_images_prop',
  'image_path_prop'],
  data: function data() {
    return {
      new_images: [],
      old_images: []
    };
  },
  mounted: function mounted() {
    // this.old_images = old_images_prop
  },
  methods: {
    onFileChange: function onFileChange(event, item_id) {
      var image = event.target.files[0];
      var id = item_id - 1;
      this.new_images[id]['image'] = image;
      this.update_perent_component_data();
    },
    add_new_image_value: function add_new_image_value() {
      var new_item_id = this.new_images.length + 1;
      this.new_images.push({
        id: new_item_id,
        image: ''
      });
    },
    del_image: function del_image(id) {
      var objWithIdIndex = this.new_images.findIndex(function (obj) {
        return obj.id === id;
      });
      this.new_images.splice(objWithIdIndex, 1);
      this.update_perent_component_data();
    },
    update_perent_component_data: function update_perent_component_data() {
      this.$emit("update_gallery_images", this.new_images);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/local_bisnes/items/galleryImageAddComponent.vue?vue&type=template&id=1a61a49b&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/local_bisnes/items/galleryImageAddComponent.vue?vue&type=template&id=1a61a49b& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function render() {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", [_vm._m(0), _vm._v(" "), _c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "form-groupe"
  }, [_c("button", {
    staticClass: "btn btn-primary float-left",
    on: {
      click: function click($event) {
        return _vm.add_new_image_value();
      }
    }
  }, [_vm._v("Add new imagee")])])])]), _vm._v(" "), _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("table", {
    staticClass: "table table-hover",
    attrs: {
      id: "dev-table"
    }
  }, [_vm._m(1), _vm._v(" "), _c("tbody", _vm._l(_vm.new_images, function (image) {
    return _c("tr", {
      key: image.id
    }, [_c("td", [_c("form", {
      ref: "myForm",
      refInFor: true
    }, [_c("input", {
      attrs: {
        type: "file",
        name: "image",
        id: "image"
      },
      on: {
        change: function change($event) {
          return _vm.onFileChange($event, image.id);
        }
      }
    })])]), _vm._v(" "), _c("td", [_vm._v("|")]), _vm._v(" "), _c("td", [_c("button", {
      staticClass: "btn btn-danger",
      on: {
        click: function click($event) {
          return _vm.del_image(image.id);
        }
      }
    }, [_vm._v("Delete")])])]);
  }), 0)])])]), _vm._v(" "), _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "form-groupe"
  }, [_c("button", {
    staticClass: "btn btn-primary float-left",
    on: {
      click: function click($event) {
        return _vm.add_new_image_value();
      }
    }
  }, [_vm._v("Add new imagee")])])])])])]);
};
var staticRenderFns = [function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "row"
  }, [_c("h2", [_vm._v("Add new gallery inmages")])])]);
}, function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("thead", [_c("tr", [_c("th", [_vm._v("Image")]), _vm._v(" "), _c("th", [_vm._v("|")]), _vm._v(" "), _c("th", [_vm._v("Delite")])])]);
}];
render._withStripped = true;


/***/ }),

/***/ "./resources/js/components/user/pages/local_bisnes/items/galleryImageAddComponent.vue":
/*!********************************************************************************************!*\
  !*** ./resources/js/components/user/pages/local_bisnes/items/galleryImageAddComponent.vue ***!
  \********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _galleryImageAddComponent_vue_vue_type_template_id_1a61a49b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./galleryImageAddComponent.vue?vue&type=template&id=1a61a49b& */ "./resources/js/components/user/pages/local_bisnes/items/galleryImageAddComponent.vue?vue&type=template&id=1a61a49b&");
/* harmony import */ var _galleryImageAddComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./galleryImageAddComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/local_bisnes/items/galleryImageAddComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _galleryImageAddComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _galleryImageAddComponent_vue_vue_type_template_id_1a61a49b___WEBPACK_IMPORTED_MODULE_0__.render,
  _galleryImageAddComponent_vue_vue_type_template_id_1a61a49b___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/local_bisnes/items/galleryImageAddComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/local_bisnes/items/galleryImageAddComponent.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/local_bisnes/items/galleryImageAddComponent.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_galleryImageAddComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./galleryImageAddComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/local_bisnes/items/galleryImageAddComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_galleryImageAddComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/local_bisnes/items/galleryImageAddComponent.vue?vue&type=template&id=1a61a49b&":
/*!***************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/local_bisnes/items/galleryImageAddComponent.vue?vue&type=template&id=1a61a49b& ***!
  \***************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_galleryImageAddComponent_vue_vue_type_template_id_1a61a49b___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_galleryImageAddComponent_vue_vue_type_template_id_1a61a49b___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_galleryImageAddComponent_vue_vue_type_template_id_1a61a49b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./galleryImageAddComponent.vue?vue&type=template&id=1a61a49b& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/local_bisnes/items/galleryImageAddComponent.vue?vue&type=template&id=1a61a49b&");


/***/ })

}]);