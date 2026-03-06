"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_routes_and_sectors_routes_items_routeEditComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
Object(function webpackMissingModule() { var e = new Error("Cannot find module '../../../items/canvas/EditorComponent.vue'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module '../../../../../mixins/editor/editor_config_mixin.js'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module '../../../items/validator_alerts_component.vue'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  mixins: [Object(function webpackMissingModule() { var e = new Error("Cannot find module '../../../../../mixins/editor/editor_config_mixin.js'"); e.code = 'MODULE_NOT_FOUND'; throw e; }())],
  components: {
    Editor: Object(function webpackMissingModule() { var e = new Error("Cannot find module '../../../items/canvas/EditorComponent.vue'"); e.code = 'MODULE_NOT_FOUND'; throw e; }()),
    validator_alerts_component: Object(function webpackMissingModule() { var e = new Error("Cannot find module '../../../items/validator_alerts_component.vue'"); e.code = 'MODULE_NOT_FOUND'; throw e; }())
  },
  data: function data() {
    return {
      description_editor: Object(function webpackMissingModule() { var e = new Error("Cannot find module '../../../../../mixins/editor/editor_config_mixin.js'"); e.code = 'MODULE_NOT_FOUND'; throw e; }())(),
      errors: [],
      regions: [],
      all_sectors: [],
      sectors: [],
      data: [],
      canvas_data: {
        json: '',
        route_id: 0,
        sector_image_id: 0
      },
      sector_images: [],
      image_num: 0,
      is_loading: false,
      is_back_action_query: true,
      sport_route_grade: ["4", "5a", "5b", "5c", "5c+", "6a", "6a+", "6b", "6b+", "6c", "6c+", "7a", "7a+", "7b", "7b+", "7c", "7c+", "8a", "8a+", "8b", "8b+", "8c", "8c+", "9a", "9a+", "9b", "9b+", "9c", "9c+"],
      boulder_route_grade: ["V1", "V2", "V3", "V4", "V5", "V6", "V7", "V8", "V9", "V10", "V11", "V12", "V13", "V14", "V15", "V16", "V17", "V18"],
      ice_climbing: ["WI1", "WI2", "WI3", "WI4", "WI5", "WI6"],
      dry_tooling: ["M1", "M2", "M3", "M4", "M5", "M6", "M7", "M8", "M9", "M10", "M11", "M12"]
    };
  },
  mounted: function mounted() {
    this.get_region_data();
    document.querySelector('body').style.marginLeft = '0';
    document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
  },
  beforeRouteLeave: function beforeRouteLeave(to, from, next) {
    if (this.is_back_action_query == true) {
      if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
        this.is_back_action_query = false;
        next();
      } else {
        next(false);
      }
    } else {
      next();
    }
  },
  methods: {
    get_region_data: function get_region_data() {
      var _this = this;
      axios.get("/article/get_category_articles/outdoor").then(function (response) {
        _this.regions = response.data;
        _this.get_sectors_data();
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    get_sectors_data: function get_sectors_data() {
      var _this2 = this;
      axios.get("/sector/").then(function (response) {
        _this2.all_sectors = response.data;
        _this2.get_route_editing_data();
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    get_route_editing_data: function get_route_editing_data() {
      var _this3 = this;
      this.is_loading = true;
      axios.get("/route/get_route_editing_data/" + this.$route.params.id).then(function (response) {
        _this3.data = response.data;
        var sector = _this3.all_sectors.find(function (item) {
          return item.id === _this3.data.sector_id;
        });
        var action_article = _this3.regions.find(function (item) {
          return item.id === sector.article_id;
        });
        _this3.data.article_id = action_article.id;
        _this3.filter_sectors();
        _this3.get_actyve_sector_images(_this3.data.sector_id, _this3.data.id);
        _this3.import_json_in_editor(_this3.data.json.json);
      })["catch"](function (error) {
        return console.log(error);
      })["finally"](function () {
        return _this3.is_loading = false;
      });
    },
    filter_sectors: function filter_sectors() {
      var vm = this;
      this.sectors = this.all_sectors.filter(function (item) {
        return item.article_id == vm.data.article_id;
      });
    },
    get_actyve_sector_images: function get_actyve_sector_images(sector_img, id) {
      var _this4 = this;
      axios.get("/sector/get_sector_images/" + sector_img).then(function (response) {
        _this4.sector_images = response.data;
        _this4.get_route_json(id);
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    get_route_json: function get_route_json(route_id) {
      var _this5 = this;
      axios.get("/route_json/get_editing_route_json/" + route_id).then(function (response) {
        if (response.data.length == 0) {
          _this5.canvas_data.route_id = _this5.data.id;
        } else {
          _this5.canvas_data.json = response.data.json;
          _this5.canvas_data.route_id = response.data.route_id;
          _this5.canvas_data.sector_image_id = response.data.sector_image_id;
          _this5.image_num = _this5.canvas_data.sector_image_id;
          var vm = _this5;
          var image = _this5.sector_images.find(function (item) {
            return item.id === vm.canvas_data.sector_image_id;
          });
          _this5.change_image(image.image, _this5.image_num);
        }
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    import_json_in_editor: function import_json_in_editor(json) {
      // this.$refs.canvasEditor.change_image
      console.log("🚀 ~ import_json_in_editor ~ this.$refs.canvasEditor.change_image:", this.$refs.canvasEditor);
      // this.$refs.canvasEditor.import_json_in_editor(json)
      // console.log("🚀 ~ import_json_in_editor ~ this.$refs.canvasEditor:", this.$refs)
    },
    change_image: function change_image(image, image_id) {
      this.$refs.canvasEditor.change_image(image);
      this.canvas_data.sector_image_id = image_id;
    },
    update_canvas_data: function update_canvas_data(event) {
      this.canvas_data.json = event;
    },
    save_editing_route: function save_editing_route() {
      var _this6 = this;
      axios.post("/route/edit_route/" + this.$route.params.id, {
        data: [this.data, this.canvas_data]
      }).then(function (response) {
        _this6.go_back(true);
      })["catch"](function (error) {
        _this6.status = "error";
      });
    },
    go_back: function go_back() {
      var action = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
      this.is_back_action_query = this.$going.back(this, action);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue?vue&type=template&id=10906cb3&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue?vue&type=template&id=10906cb3& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function render() {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "col-md-12"
  }, [_vm.is_loading ? _c("div", {
    staticClass: "row justify-content-center"
  }, [_c("div", {
    staticClass: "col-md-4"
  }, [_c("img", {
    attrs: {
      src: "../../../../../../public/images/site_img/loading.gif",
      alt: "loading"
    }
  })])]) : _vm._e(), _vm._v(" "), !_vm.is_loading ? _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "form-group"
  }, [_c("button", {
    staticClass: "btn btn-primary",
    attrs: {
      type: "submit"
    },
    on: {
      click: function click($event) {
        return _vm.go_back();
      }
    }
  }, [_vm._v("Beck")])])]) : _vm._e(), _vm._v(" "), !_vm.is_loading ? _c("div", {
    staticClass: "row"
  }, [_vm._m(0)]) : _vm._e(), _vm._v(" "), _c("div", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: !_vm.is_loading,
      expression: "!is_loading"
    }],
    staticClass: "row"
  }, [_c("validator_alerts_component", {
    attrs: {
      errors_prop: _vm.errors
    }
  })], 1), _vm._v(" "), !_vm.is_loading ? _c("div", {
    staticClass: "wrapper container-fluid container"
  }, [_c("form", {
    attrs: {
      id: "route_add_form"
    },
    on: {
      submit: function submit($event) {
        $event.preventDefault();
        return _vm.save_editing_route();
      }
    }
  }, [_c("div", {
    staticClass: "form-group clearfix row"
  }, [_c("label", {
    staticClass: "col-md-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Region ")]), _vm._v(" "), _c("div", {
    staticClass: "col-md-5"
  }, [_c("select", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.article_id,
      expression: "data.article_id"
    }],
    staticClass: "form-control",
    attrs: {
      required: ""
    },
    on: {
      click: function click($event) {
        return _vm.filter_sectors();
      },
      change: function change($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
          return o.selected;
        }).map(function (o) {
          var val = "_value" in o ? o._value : o.value;
          return val;
        });
        _vm.$set(_vm.data, "article_id", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
      }
    }
  }, [_c("option", {
    attrs: {
      value: "",
      disabled: ""
    }
  }, [_vm._v("Select outdoor article")]), _vm._v(" "), _vm._l(_vm.regions, function (region) {
    return _c("option", {
      key: region.id,
      domProps: {
        value: region.id
      }
    }, [_vm._v(_vm._s(region.url_title))]);
  })], 2)]), _vm._v(" "), _c("div", {
    staticClass: "col-md-5"
  }, [_vm.data.article_id != "" ? _c("select", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.sector_id,
      expression: "data.sector_id"
    }],
    staticClass: "form-control",
    attrs: {
      required: ""
    },
    on: {
      change: function change($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
          return o.selected;
        }).map(function (o) {
          var val = "_value" in o ? o._value : o.value;
          return val;
        });
        _vm.$set(_vm.data, "sector_id", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
      }
    }
  }, [_c("option", {
    attrs: {
      value: "",
      disabled: ""
    }
  }, [_vm._v("Select sector")]), _vm._v(" "), _vm._l(_vm.sectors, function (sector) {
    return _c("option", {
      key: sector.id,
      domProps: {
        value: sector.id
      }
    }, [_vm._v(_vm._s(sector.name))]);
  })], 2) : _vm._e()])]), _vm._v(" "), _vm.data.sector_id != "" ? _c("div", {
    staticClass: "form-group clearfix row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_vm._m(1), _vm._v(" "), _c("div", {
    staticClass: "row"
  }, _vm._l(_vm.sector_images, function (image) {
    return _c("span", {
      key: image.id,
      "class": "sector_images sector_images_" + _vm.sector_images.length
    }, [_c("input", {
      directives: [{
        name: "model",
        rawName: "v-model",
        value: _vm.image_num,
        expression: "image_num"
      }],
      attrs: {
        type: "radio",
        id: image.id,
        name: "fav_language"
      },
      domProps: {
        value: image.id,
        checked: _vm._q(_vm.image_num, image.id)
      },
      on: {
        click: function click($event) {
          return _vm.change_image(image.image, image.id);
        },
        change: function change($event) {
          _vm.image_num = image.id;
        }
      }
    }), _vm._v(" "), _c("label", {
      attrs: {
        "for": image.id
      }
    }, [_c("img", {
      attrs: {
        src: "/public/images/sector_img/" + image.image,
        alt: image.image
      }
    })])]);
  }), 0)])]) : _vm._e(), _vm._v(" "), _vm.data.sector_id != "" ? _c("div", {
    staticClass: "form-group clearfix row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "row"
  }, [_c("Editor", {
    ref: "canvasEditor",
    on: {
      canvas_data: _vm.update_canvas_data
    }
  })], 1)])]) : _vm._e(), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix row"
  }, [_c("label", {
    staticClass: "col-md-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Grade ")]), _vm._v(" "), _c("div", {
    staticClass: "col-md-5"
  }, [_c("select", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.category,
      expression: "data.category"
    }],
    staticClass: "form-control",
    attrs: {
      required: ""
    },
    on: {
      change: function change($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
          return o.selected;
        }).map(function (o) {
          var val = "_value" in o ? o._value : o.value;
          return val;
        });
        _vm.$set(_vm.data, "category", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
      }
    }
  }, [_c("option", {
    attrs: {
      value: "",
      disabled: ""
    }
  }, [_vm._v("Please select route type")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "sport climbing"
    }
  }, [_vm._v("Stort climbing")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "bouldering"
    }
  }, [_vm._v("Bouldering")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "tred"
    }
  }, [_vm._v("Tred Climbing")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "top"
    }
  }, [_vm._v("Top Roupe")])])]), _vm._v(" "), _vm.data.category != "" && _vm.data.category == "sport climbing" || _vm.data.category == "top" || _vm.data.category == "tred" ? _c("div", {
    staticClass: "col-md-5"
  }, [_c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-6"
  }, [_vm.data.category != "" && _vm.data.category == "sport climbing" || _vm.data.category == "top" || _vm.data.category == "tred" ? _c("select", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.grade,
      expression: "data.grade"
    }],
    staticClass: "form-control",
    attrs: {
      required: ""
    },
    on: {
      change: function change($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
          return o.selected;
        }).map(function (o) {
          var val = "_value" in o ? o._value : o.value;
          return val;
        });
        _vm.$set(_vm.data, "grade", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
      }
    }
  }, [_c("option", {
    attrs: {
      value: ""
    }
  }, [_vm._v(" No grade ")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "Project"
    }
  }, [_vm._v("Project")]), _vm._v(" "), _vm._l(_vm.sport_route_grade, function (sport) {
    return _c("option", {
      key: sport,
      domProps: {
        value: sport,
        selected: true
      }
    }, [_vm._v(_vm._s(sport))]);
  })], 2) : _vm._e()]), _vm._v(" "), _c("div", {
    staticClass: "col-md-6"
  }, [_vm.data.category != "" && _vm.data.category == "sport climbing" || _vm.data.category == "top" || _vm.data.category == "tred" ? _c("select", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.or_grade,
      expression: "data.or_grade"
    }],
    staticClass: "form-control",
    on: {
      change: function change($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
          return o.selected;
        }).map(function (o) {
          var val = "_value" in o ? o._value : o.value;
          return val;
        });
        _vm.$set(_vm.data, "or_grade", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
      }
    }
  }, [_c("option", {
    attrs: {
      value: ""
    }
  }, [_vm._v(" No grade ")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "Project"
    }
  }, [_vm._v("Project")]), _vm._v(" "), _vm._l(_vm.sport_route_grade, function (sport) {
    return _c("option", {
      key: sport,
      domProps: {
        value: sport,
        selected: true
      }
    }, [_vm._v(_vm._s(sport))]);
  })], 2) : _vm._e()])])]) : _vm._e(), _vm._v(" "), _vm.data.category != "" && _vm.data.category == "bouldering" ? _c("div", {
    staticClass: "col-md-5"
  }, [_c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-6"
  }, [_vm.data.category != "" && _vm.data.category == "bouldering" ? _c("select", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.grade,
      expression: "data.grade"
    }],
    staticClass: "form-control",
    attrs: {
      required: ""
    },
    on: {
      change: function change($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
          return o.selected;
        }).map(function (o) {
          var val = "_value" in o ? o._value : o.value;
          return val;
        });
        _vm.$set(_vm.data, "grade", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
      }
    }
  }, [_c("option", {
    attrs: {
      value: ""
    }
  }, [_vm._v(" No grade ")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "Project"
    }
  }, [_vm._v("Project")]), _vm._v(" "), _vm._l(_vm.boulder_route_grade, function (boulder) {
    return _c("option", {
      key: boulder,
      domProps: {
        value: boulder,
        selected: true
      }
    }, [_vm._v(_vm._s(boulder))]);
  })], 2) : _vm._e()]), _vm._v(" "), _c("div", {
    staticClass: "col-md-6"
  }, [_vm.data.category != "" && _vm.data.category == "bouldering" ? _c("select", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.or_grade,
      expression: "data.or_grade"
    }],
    staticClass: "form-control",
    on: {
      change: function change($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
          return o.selected;
        }).map(function (o) {
          var val = "_value" in o ? o._value : o.value;
          return val;
        });
        _vm.$set(_vm.data, "or_grade", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
      }
    }
  }, [_c("option", {
    attrs: {
      value: ""
    }
  }, [_vm._v(" No grade ")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "Project"
    }
  }, [_vm._v("Project")]), _vm._v(" "), _vm._l(_vm.boulder_route_grade, function (boulder) {
    return _c("option", {
      key: boulder,
      domProps: {
        value: boulder,
        selected: true
      }
    }, [_vm._v(_vm._s(boulder))]);
  })], 2) : _vm._e()])])]) : _vm._e()]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix row"
  }, [_c("label", {
    staticClass: "col-md-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Route name ")]), _vm._v(" "), _c("div", {
    staticClass: "col-md-10"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.name,
      expression: "data.name"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "name",
      placeholder: "Route name..",
      required: ""
    },
    domProps: {
      value: _vm.data.name
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data, "name", $event.target.value);
      }
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix row"
  }, [_c("label", {
    staticClass: "col-md-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Description (Text) ")]), _vm._v(" "), _c("div", {
    staticClass: "col-md-10"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.description_editor
    },
    model: {
      value: _vm.data.text,
      callback: function callback($$v) {
        _vm.$set(_vm.data, "text", $$v);
      },
      expression: "data.text"
    }
  })], 1)]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix row"
  }, [_c("label", {
    staticClass: "col-md-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Bolts & height ")]), _vm._v(" "), _vm.data.category != "" && _vm.data.category == "sport climbing" ? _c("div", {
    staticClass: "col-md-2"
  }, [_c("label", {
    staticClass: "col-md-12 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Bolts: ")])]) : _vm._e(), _vm._v(" "), _vm.data.category != "" && _vm.data.category == "sport climbing" ? _c("div", {
    staticClass: "col-md-3"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.bolts,
      expression: "data.bolts"
    }],
    staticClass: "form-control",
    attrs: {
      type: "number",
      name: "title",
      placeholder: "Bolts"
    },
    domProps: {
      value: _vm.data.bolts
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data, "bolts", $event.target.value);
      }
    }
  })]) : _vm._e(), _vm._v(" "), _vm._m(2), _vm._v(" "), _c("div", {
    staticClass: "col-md-3"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.height,
      expression: "data.height"
    }],
    staticClass: "form-control",
    attrs: {
      type: "number",
      name: "title",
      placeholder: "Height"
    },
    domProps: {
      value: _vm.data.height
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data, "height", $event.target.value);
      }
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix row"
  }, [_c("label", {
    staticClass: "col-md-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Ancher and bolt type: ")]), _vm._v(" "), _vm.data.category != "" && _vm.data.category != "bouldering" ? _c("div", {
    staticClass: "col-md-2"
  }, [_c("label", {
    staticClass: "col-md-12 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Anchor type: ")])]) : _vm._e(), _vm._v(" "), _vm.data.category != "" && _vm.data.category != "bouldering" ? _c("div", {
    staticClass: "col-md-3"
  }, [_c("select", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.anchor_type,
      expression: "data.anchor_type"
    }],
    staticClass: "form-control",
    on: {
      change: function change($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
          return o.selected;
        }).map(function (o) {
          var val = "_value" in o ? o._value : o.value;
          return val;
        });
        _vm.$set(_vm.data, "anchor_type", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
      }
    }
  }, [_c("option", {
    attrs: {
      value: "",
      disabled: ""
    }
  }, [_vm._v("Anchor type")]), _vm._v(" "), _c("option", {
    domProps: {
      value: null
    }
  }, [_vm._v("?")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "Chain"
    }
  }, [_vm._v("Chain")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "Chain with carabiner"
    }
  }, [_vm._v("Chain with carabiner")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "Chain with ring"
    }
  }, [_vm._v("Chain with ring")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "2 independent chains"
    }
  }, [_vm._v("2 independent chains")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "2 bolts with carabiner"
    }
  }, [_vm._v("2 bolts with carabiner")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "2 bolts only"
    }
  }, [_vm._v("2 bolts only")])])]) : _vm._e(), _vm._v(" "), _vm.data.category != "" && _vm.data.category != "bouldering" ? _c("div", {
    staticClass: "col-md-2"
  }, [_c("label", {
    staticClass: "col-md-12 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Bolts type: ")])]) : _vm._e(), _vm._v(" "), _vm.data.category != "" && _vm.data.category != "bouldering" ? _c("div", {
    staticClass: "col-md-3"
  }, [_c("select", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.bolts_type,
      expression: "data.bolts_type"
    }],
    staticClass: "form-control",
    on: {
      change: function change($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
          return o.selected;
        }).map(function (o) {
          var val = "_value" in o ? o._value : o.value;
          return val;
        });
        _vm.$set(_vm.data, "bolts_type", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
      }
    }
  }, [_c("option", {
    attrs: {
      value: "",
      disabled: ""
    }
  }, [_vm._v("Bolts type")]), _vm._v(" "), _c("option", {
    domProps: {
      value: null
    }
  }, [_vm._v("?")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "glued"
    }
  }, [_vm._v("Glued-in bolts")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "hangerr"
    }
  }, [_vm._v("Hangerr bolts")])])]) : _vm._e()]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix row"
  }, [_c("label", {
    staticClass: "col-md-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Author & Creation date ")]), _vm._v(" "), _c("div", {
    staticClass: "col-md-5"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.author,
      expression: "data.author"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "auther",
      placeholder: "Bolter"
    },
    domProps: {
      value: _vm.data.author
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data, "author", $event.target.value);
      }
    }
  })]), _vm._v(" "), _c("div", {
    staticClass: "col-md-5"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.creation_data,
      expression: "data.creation_data"
    }],
    staticClass: "form-control",
    attrs: {
      type: "date",
      name: "creating_data",
      placeholder: "Bolting Data"
    },
    domProps: {
      value: _vm.data.creation_data
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data, "creation_data", $event.target.value);
      }
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix row"
  }, [_c("label", {
    staticClass: "col-md-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v("Firs Ascent ")]), _vm._v(" "), _c("div", {
    staticClass: "col-md-5"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.first_ascent,
      expression: "data.first_ascent"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "first_ascent",
      placeholder: "First ascent"
    },
    domProps: {
      value: _vm.data.first_ascent
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data, "first_ascent", $event.target.value);
      }
    }
  })])])])]) : _vm._e()]);
};
var staticRenderFns = [function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "form-group"
  }, [_c("button", {
    staticClass: "btn btn-primary",
    attrs: {
      form: "route_add_form",
      type: "submit"
    }
  }, [_vm._v("Save and go back")]), _vm._v(" "), _c("p", [_vm._v("Save and go to route tab page")])]);
}, function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "row"
  }, [_c("h2", [_vm._v("Select Routes Image")])]);
}, function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "col-md-2"
  }, [_c("label", {
    staticClass: "col-md-12 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Metrs: ")])]);
}];
render._withStripped = true;


