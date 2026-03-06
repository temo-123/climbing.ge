"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_multimedia_XXX_ArticleImagesPageFixed_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/multimedia/XXX ArticleImagesPageFixed.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/multimedia/XXX ArticleImagesPageFixed.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return exports; }; var exports = {}, Op = Object.prototype, hasOwn = Op.hasOwnProperty, defineProperty = Object.defineProperty || function (obj, key, desc) { obj[key] = desc.value; }, $Symbol = "function" == typeof Symbol ? Symbol : {}, iteratorSymbol = $Symbol.iterator || "@@iterator", asyncIteratorSymbol = $Symbol.asyncIterator || "@@asyncIterator", toStringTagSymbol = $Symbol.toStringTag || "@@toStringTag"; function define(obj, key, value) { return Object.defineProperty(obj, key, { value: value, enumerable: !0, configurable: !0, writable: !0 }), obj[key]; } try { define({}, ""); } catch (err) { define = function define(obj, key, value) { return obj[key] = value; }; } function wrap(innerFn, outerFn, self, tryLocsList) { var protoGenerator = outerFn && outerFn.prototype instanceof Generator ? outerFn : Generator, generator = Object.create(protoGenerator.prototype), context = new Context(tryLocsList || []); return defineProperty(generator, "_invoke", { value: makeInvokeMethod(innerFn, self, context) }), generator; } function tryCatch(fn, obj, arg) { try { return { type: "normal", arg: fn.call(obj, arg) }; } catch (err) { return { type: "throw", arg: err }; } } exports.wrap = wrap; var ContinueSentinel = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var IteratorPrototype = {}; define(IteratorPrototype, iteratorSymbol, function () { return this; }); var getProto = Object.getPrototypeOf, NativeIteratorPrototype = getProto && getProto(getProto(values([]))); NativeIteratorPrototype && NativeIteratorPrototype !== Op && hasOwn.call(NativeIteratorPrototype, iteratorSymbol) && (IteratorPrototype = NativeIteratorPrototype); var Gp = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(IteratorPrototype); function defineIteratorMethods(prototype) { ["next", "throw", "return"].forEach(function (method) { define(prototype, method, function (arg) { return this._invoke(method, arg); }); }); } function AsyncIterator(generator, PromiseImpl) { function invoke(method, arg, resolve, reject) { var record = tryCatch(generator[method], generator, arg); if ("throw" !== record.type) { var result = record.arg, value = result.value; return value && "object" == _typeof(value) && hasOwn.call(value, "__await") ? PromiseImpl.resolve(value.__await).then(function (value) { invoke("next", value, resolve, reject); }, function (err) { invoke("throw", err, resolve, reject); }) : PromiseImpl.resolve(value).then(function (unwrapped) { result.value = unwrapped, resolve(result); }, function (error) { return invoke("throw", error, resolve, reject); }); } reject(record.arg); } var previousPromise; defineProperty(this, "_invoke", { value: function value(method, arg) { function callInvokeWithMethodAndArg() { return new PromiseImpl(function (resolve, reject) { invoke(method, arg, resolve, reject); }); } return previousPromise = previousPromise ? previousPromise.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(innerFn, self, context) { var state = "suspendedStart"; return function (method, arg) { if ("executing" === state) throw new Error("Generator is already running"); if ("completed" === state) { if ("throw" === method) throw arg; return doneResult(); } for (context.method = method, context.arg = arg;;) { var delegate = context.delegate; if (delegate) { var delegateResult = maybeInvokeDelegate(delegate, context); if (delegateResult) { if (delegateResult === ContinueSentinel) continue; return delegateResult; } } if ("next" === context.method) context.sent = context._sent = context.arg;else if ("throw" === context.method) { if ("suspendedStart" === state) throw state = "completed", context.arg; context.dispatchException(context.arg); } else "return" === context.method && context.abrupt("return", context.arg); state = "executing"; var record = tryCatch(innerFn, self, context); if ("normal" === record.type) { if (state = context.done ? "completed" : "suspendedYield", record.arg === ContinueSentinel) continue; return { value: record.arg, done: context.done }; } "throw" === record.type && (state = "completed", context.method = "throw", context.arg = record.arg); } }; } function maybeInvokeDelegate(delegate, context) { var methodName = context.method, method = delegate.iterator[methodName]; if (undefined === method) return context.delegate = null, "throw" === methodName && delegate.iterator["return"] && (context.method = "return", context.arg = undefined, maybeInvokeDelegate(delegate, context), "throw" === context.method) || "return" !== methodName && (context.method = "throw", context.arg = new TypeError("The iterator does not provide a '" + methodName + "' method")), ContinueSentinel; var record = tryCatch(method, delegate.iterator, context.arg); if ("throw" === record.type) return context.method = "throw", context.arg = record.arg, context.delegate = null, ContinueSentinel; var info = record.arg; return info ? info.done ? (context[delegate.resultName] = info.value, context.next = delegate.nextLoc, "return" !== context.method && (context.method = "next", context.arg = undefined), context.delegate = null, ContinueSentinel) : info : (context.method = "throw", context.arg = new TypeError("iterator result is not an object"), context.delegate = null, ContinueSentinel); } function pushTryEntry(locs) { var entry = { tryLoc: locs[0] }; 1 in locs && (entry.catchLoc = locs[1]), 2 in locs && (entry.finallyLoc = locs[2], entry.afterLoc = locs[3]), this.tryEntries.push(entry); } function resetTryEntry(entry) { var record = entry.completion || {}; record.type = "normal", delete record.arg, entry.completion = record; } function Context(tryLocsList) { this.tryEntries = [{ tryLoc: "root" }], tryLocsList.forEach(pushTryEntry, this), this.reset(!0); } function values(iterable) { if (iterable) { var iteratorMethod = iterable[iteratorSymbol]; if (iteratorMethod) return iteratorMethod.call(iterable); if ("function" == typeof iterable.next) return iterable; if (!isNaN(iterable.length)) { var i = -1, next = function next() { for (; ++i < iterable.length;) if (hasOwn.call(iterable, i)) return next.value = iterable[i], next.done = !1, next; return next.value = undefined, next.done = !0, next; }; return next.next = next; } } return { next: doneResult }; } function doneResult() { return { value: undefined, done: !0 }; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, defineProperty(Gp, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), defineProperty(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, toStringTagSymbol, "GeneratorFunction"), exports.isGeneratorFunction = function (genFun) { var ctor = "function" == typeof genFun && genFun.constructor; return !!ctor && (ctor === GeneratorFunction || "GeneratorFunction" === (ctor.displayName || ctor.name)); }, exports.mark = function (genFun) { return Object.setPrototypeOf ? Object.setPrototypeOf(genFun, GeneratorFunctionPrototype) : (genFun.__proto__ = GeneratorFunctionPrototype, define(genFun, toStringTagSymbol, "GeneratorFunction")), genFun.prototype = Object.create(Gp), genFun; }, exports.awrap = function (arg) { return { __await: arg }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, asyncIteratorSymbol, function () { return this; }), exports.AsyncIterator = AsyncIterator, exports.async = function (innerFn, outerFn, self, tryLocsList, PromiseImpl) { void 0 === PromiseImpl && (PromiseImpl = Promise); var iter = new AsyncIterator(wrap(innerFn, outerFn, self, tryLocsList), PromiseImpl); return exports.isGeneratorFunction(outerFn) ? iter : iter.next().then(function (result) { return result.done ? result.value : iter.next(); }); }, defineIteratorMethods(Gp), define(Gp, toStringTagSymbol, "Generator"), define(Gp, iteratorSymbol, function () { return this; }), define(Gp, "toString", function () { return "[object Generator]"; }), exports.keys = function (val) { var object = Object(val), keys = []; for (var key in object) keys.push(key); return keys.reverse(), function next() { for (; keys.length;) { var key = keys.pop(); if (key in object) return next.value = key, next.done = !1, next; } return next.done = !0, next; }; }, exports.values = values, Context.prototype = { constructor: Context, reset: function reset(skipTempReset) { if (this.prev = 0, this.next = 0, this.sent = this._sent = undefined, this.done = !1, this.delegate = null, this.method = "next", this.arg = undefined, this.tryEntries.forEach(resetTryEntry), !skipTempReset) for (var name in this) "t" === name.charAt(0) && hasOwn.call(this, name) && !isNaN(+name.slice(1)) && (this[name] = undefined); }, stop: function stop() { this.done = !0; var rootRecord = this.tryEntries[0].completion; if ("throw" === rootRecord.type) throw rootRecord.arg; return this.rval; }, dispatchException: function dispatchException(exception) { if (this.done) throw exception; var context = this; function handle(loc, caught) { return record.type = "throw", record.arg = exception, context.next = loc, caught && (context.method = "next", context.arg = undefined), !!caught; } for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i], record = entry.completion; if ("root" === entry.tryLoc) return handle("end"); if (entry.tryLoc <= this.prev) { var hasCatch = hasOwn.call(entry, "catchLoc"), hasFinally = hasOwn.call(entry, "finallyLoc"); if (hasCatch && hasFinally) { if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0); if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc); } else if (hasCatch) { if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0); } else { if (!hasFinally) throw new Error("try statement without catch or finally"); if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc); } } } }, abrupt: function abrupt(type, arg) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.tryLoc <= this.prev && hasOwn.call(entry, "finallyLoc") && this.prev < entry.finallyLoc) { var finallyEntry = entry; break; } } finallyEntry && ("break" === type || "continue" === type) && finallyEntry.tryLoc <= arg && arg <= finallyEntry.finallyLoc && (finallyEntry = null); var record = finallyEntry ? finallyEntry.completion : {}; return record.type = type, record.arg = arg, finallyEntry ? (this.method = "next", this.next = finallyEntry.finallyLoc, ContinueSentinel) : this.complete(record); }, complete: function complete(record, afterLoc) { if ("throw" === record.type) throw record.arg; return "break" === record.type || "continue" === record.type ? this.next = record.arg : "return" === record.type ? (this.rval = this.arg = record.arg, this.method = "return", this.next = "end") : "normal" === record.type && afterLoc && (this.next = afterLoc), ContinueSentinel; }, finish: function finish(finallyLoc) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.finallyLoc === finallyLoc) return this.complete(entry.completion, entry.afterLoc), resetTryEntry(entry), ContinueSentinel; } }, "catch": function _catch(tryLoc) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.tryLoc === tryLoc) { var record = entry.completion; if ("throw" === record.type) { var thrown = record.arg; resetTryEntry(entry); } return thrown; } } throw new Error("illegal catch attempt"); }, delegateYield: function delegateYield(iterable, resultName, nextLoc) { return this.delegate = { iterator: values(iterable), resultName: resultName, nextLoc: nextLoc }, "next" === this.method && (this.arg = undefined), ContinueSentinel; } }, exports; }
function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }
function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      // Core data
      folderStructure: [],
      loading: true,
      error: null,
      // UI state
      expandedFolders: {},
      selectedImage: null,
      selectedItems: [],
      // Search and filter
      searchQuery: '',
      sortBy: 'name',
      filterType: '',
      // File handling
      currentImageIndex: 0,
      // Debug
      debugLog: []
    };
  },
  computed: {
    // Filter and sort folders
    filteredFolders: function filteredFolders() {
      var folders = this.getAllFolders();
      return this.filterAndSortItems(folders);
    },
    // Filter and sort images
    filteredImages: function filteredImages() {
      var images = this.getAllImages();
      return this.filterAndSortItems(images);
    }
  },
  mounted: function mounted() {
    this.fetchImages();
    this.setupKeyboardNavigation();
  },
  methods: {
    // Core data fetching
    fetchImages: function fetchImages() {
      var _this = this;
      return _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee() {
        var response, data;
        return _regeneratorRuntime().wrap(function _callee$(_context) {
          while (1) switch (_context.prev = _context.next) {
            case 0:
              _context.prev = 0;
              _this.loading = true;
              _this.error = null;
              console.log('Fetching images...');
              _context.next = 6;
              return fetch('/api/set_multimedia/get_images_folder_structure');
            case 6:
              response = _context.sent;
              if (response.ok) {
                _context.next = 9;
                break;
              }
              throw new Error("HTTP ".concat(response.status, ": ").concat(response.statusText));
            case 9:
              _context.next = 11;
              return response.json();
            case 11:
              data = _context.sent;
              console.log('API Response received:', data);

              // Ensure response is an array
              if (!Array.isArray(data)) {
                _context.next = 18;
                break;
              }
              _this.folderStructure = data;
              console.log('Folder structure set:', _this.folderStructure);
              _context.next = 19;
              break;
            case 18:
              throw new Error('API response is not an array');
            case 19:
              _context.next = 26;
              break;
            case 21:
              _context.prev = 21;
              _context.t0 = _context["catch"](0);
              console.error('Error fetching images:', _context.t0);
              _this.error = "Failed to load media files: ".concat(_context.t0.message);
              _this.folderStructure = [];
            case 26:
              _context.prev = 26;
              _this.loading = false;
              return _context.finish(26);
            case 29:
            case "end":
              return _context.stop();
          }
        }, _callee, null, [[0, 21, 26, 29]]);
      }))();
    },
    // Folder operations
    toggleFolder: function toggleFolder(path) {
      console.log('Toggling folder:', path);
      this.expandedFolders[path] = !this.expandedFolders[path];
      console.log('Expanded folders:', this.expandedFolders);
    },
    getAllFolders: function getAllFolders() {
      var folders = [];
      this.recursiveFolderSearch(this.folderStructure, folders);
      console.log('All folders found:', folders.length);
      return folders;
    },
    recursiveFolderSearch: function recursiveFolderSearch(items, folders) {
      var _this2 = this;
      if (!Array.isArray(items)) return;
      items.forEach(function (item) {
        if (item && item.type === 'directory') {
          folders.push(item);
          if (item.children && Array.isArray(item.children)) {
            _this2.recursiveFolderSearch(item.children, folders);
          }
        }
      });
    },
    getAllImages: function getAllImages() {
      var images = [];
      this.recursiveImageSearch(this.folderStructure, images);
      console.log('All images found:', images.length);
      return images;
    },
    recursiveImageSearch: function recursiveImageSearch(items, images) {
      var _this3 = this;
      if (!Array.isArray(items)) return;
      items.forEach(function (item) {
        if (item && item.type === 'file') {
          images.push(item);
        } else if (item && item.type === 'directory' && item.children && Array.isArray(item.children)) {
          _this3.recursiveImageSearch(item.children, images);
        }
      });
    },
    getFolderContents: function getFolderContents(folder) {
      return Array.isArray(folder.children) ? folder.children : [];
    },
    getItemCount: function getItemCount(item) {
      if (item.type === 'directory') {
        var children = Array.isArray(item.children) ? item.children : [];
        var fileCount = children.filter(function (child) {
          return child.type === 'file';
        }).length;
        var folderCount = children.filter(function (child) {
          return child.type === 'directory';
        }).length;
        return fileCount + folderCount;
      }
      return 1;
    },
    // Selection management
    toggleSelection: function toggleSelection(path) {
      var index = this.selectedItems.indexOf(path);
      if (index > -1) {
        this.selectedItems.splice(index, 1);
      } else {
        this.selectedItems.push(path);
      }
    },
    // Search and filtering
    filterAndSortItems: function filterAndSortItems(items) {
      var _this4 = this;
      var filtered = items;

      // Apply search filter
      if (this.searchQuery) {
        var query = this.searchQuery.toLowerCase();
        filtered = filtered.filter(function (item) {
          return item && item.name && item.name.toLowerCase().includes(query);
        });
      }

      // Apply type filter
      if (this.filterType) {
        filtered = filtered.filter(function (item) {
          if (item.type === 'file') {
            return item.type === _this4.filterType;
          }
          return true;
        });
      }

      // Apply sorting
      return filtered.sort(function (a, b) {
        switch (_this4.sortBy) {
          case 'name':
            return (a.name || '').localeCompare(b.name || '');
          case 'date':
            return new Date(b.modified || 0) - new Date(a.modified || 0);
          case 'size':
            return (b.size || 0) - (a.size || 0);
          case 'type':
            return (a.type || '').localeCompare(b.type || '');
          default:
            return 0;
        }
      });
    },
    filterItems: function filterItems() {
      // Computed properties will automatically update
    },
    sortItems: function sortItems() {
      // Computed properties will automatically update
    },
    // Image operations
    openImage: function openImage(image) {
      console.log('Opening image:', image);
      // Open image in new tab instead of modal
      window.open(image.url, '_blank');
    },
    handleImageLoad: function handleImageLoad(event) {
      console.log('Image loaded successfully:', event.target.src);
      event.target.style.display = 'block';
    },
    handleImageError: function handleImageError(event) {
      console.error('Image failed to load:', event.target.src);
      var img = event.target;
      img.style.display = 'none';
      img.parentElement.innerHTML = '<span style="color: #666;">Failed to load</span>';
    },
    // Utility functions
    formatFileSize: function formatFileSize(bytes) {
      if (!bytes) return 'Unknown size';
      var sizes = ['Bytes', 'KB', 'MB', 'GB'];
      var i = Math.floor(Math.log(bytes) / Math.log(1024));
      return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i];
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/multimedia/XXX ArticleImagesPageFixed.vue?vue&type=template&id=b5edfa42&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/multimedia/XXX ArticleImagesPageFixed.vue?vue&type=template&id=b5edfa42& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function render() {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", [_c("div", {
    staticClass: "main-content"
  }, [_c("div", {
    staticStyle: {
      padding: "15px",
      background: "#f5f5f5",
      "margin-bottom": "10px"
    }
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.searchQuery,
      expression: "searchQuery"
    }],
    staticStyle: {
      padding: "8px",
      border: "1px solid #ddd",
      width: "300px"
    },
    attrs: {
      type: "text",
      placeholder: "Search images and folders..."
    },
    domProps: {
      value: _vm.searchQuery
    },
    on: {
      input: [function ($event) {
        if ($event.target.composing) return;
        _vm.searchQuery = $event.target.value;
      }, _vm.filterItems]
    }
  }), _vm._v(" "), _c("select", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.sortBy,
      expression: "sortBy"
    }],
    staticStyle: {
      padding: "8px",
      border: "1px solid #ddd",
      "margin-left": "10px"
    },
    on: {
      change: [function ($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
          return o.selected;
        }).map(function (o) {
          var val = "_value" in o ? o._value : o.value;
          return val;
        });
        _vm.sortBy = $event.target.multiple ? $$selectedVal : $$selectedVal[0];
      }, _vm.sortItems]
    }
  }, [_c("option", {
    attrs: {
      value: "name"
    }
  }, [_vm._v("Sort by Name")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "date"
    }
  }, [_vm._v("Sort by Date")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "size"
    }
  }, [_vm._v("Sort by Size")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "type"
    }
  }, [_vm._v("Sort by Type")])]), _vm._v(" "), _c("select", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.filterType,
      expression: "filterType"
    }],
    staticStyle: {
      padding: "8px",
      border: "1px solid #ddd",
      "margin-left": "10px"
    },
    on: {
      change: [function ($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
          return o.selected;
        }).map(function (o) {
          var val = "_value" in o ? o._value : o.value;
          return val;
        });
        _vm.filterType = $event.target.multiple ? $$selectedVal : $$selectedVal[0];
      }, _vm.filterItems]
    }
  }, [_c("option", {
    attrs: {
      value: ""
    }
  }, [_vm._v("All Files")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "jpg"
    }
  }, [_vm._v("Images")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "png"
    }
  }, [_vm._v("PNG Files")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "gif"
    }
  }, [_vm._v("GIF Files")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "svg"
    }
  }, [_vm._v("SVG Files")])])]), _vm._v(" "), _vm.loading ? _c("div", {
    staticStyle: {
      "text-align": "center",
      padding: "20px"
    }
  }, [_c("p", [_vm._v("Loading media files...")])]) : _vm.error ? _c("div", {
    staticStyle: {
      "text-align": "center",
      padding: "20px",
      color: "red"
    }
  }, [_c("p", [_vm._v(_vm._s(_vm.error))]), _vm._v(" "), _c("button", {
    staticStyle: {
      padding: "8px 16px"
    },
    on: {
      click: _vm.fetchImages
    }
  }, [_vm._v("Retry")])]) : _c("div", {
    staticStyle: {
      display: "flex",
      gap: "20px"
    }
  }, [_c("div", {
    staticStyle: {
      flex: "1"
    }
  }, [_c("h3", [_vm._v("Folders")]), _vm._v(" "), _c("div", _vm._l(_vm.filteredFolders, function (folder) {
    return _c("div", {
      key: folder.path,
      staticClass: "folder-node"
    }, [_c("div", {
      staticStyle: {
        padding: "px",
        cursor: "5 pointer",
        border: "1px solid #ddd",
        "margin-bottom": "2px"
      },
      on: {
        click: function click($event) {
          return _vm.toggleFolder(folder.path);
        }
      }
    }, [_c("input", {
      staticStyle: {
        "margin-right": "5px"
      },
      attrs: {
        type: "checkbox"
      },
      domProps: {
        checked: _vm.selectedItems.includes(folder.path)
      },
      on: {
        change: function change($event) {
          return _vm.toggleSelection(folder.path);
        },
        click: function click($event) {
          $event.stopPropagation();
        }
      }
    }), _vm._v(" "), _c("span", [_vm._v(_vm._s(_vm.expandedFolders[folder.path] ? "📂" : "📁"))]), _vm._v(" "), _c("span", {
      staticStyle: {
        "margin-left": "5px"
      }
    }, [_vm._v(_vm._s(folder.name) + " (" + _vm._s(_vm.getItemCount(folder)) + ")")])]), _vm._v(" "), _vm.expandedFolders[folder.path] ? _c("div", {
      staticStyle: {
        "margin-left": "20px"
      }
    }, _vm._l(_vm.getFolderContents(folder), function (item) {
      return _c("div", {
        key: item.path,
        staticClass: "content-item",
        "class": {
          selected: _vm.selectedItems.includes(item.path)
        }
      }, [item.type === "directory" ? _c("div", {
        staticStyle: {
          "margin-bottom": "2px"
        }
      }, [_c("div", {
        staticStyle: {
          padding: "3px",
          cursor: "pointer",
          border: "1px solid #eee"
        },
        on: {
          click: function click($event) {
            return _vm.toggleFolder(item.path);
          }
        }
      }, [_c("span", [_vm._v(_vm._s(_vm.expandedFolders[item.path] ? "📂" : "📁"))]), _vm._v(" "), _c("span", {
        staticStyle: {
          "margin-left": "5px"
        }
      }, [_vm._v(_vm._s(item.name) + " (" + _vm._s(_vm.getItemCount(item)) + ")")])]), _vm._v(" "), _vm.expandedFolders[item.path] ? _c("div", {
        staticStyle: {
          "margin-left": "15px"
        }
      }, _vm._l(_vm.getFolderContents(item), function (subItem) {
        return _c("div", {
          key: subItem.path
        }, [subItem.type === "file" ? _c("div", {
          staticStyle: {
            padding: "2px",
            cursor: "pointer"
          },
          on: {
            click: function click($event) {
              return _vm.openImage(subItem);
            }
          }
        }, [_c("input", {
          staticStyle: {
            "margin-right": "5px"
          },
          attrs: {
            type: "checkbox"
          },
          domProps: {
            checked: _vm.selectedItems.includes(subItem.path)
          },
          on: {
            change: function change($event) {
              return _vm.toggleSelection(subItem.path);
            }
          }
        }), _vm._v(" "), _c("span", [_vm._v("🖼️")]), _vm._v(" "), _c("span", {
          staticStyle: {
            "margin-left": "5px"
          }
        }, [_vm._v(_vm._s(subItem.name))])]) : _vm._e()]);
      }), 0) : _vm._e()]) : _c("div", {
        staticStyle: {
          padding: "2px",
          cursor: "pointer"
        },
        on: {
          click: function click($event) {
            return _vm.openImage(item);
          }
        }
      }, [_c("input", {
        staticStyle: {
          "margin-right": "5px"
        },
        attrs: {
          type: "checkbox"
        },
        domProps: {
          checked: _vm.selectedItems.includes(item.path)
        },
        on: {
          change: function change($event) {
            return _vm.toggleSelection(item.path);
          }
        }
      }), _vm._v(" "), _c("span", [_vm._v("🖼️")]), _vm._v(" "), _c("span", {
        staticStyle: {
          "margin-left": "5px"
        }
      }, [_vm._v(_vm._s(item.name))])])]);
    }), 0) : _vm._e()]);
  }), 0)]), _vm._v(" "), _c("div", {
    staticStyle: {
      flex: "1"
    }
  }, [_c("h3", [_vm._v("Images (" + _vm._s(_vm.filteredImages.length) + ")")]), _vm._v(" "), _c("div", {
    staticStyle: {
      display: "grid",
      "grid-template-columns": "repeat(auto-fill, minmax(150px, 1fr))",
      gap: "10px"
    }
  }, _vm._l(_vm.filteredImages, function (image) {
    return _c("div", {
      key: image.path,
      staticStyle: {
        border: "1px solid #ddd",
        padding: "5px",
        cursor: "pointer"
      },
      on: {
        click: function click($event) {
          return _vm.openImage(image);
        }
      }
    }, [_c("div", {
      staticStyle: {
        height: "100px",
        background: "#f5f5f5",
        display: "flex",
        "align-items": "center",
        "justify-content": "center",
        overflow: "hidden"
      }
    }, [_c("img", {
      staticStyle: {
        "max-width": "100%",
        "max-height": "100%",
        "object-fit": "cover",
        display: "none"
      },
      attrs: {
        src: image.url,
        alt: image.name
      },
      on: {
        error: _vm.handleImageError,
        load: _vm.handleImageLoad
      }
    }), _vm._v(" "), _c("span", {
      staticStyle: {
        color: "#666"
      }
    }, [_vm._v("Loading...")])]), _vm._v(" "), _c("div", {
      staticStyle: {
        padding: "5px",
        display: "flex",
        "align-items": "center",
        gap: "5px"
      }
    }, [_c("input", {
      attrs: {
        type: "checkbox"
      },
      domProps: {
        checked: _vm.selectedItems.includes(image.path)
      },
      on: {
        change: function change($event) {
          return _vm.toggleSelection(image.path);
        },
        click: function click($event) {
          $event.stopPropagation();
        }
      }
    }), _vm._v(" "), _c("div", [_c("div", {
      staticStyle: {
        "font-size": "12px",
        "font-weight": "bold"
      }
    }, [_vm._v(_vm._s(image.name))]), _vm._v(" "), _c("div", {
      staticStyle: {
        "font-size": "10px",
        color: "#666"
      }
    }, [_vm._v(_vm._s(_vm.formatFileSize(image.size)) + " • " + _vm._s(image.type))])])])]);
  }), 0)]), _vm._v(" "), !_vm.loading && _vm.filteredImages.length === 0 && _vm.filteredFolders.length === 0 ? _c("div", {
    staticStyle: {
      "text-align": "center",
      padding: "20px"
    }
  }, [_c("h3", [_vm._v("No files found")]), _vm._v(" "), _c("p", [_vm._v(_vm._s(_vm.searchQuery ? "Try adjusting your search or filters" : "No images or folders available"))])]) : _vm._e()])])]);
};
var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./resources/js/components/user/pages/multimedia/XXX ArticleImagesPageFixed.vue":
/*!**************************************************************************************!*\
  !*** ./resources/js/components/user/pages/multimedia/XXX ArticleImagesPageFixed.vue ***!
  \**************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _XXX_ArticleImagesPageFixed_vue_vue_type_template_id_b5edfa42___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./XXX ArticleImagesPageFixed.vue?vue&type=template&id=b5edfa42& */ "./resources/js/components/user/pages/multimedia/XXX ArticleImagesPageFixed.vue?vue&type=template&id=b5edfa42&");
