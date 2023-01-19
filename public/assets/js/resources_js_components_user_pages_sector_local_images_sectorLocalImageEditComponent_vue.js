"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_sector_local_images_sectorLocalImageEditComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/sector_local_images/addSectorModalComponent.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/sector_local_images/addSectorModalComponent.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************************************************************************************/
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
 //https://github.com/Jexordexan/vue-slicksort

 //https://innologica.github.io/vue-stackable-modal/#sample-css

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    StackModal: (_innologica_vue_stackable_modal__WEBPACK_IMPORTED_MODULE_1___default()),
    SlickItem: vue_slicksort__WEBPACK_IMPORTED_MODULE_0__.SlickItem,
    SlickList: vue_slicksort__WEBPACK_IMPORTED_MODULE_0__.SlickList
  },
  props: [// 'add_sector_modal_prop',
  ],
  data: function data() {
    return {
      sectors: [],
      filtred_sectors: [],
      outdoor_areas: [],
      sectors_loading: false,
      add_sector_modal: false,
      selected_sector: 'Select sector',
      selected_outdoor_area: 'Select outdoor area',
      image_sectors: [],
      data: {
        title: '',
        image: ''
      }
    };
  },
  mounted: function mounted() {
    this.get_all_outdoor_areas();
    this.get_all_sectors();
  },
  methods: {
    get_all_outdoor_areas: function get_all_outdoor_areas() {
      var _this = this;

      this.sectors_loading = true;
      axios.post("../../api/article/", {
        category: 'outdoor'
      }).then(function (response) {
        _this.outdoor_areas = response.data;
      })["catch"](function (error) {
        return console.log(error);
      })["finally"](function () {
        return _this.sectors_loading = false;
      });
    },
    get_all_sectors: function get_all_sectors() {
      var _this2 = this;

      axios.get("../../api/sector/").then(function (response) {
        _this2.sectors = response.data;
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    get_region_sectors: function get_region_sectors() {
      if (this.selected_outdoor_area != 'Select outdoor area' && this.selected_outdoor_area != 'All sectors') {
        var vm = this;
        this.filtred_sectors = this.sectors.filter(function (item) {
          return item.article_id == vm.selected_outdoor_area;
        });
      } else if (this.selected_outdoor_area != 'Select outdoor area' && this.selected_outdoor_area == 'All sectors') {
        this.filtred_sectors = this.sectors;
      } else {
        this.filtred_sectors = this.sectors;
      }
    },
    close_add_sector_modal: function close_add_sector_modal() {
      this.selected_sector = 'Select sector';
      this.add_sector_modal = false;
    },
    add_new_sector_model: function add_new_sector_model() {
      this.add_sector_modal = true;
    },
    add_sector_from_modal: function add_sector_from_modal(event) {
      this.$emit('sector', this.selected_sector);
      this.close_add_sector_modal();
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/sector_local_images/sectorLocalImageEditComponent.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/sector_local_images/sectorLocalImageEditComponent.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _addSectorModalComponent_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./addSectorModalComponent.vue */ "./resources/js/components/user/pages/sector_local_images/addSectorModalComponent.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    addSectorModal: _addSectorModalComponent_vue__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  props: [// 'status',
  ],
  data: function data() {
    return {
      old_image_sectors: [],
      new_image_sectors: [],
      data: {
        title: '',
        image: ''
      },
      image: '',
      sectors_refresh: false
    };
  },
  mounted: function mounted() {
    // this.get_all_sectors()
    this.get_editing_image();
  },
  methods: {
    get_editing_image: function get_editing_image() {
      var _this = this;

      axios.get('../../api/sector_local_images/' + this.$route.params.id).then(function (response) {
        _this.data.title = response.data.sector_local_image.title;
        _this.data.image = response.data.sector_local_image.image;
        _this.old_image_sectors = response.data.sector_local_image_sectors;
      })["catch"]();
    },
    get_editing_sectors: function get_editing_sectors() {
      var _this2 = this;

      axios.get('../../api/get_editing_sectors/' + this.$route.params.id).then(function (response) {
        _this2.old_image_sectors = response.data;
      })["catch"]();
    },
    add_new_sector: function add_new_sector(e) {
      if (e != 'Select sector') {
        var new_item_id = this.new_image_sectors.length + 1;
        this.new_image_sectors.push({
          id: e.id,
          name: e.name
        });
        this.close_add_sector_modal();
      }
    },
    del_image_sector_from_db: function del_image_sector_from_db(sector_id) {
      var _this3 = this;

      if (confirm('Are you sure, you want delite itt?')) {
        axios.post('../../api/del_image_sector_from_db/' + this.$route.params.id + '/' + sector_id, {
          _method: 'delete'
        }).then(function (response) {
          // alert(response.data)
          _this3.get_editing_sectors();
        })["catch"]();
      }
    },
    del_sector: function del_sector(id) {
      this.removeObjectWithId(this.old_image_sectors, id);
    },
    removeObjectWithId: function removeObjectWithId(arr, id) {
      var objWithIdIndex = arr.findIndex(function (obj) {
        return obj.id === id;
      });
      arr.splice(objWithIdIndex, 1);
      return arr;
    },
    onFileChange: function onFileChange(e) {
      this.image = e.target.files[0];
    },
    update: function update() {
      var _this4 = this;

      var config = {
        headers: {
          'content-type': 'multipart/form-data'
        }
      };
      var formData = new FormData();
      formData.append('image', this.image);
      formData.append('image_sectors', JSON.stringify(this.new_image_sectors));
      formData.append('title', this.data.title);
      axios.post('../../api/sector_local_images/update_image/' + this.$route.params.id, // axios.put('../../api/sector_local_images/'+this.$route.params.id, 
      formData, config).then(function (response) {
        _this4.$router.go(-1);
      })["catch"]();
    },
    go_back: function go_back() {
      if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
        this.$router.go(-1);
      }
    }
  }
});

/***/ }),

