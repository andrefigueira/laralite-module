(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[13],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/TemplatesComponent.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/TemplatesComponent.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************/
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
      templates: []
    };
  },
  methods: {
    load: function load() {
      var _this = this;

      axios.get('/api/template').then(function (response) {
        _this.templates = response.data.data;

        if (_this.templates.length > 0) {
          _this.showResults = true;
        }

        _this.loading = false;
      })["catch"](function (error) {// handle error
      });
    },
    confirmDelete: function confirmDelete(template) {
      var _this2 = this;

      this.$bvModal.msgBoxConfirm('Are you sure?').then(function (value) {
        if (value) {
          var index = _this2.templates.indexOf(template);

          var self = _this2;
          axios["delete"]('/api/template/' + template.id).then(function (response) {
            self.templates.splice(index, 1);

            if (self.templates.length < 1) {
              self.showResults = false;
            }
          })["catch"](function (error) {// handle error
          });
        }
      })["catch"](function (error) {// An error occurred
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/TemplatesComponent.vue?vue&type=template&id=4a09716f&":
/*!*********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/TemplatesComponent.vue?vue&type=template&id=4a09716f& ***!
  \*********************************************************************************************************************************************************************************************************************/
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
          _vm._v("No templates added yet · "),
          _c("a", { attrs: { href: "/admin/templates/create" } }, [
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
        "table",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value: _vm.showResults,
              expression: "showResults"
            }
          ],
          staticClass: "table"
        },
        [
          _vm._m(0),
          _vm._v(" "),
          _vm._l(_vm.templates, function(template) {
            return _c("tr", [
              _c("td", [_vm._v(_vm._s(template.name))]),
              _vm._v(" "),
              _c("td", [_vm._v(_vm._s(template.description))]),
              _vm._v(" "),
              _c(
                "td",
                [
                  _c(
                    "b-button",
                    {
                      staticClass: "float-right",
                      attrs: { variant: "danger", size: "sm" },
                      on: {
                        click: function($event) {
                          return _vm.confirmDelete(template)
                        }
                      }
                    },
                    [_vm._v("Delete")]
                  ),
                  _vm._v(" "),
                  _c(
                    "a",
                    {
                      staticClass: "btn btn-sm btn-primary float-right mr-1",
                      attrs: { href: "/admin/templates/edit/" + template.id }
                    },
                    [_vm._v("Edit")]
                  )
                ],
                1
              )
            ])
          })
        ],
        2
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
    return _c("tr", [
      _c("th", [_vm._v("Name")]),
      _vm._v(" "),
      _c("th", [_vm._v("Description")]),
      _vm._v(" "),
      _c("th")
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/TemplatesComponent.vue":
/*!********************************************************!*\
  !*** ./resources/js/components/TemplatesComponent.vue ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _TemplatesComponent_vue_vue_type_template_id_4a09716f___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./TemplatesComponent.vue?vue&type=template&id=4a09716f& */ "./resources/js/components/TemplatesComponent.vue?vue&type=template&id=4a09716f&");
/* harmony import */ var _TemplatesComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./TemplatesComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/TemplatesComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _TemplatesComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _TemplatesComponent_vue_vue_type_template_id_4a09716f___WEBPACK_IMPORTED_MODULE_0__["render"],
  _TemplatesComponent_vue_vue_type_template_id_4a09716f___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/TemplatesComponent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/TemplatesComponent.vue?vue&type=script&lang=js&":
/*!*********************************************************************************!*\
  !*** ./resources/js/components/TemplatesComponent.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TemplatesComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./TemplatesComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/TemplatesComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TemplatesComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/TemplatesComponent.vue?vue&type=template&id=4a09716f&":
/*!***************************************************************************************!*\
  !*** ./resources/js/components/TemplatesComponent.vue?vue&type=template&id=4a09716f& ***!
  \***************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TemplatesComponent_vue_vue_type_template_id_4a09716f___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./TemplatesComponent.vue?vue&type=template&id=4a09716f& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/TemplatesComponent.vue?vue&type=template&id=4a09716f&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TemplatesComponent_vue_vue_type_template_id_4a09716f___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TemplatesComponent_vue_vue_type_template_id_4a09716f___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);