/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue?vue&type=style&index=0&id=10906cb3&lang=css&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue?vue&type=style&index=0&id=10906cb3&lang=css& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js */ "./node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "\n.sector_images {\n    float: left;\n    margin: 0.45%;\n}\n.sector_images_1 {\n    width: 99% !important;\n}\n.sector_images_2 {\n    width: 49% !important;\n}\n.sector_images_3 {\n    width: 32% !important;\n}\n.sector_images_4 {\n    width: 24% !important;\n}\n.sector_images_5 {\n    width: 19% !important;\n}\n.sector_images_6 {\n    width: 16% !important;\n}\n.sector_images_7 {\n    width: 14% !important;\n}\n.sector_images_8 {\n    width: 12% !important;\n}\n.sector_images_9 {\n    width: 10% !important;\n}\n.sector_images_10 {\n    width: 9% !important;\n}\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue?vue&type=style&index=0&id=10906cb3&lang=css&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue?vue&type=style&index=0&id=10906cb3&lang=css& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_routeEditComponent_vue_vue_type_style_index_0_id_10906cb3_lang_css___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!../../../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./routeEditComponent.vue?vue&type=style&index=0&id=10906cb3&lang=css& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue?vue&type=style&index=0&id=10906cb3&lang=css&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_routeEditComponent_vue_vue_type_style_index_0_id_10906cb3_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_routeEditComponent_vue_vue_type_style_index_0_id_10906cb3_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue":
/*!***************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue ***!
  \***************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _routeEditComponent_vue_vue_type_template_id_10906cb3___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./routeEditComponent.vue?vue&type=template&id=10906cb3& */ "./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue?vue&type=template&id=10906cb3&");