/***/ "./resources/js/components/user/pages/sector_local_images/addSectorModalComponent.vue":
/*!********************************************************************************************!*\
  !*** ./resources/js/components/user/pages/sector_local_images/addSectorModalComponent.vue ***!
  \********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _addSectorModalComponent_vue_vue_type_template_id_32fd8c09___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./addSectorModalComponent.vue?vue&type=template&id=32fd8c09& */ "./resources/js/components/user/pages/sector_local_images/addSectorModalComponent.vue?vue&type=template&id=32fd8c09&");
/* harmony import */ var _addSectorModalComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./addSectorModalComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/sector_local_images/addSectorModalComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _addSectorModalComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _addSectorModalComponent_vue_vue_type_template_id_32fd8c09___WEBPACK_IMPORTED_MODULE_0__.render,
  _addSectorModalComponent_vue_vue_type_template_id_32fd8c09___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/sector_local_images/addSectorModalComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/sector_local_images/sectorLocalImageEditComponent.vue":
/*!**************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/sector_local_images/sectorLocalImageEditComponent.vue ***!
  \**************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _sectorLocalImageEditComponent_vue_vue_type_template_id_1407ee6f___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./sectorLocalImageEditComponent.vue?vue&type=template&id=1407ee6f& */ "./resources/js/components/user/pages/sector_local_images/sectorLocalImageEditComponent.vue?vue&type=template&id=1407ee6f&");
/* harmony import */ var _sectorLocalImageEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./sectorLocalImageEditComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/sector_local_images/sectorLocalImageEditComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _sectorLocalImageEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _sectorLocalImageEditComponent_vue_vue_type_template_id_1407ee6f___WEBPACK_IMPORTED_MODULE_0__.render,
  _sectorLocalImageEditComponent_vue_vue_type_template_id_1407ee6f___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/sector_local_images/sectorLocalImageEditComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/sector_local_images/addSectorModalComponent.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/sector_local_images/addSectorModalComponent.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_addSectorModalComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./addSectorModalComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/sector_local_images/addSectorModalComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_addSectorModalComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/sector_local_images/sectorLocalImageEditComponent.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/sector_local_images/sectorLocalImageEditComponent.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_sectorLocalImageEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./sectorLocalImageEditComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/sector_local_images/sectorLocalImageEditComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_sectorLocalImageEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/sector_local_images/addSectorModalComponent.vue?vue&type=template&id=32fd8c09&":