/* harmony import */ var _XXX_ArticleImagesPageFixed_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./XXX ArticleImagesPageFixed.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/multimedia/XXX ArticleImagesPageFixed.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _XXX_ArticleImagesPageFixed_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _XXX_ArticleImagesPageFixed_vue_vue_type_template_id_b5edfa42___WEBPACK_IMPORTED_MODULE_0__.render,
  _XXX_ArticleImagesPageFixed_vue_vue_type_template_id_b5edfa42___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/multimedia/XXX ArticleImagesPageFixed.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/multimedia/XXX ArticleImagesPageFixed.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/multimedia/XXX ArticleImagesPageFixed.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_XXX_ArticleImagesPageFixed_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./XXX ArticleImagesPageFixed.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/multimedia/XXX ArticleImagesPageFixed.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_XXX_ArticleImagesPageFixed_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/multimedia/XXX ArticleImagesPageFixed.vue?vue&type=template&id=b5edfa42&":
/*!*********************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/multimedia/XXX ArticleImagesPageFixed.vue?vue&type=template&id=b5edfa42& ***!
  \*********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_XXX_ArticleImagesPageFixed_vue_vue_type_template_id_b5edfa42___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_XXX_ArticleImagesPageFixed_vue_vue_type_template_id_b5edfa42___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_XXX_ArticleImagesPageFixed_vue_vue_type_template_id_b5edfa42___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./XXX ArticleImagesPageFixed.vue?vue&type=template&id=b5edfa42& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/multimedia/XXX ArticleImagesPageFixed.vue?vue&type=template&id=b5edfa42&");


/***/ })

}]);