/* harmony import */ var _routeEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./routeEditComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _routeEditComponent_vue_vue_type_style_index_0_id_10906cb3_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./routeEditComponent.vue?vue&type=style&index=0&id=10906cb3&lang=css& */ "./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue?vue&type=style&index=0&id=10906cb3&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _routeEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _routeEditComponent_vue_vue_type_template_id_10906cb3___WEBPACK_IMPORTED_MODULE_0__.render,
  _routeEditComponent_vue_vue_type_template_id_10906cb3___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_routeEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./routeEditComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_routeEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue?vue&type=template&id=10906cb3&":
/*!**********************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue?vue&type=template&id=10906cb3& ***!
  \**********************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_routeEditComponent_vue_vue_type_template_id_10906cb3___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_routeEditComponent_vue_vue_type_template_id_10906cb3___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_routeEditComponent_vue_vue_type_template_id_10906cb3___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./routeEditComponent.vue?vue&type=template&id=10906cb3& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue?vue&type=template&id=10906cb3&");


/***/ }),

/***/ "./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue?vue&type=style&index=0&id=10906cb3&lang=css&":
/*!************************************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue?vue&type=style&index=0&id=10906cb3&lang=css& ***!
  \************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_routeEditComponent_vue_vue_type_style_index_0_id_10906cb3_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/style-loader/dist/cjs.js!../../../../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!../../../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./routeEditComponent.vue?vue&type=style&index=0&id=10906cb3&lang=css& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/routes/items/routeEditComponent.vue?vue&type=style&index=0&id=10906cb3&lang=css&");


/***/ })

}]);