/*!***************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/sector_local_images/addSectorModalComponent.vue?vue&type=template&id=32fd8c09& ***!
  \***************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_addSectorModalComponent_vue_vue_type_template_id_32fd8c09___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_addSectorModalComponent_vue_vue_type_template_id_32fd8c09___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_addSectorModalComponent_vue_vue_type_template_id_32fd8c09___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./addSectorModalComponent.vue?vue&type=template&id=32fd8c09& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/sector_local_images/addSectorModalComponent.vue?vue&type=template&id=32fd8c09&");


/***/ }),

/***/ "./resources/js/components/user/pages/sector_local_images/sectorLocalImageEditComponent.vue?vue&type=template&id=1407ee6f&":
/*!*********************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/sector_local_images/sectorLocalImageEditComponent.vue?vue&type=template&id=1407ee6f& ***!
  \*********************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_sectorLocalImageEditComponent_vue_vue_type_template_id_1407ee6f___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_sectorLocalImageEditComponent_vue_vue_type_template_id_1407ee6f___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_sectorLocalImageEditComponent_vue_vue_type_template_id_1407ee6f___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./sectorLocalImageEditComponent.vue?vue&type=template&id=1407ee6f& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/sector_local_images/sectorLocalImageEditComponent.vue?vue&type=template&id=1407ee6f&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/sector_local_images/addSectorModalComponent.vue?vue&type=template&id=32fd8c09&":
/*!******************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/sector_local_images/addSectorModalComponent.vue?vue&type=template&id=32fd8c09& ***!
  \******************************************************************************************************************************************************************************************************************************************************************/
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
    "stack-modal",
    {
      attrs: {
        show: _vm.add_sector_modal,
        title: "Add sector",
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
      on: { close: _vm.close_add_sector_modal },
    },
    [
      _c("pre", { staticClass: "language-vue" }, [
        _vm._v("        "),
        _c(
          "form",
          {
            staticClass: "form",
            attrs: { id: "add_new_sector" },
            on: {
              submit: function ($event) {
                $event.preventDefault()
                return _vm.add_sector_from_modal.apply(null, arguments)
              },
            },
          },
          [
            _vm._v("\n            "),
            _vm._v("\n            "),
            _c(
              "select",
              {
                directives: [
                  {
                    name: "model",
                    rawName: "v-model",
                    value: _vm.selected_outdoor_area,
                    expression: "selected_outdoor_area",
                  },
                ],
                staticClass: "form-control",
                attrs: { name: "comment delete cause" },
                on: {
                  click: function ($event) {
                    return _vm.get_region_sectors()
                  },
                  change: function ($event) {
                    var $$selectedVal = Array.prototype.filter
                      .call($event.target.options, function (o) {
                        return o.selected
                      })
                      .map(function (o) {
                        var val = "_value" in o ? o._value : o.value
                        return val
                      })
                    _vm.selected_outdoor_area = $event.target.multiple
                      ? $$selectedVal
                      : $$selectedVal[0]
                  },
                },
              },
              [
                _vm._v(" \n                "),
                _c(
                  "option",
                  { attrs: { value: "Select outdoor area", disabled: "" } },
                  [_vm._v("Select outdoor area")]
                ),
                _vm._v("\n                "),
                _c("option", { attrs: { value: "All sectors" } }, [
                  _vm._v("Show all sectors"),
                ]),
                _vm._v("\n                "),
                _vm._l(_vm.outdoor_areas, function (outdoor_area) {
                  return _c(
                    "option",
                    {
                      key: outdoor_area.id,
                      domProps: { value: outdoor_area.id },
                    },
                    [_vm._v(_vm._s(outdoor_area.url_title))]
                  )
                }),
                _vm._v("\n            "),
              ],
              2
            ),
            _vm._v("\n\n            "),
            !_vm.sectors_loading && _vm.sectors.length != 0
              ? _c(
                  "select",
                  {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.selected_sector,
                        expression: "selected_sector",
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
                        _vm.selected_sector = $event.target.multiple
                          ? $$selectedVal
                          : $$selectedVal[0]
                      },
                    },
                  },
                  [
                    _vm._v(" \n                "),
                    _c(
                      "option",
                      { attrs: { value: "Select sector", disabled: "" } },
                      [_vm._v("Select sector")]
                    ),
                    _vm._v("\n                "),
                    _vm._l(_vm.filtred_sectors, function (sector) {
                      return _c(
                        "option",
                        {
                          key: sector.id,
                          domProps: {
                            value: { id: sector.id, name: sector.name },
                          },
                        },
                        [_vm._v(_vm._s(sector.name))]
                      )
                    }),
                    _vm._v("\n            "),
                  ],
                  2
                )
              : _vm._e(),
            _vm._v("\n\n            "),
            _vm.sectors_loading
              ? _c("img", {
                  attrs: {
                    src: "../../../public/images/site_img/loading.gif",
                    alt: "loading",
                  },
                })
              : _vm._e(),
            _vm._v("\n        "),
          ]
        ),
        _vm._v("\n    "),
      ]),
      _vm._v(" "),
      _c("div", { attrs: { slot: "modal-footer" }, slot: "modal-footer" }, [
        _c("div", { staticClass: "modal-footer" }, [
          _c(
            "button",
            {
              staticClass: "btn btn-primary",
              attrs: { type: "submit", form: "add_new_sector" },
            },
            [_vm._v("Add new sector")]
          ),
        ]),
      ]),
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/sector_local_images/sectorLocalImageEditComponent.vue?vue&type=template&id=1407ee6f&":
/*!************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/sector_local_images/sectorLocalImageEditComponent.vue?vue&type=template&id=1407ee6f& ***!
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
  return _c(
    "div",
    { staticClass: "row" },
    [
      _c("div", { staticClass: "col-md-12" }, [
        _c("div", { staticClass: "row" }, [
          _vm._m(0),
          _vm._v(" "),
          _c("div", { staticClass: "col-md-6" }, [
            _c("div", { staticClass: "form-groupe" }, [
              _c(
                "button",
                {
                  staticClass: "btn btn-success float-right",
                  on: {
                    click: function ($event) {
                      return _vm.go_back()
                    },
                  },
                },
                [_vm._v("Go back")]
              ),
            ]),
          ]),
        ]),
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "col-md-12" }, [
        _c(
          "form",
          {
            ref: "myForm",
            attrs: { id: "myForm", enctype: "multipart/form-data" },
            on: {
              submit: function ($event) {
                $event.preventDefault()
                return _vm.update.apply(null, arguments)
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
                  attrs: { type: "text", name: "name", required: "" },
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
            _c("div", { staticClass: "form-group clearfix row" }, [
              _c(
                "label",
                {
                  staticClass: "col-md-2 control-label",
                  attrs: { for: "email" },
                },
                [_vm._v("Active image:")]
              ),
              _vm._v(" "),
              _c("div", { staticClass: "col-md-5" }, [
                _c("img", {
                  attrs: {
                    src: "/public/images/sector_local_img/" + _vm.data.image,
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
                  attrs: { for: "email" },
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
        _vm._v(" "),
        _vm._m(1),
        _vm._v(" "),
        _c("div", { staticClass: "row" }, [
          _c("div", { staticClass: "col-md-6" }, [
            _c("div", { staticClass: "form-groupe" }, [
              _c(
                "button",
                {
                  staticClass: "btn btn-primary",
                  on: {
                    click: function ($event) {
                      return _vm.$refs.myChild.add_new_sector_model()
                    },
                  },
                },
                [_vm._v("Add new sector")]
              ),
            ]),
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "col-md-6" }, [
            _c("div", { staticClass: "form-groupe" }, [
              !_vm.sectors_refresh
                ? _c(
                    "button",
                    {
                      staticClass: "btn btn-success float-right",
                      on: { click: _vm.get_editing_sectors },
                    },
                    [_vm._v("Refresh")]
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.sectors_refresh
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
          _c("div", { staticClass: "col-md-12" }, [
            _c(
              "table",
              { staticClass: "table table-hover", attrs: { id: "dev-table" } },
              [
                _vm._m(2),
                _vm._v(" "),
                _vm.old_image_sectors.length
                  ? _c("th", [_vm._v("Previously adde")])
                  : _vm._e(),
                _vm._v(" "),
                _vm.old_image_sectors != []
                  ? _c(
                      "tbody",
                      _vm._l(
                        _vm.old_image_sectors,
                        function (old_image_sector) {
                          return _c("tr", { key: old_image_sector.id }, [
                            _c("td", [_vm._v(_vm._s(old_image_sector.id))]),
                            _vm._v(" "),
                            _c("td", [_vm._v("|")]),
                            _vm._v(" "),
                            _c("td", [
                              _vm._v(_vm._s(old_image_sector.name) + " "),
                            ]),
                            _vm._v(" "),
                            _c("td", [_vm._v("|")]),
                            _vm._v(" "),
                            _c("td", [
                              _c(
                                "button",
                                {
                                  staticClass: "btn btn-danger",
                                  attrs: { type: "submit" },
                                  on: {
                                    click: function ($event) {
                                      return _vm.del_image_sector_from_db(
                                        old_image_sector.id
                                      )
                                    },
                                  },
                                },
                                [_vm._v("Delete")]
                              ),
                            ]),
                          ])
                        }
                      ),
                      0
                    )
                  : _vm._e(),
                _vm._v(" "),
                _vm.new_image_sectors.length
                  ? _c("th", [_vm._v("Added naw")])
                  : _vm._e(),
                _vm._v(" "),
                _vm.new_image_sectors != []
                  ? _c(
                      "tbody",
                      _vm._l(
                        _vm.new_image_sectors,
                        function (new_image_sector) {
                          return _c("tr", { key: new_image_sector.id }, [
                            _c("td", [_vm._v(_vm._s(new_image_sector.id))]),
                            _vm._v(" "),
                            _c("td", [_vm._v("|")]),
                            _vm._v(" "),
                            _c("td", [
                              _vm._v(_vm._s(new_image_sector.name) + " "),
                            ]),
                            _vm._v(" "),
                            _c("td", [_vm._v("|")]),
                            _vm._v(" "),
                            _c("td", [
                              _c(
                                "button",
                                {
                                  staticClass: "btn btn-danger",
                                  attrs: { type: "submit" },
                                  on: {
                                    click: function ($event) {
                                      return _vm.del_sector(new_image_sector.id)
                                    },
                                  },
                                },
                                [_vm._v("Delete")]
                              ),
                            ]),
                          ])
                        }
                      ),
                      0
                    )
                  : _vm._e(),
              ]
            ),
          ]),
        ]),
      ]),
      _vm._v(" "),
      _c("addSectorModal", {
        ref: "myChild",
        on: { sector: _vm.add_new_sector },
      }),
    ],
    1
  )
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "col-md-6" }, [
      _c("div", { staticClass: "form-groupe" }, [
        _c(
          "button",
          {
            staticClass: "btn btn-primary",
            attrs: { type: "submit", form: "myForm" },
          },
          [_vm._v("Save")]
        ),
      ]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-md-12" }, [_c("hr")]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c("tr", [
        _c("th", [_vm._v("Sector id")]),
        _vm._v(" "),
        _c("th", [_vm._v("|")]),
        _vm._v(" "),
        _c("th", [_vm._v("Sector name")]),
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