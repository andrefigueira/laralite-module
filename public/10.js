(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[10],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/PagesComponent.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/PagesComponent.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  mounted: function mounted() {
    console.log('Component mounted.');
    this.load();
  },
  data: function data() {
    return {
      loading: true,
      showResults: false,
      pages: []
    };
  },
  methods: {
    load: function load() {
      var _this = this;

      axios.get('/api/page?with=children').then(function (response) {
        _this.pages = response.data.data;

        if (_this.pages.length > 0) {
          _this.showResults = true;
        }

        _this.loading = false;
      })["catch"](function (error) {// handle error
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/PagesComponent.vue?vue&type=template&id=0fee7f78&":
/*!*****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/PagesComponent.vue?vue&type=template&id=0fee7f78& ***!
  \*****************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    [
      _c(
        "b-alert",
        {
          staticClass: "m-3",
          attrs: { show: !_vm.loading && !_vm.showResults }
        },
        [
          _vm._v("No pages added yet · "),
          _c("a", { attrs: { href: "/admin/pages/create" } }, [
            _vm._v("Create one")
          ])
        ]
      ),
      _vm._v(" "),
      _c(
        "div",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value: _vm.loading,
              expression: "loading"
            }
          ],
          staticClass: "text-center"
        },
        [_c("b-spinner", { attrs: { label: "Spinning" } })],
        1
      ),
      _vm._v(" "),
      _c(
        "div",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value: _vm.showResults,
              expression: "showResults"
            }
          ],
          staticClass: "table table-top-border-0"
        },
        [
          _vm._m(0),
          _vm._v(" "),
          _c("recursive-table-row", { attrs: { data: _vm.pages } })
        ],
        1
      )
    ],
    1
  )
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "row table-header" }, [
      _c("div", { staticClass: "col-md-3" }, [_vm._v("Name")]),
      _vm._v(" "),
      _c("div", { staticClass: "col-md-3" }, [_vm._v("Slug")]),
      _vm._v(" "),
      _c("div", { staticClass: "col-md-2" }, [_vm._v("Type")]),
      _vm._v(" "),
      _c("div", { staticClass: "col-md-2" }, [_vm._v("Template")]),
      _vm._v(" "),
      _c("div", { staticClass: "col-md-2" })
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/PagesComponent.vue":
/*!****************************************************!*\
  !*** ./resources/js/components/PagesComponent.vue ***!
  \****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _PagesComponent_vue_vue_type_template_id_0fee7f78___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./PagesComponent.vue?vue&type=template&id=0fee7f78& */ "./resources/js/components/PagesComponent.vue?vue&type=template&id=0fee7f78&");
/* harmony import */ var _PagesComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./PagesComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/PagesComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _PagesComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _PagesComponent_vue_vue_type_template_id_0fee7f78___WEBPACK_IMPORTED_MODULE_0__["render"],
  _PagesComponent_vue_vue_type_template_id_0fee7f78___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/PagesComponent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/PagesComponent.vue?vue&type=script&lang=js&":
/*!*****************************************************************************!*\
  !*** ./resources/js/components/PagesComponent.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PagesComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./PagesComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/PagesComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PagesComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/PagesComponent.vue?vue&type=template&id=0fee7f78&":
/*!***********************************************************************************!*\
  !*** ./resources/js/components/PagesComponent.vue?vue&type=template&id=0fee7f78& ***!
  \***********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PagesComponent_vue_vue_type_template_id_0fee7f78___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./PagesComponent.vue?vue&type=template&id=0fee7f78& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/PagesComponent.vue?vue&type=template&id=0fee7f78&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PagesComponent_vue_vue_type_template_id_0fee7f78___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PagesComponent_vue_vue_type_template_id_0fee7f78___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);