(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[1],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/FrontEndComponents/CarouselComponent2.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/FrontEndComponents/CarouselComponent2.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  name: 'CarouselComponent',
  mounted: function mounted() {
    console.log('Component mounted.');
  },
  props: {}
});

/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/FrontEndComponents/CarouselComponent2.vue?vue&type=style&index=0&lang=scss&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--9-2!./node_modules/sass-loader/dist/cjs.js??ref--9-3!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/FrontEndComponents/CarouselComponent2.vue?vue&type=style&index=0&lang=scss& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "/* Carousel base class */\n/* Since positioning the image, we need to help out the caption */\n.carousel-caption {\n  bottom: 3rem;\n  z-index: 10;\n}\n\n/* Declare heights because of positioning of img element */\n.carousel-item {\n  height: 32rem;\n  background-color: #777;\n}\n.carousel-item > img {\n  position: absolute;\n  top: 0;\n  left: 0;\n  min-width: 100%;\n  height: 32rem;\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/FrontEndComponents/CarouselComponent2.vue?vue&type=style&index=0&lang=scss&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--9-2!./node_modules/sass-loader/dist/cjs.js??ref--9-3!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/FrontEndComponents/CarouselComponent2.vue?vue&type=style&index=0&lang=scss& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--9-2!../../../../node_modules/sass-loader/dist/cjs.js??ref--9-3!../../../../node_modules/vue-loader/lib??vue-loader-options!./CarouselComponent2.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/FrontEndComponents/CarouselComponent2.vue?vue&type=style&index=0&lang=scss&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/FrontEndComponents/CarouselComponent2.vue?vue&type=template&id=3f44043e&":
/*!****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/FrontEndComponents/CarouselComponent2.vue?vue&type=template&id=3f44043e& ***!
  \****************************************************************************************************************************************************************************************************************************************/
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
  return _vm._m(0)
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", [
      _c(
        "div",
        {
          staticClass: "carousel slide",
          attrs: { id: "myCarousel", "data-ride": "carousel" }
        },
        [
          _c("ol", { staticClass: "carousel-indicators" }, [
            _c("li", {
              staticClass: "active",
              attrs: { "data-target": "#myCarousel", "data-slide-to": "0" }
            }),
            _vm._v(" "),
            _c("li", {
              attrs: { "data-target": "#myCarousel", "data-slide-to": "1" }
            }),
            _vm._v(" "),
            _c("li", {
              attrs: { "data-target": "#myCarousel", "data-slide-to": "2" }
            })
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "carousel-inner" }, [
            _c("div", { staticClass: "carousel-item active" }, [
              _c("img", {
                staticClass: "first-slide",
                attrs: {
                  src:
                    "data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==",
                  alt: "First slide"
                }
              }),
              _vm._v(" "),
              _c("div", { staticClass: "container" }, [
                _c("div", { staticClass: "carousel-caption text-left" }, [
                  _c("h1", [_vm._v("Example headline.")]),
                  _vm._v(" "),
                  _c("p", [
                    _vm._v(
                      "Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit."
                    )
                  ]),
                  _vm._v(" "),
                  _c("p", [
                    _c(
                      "a",
                      {
                        staticClass: "btn btn-lg btn-primary",
                        attrs: { href: "#", role: "button" }
                      },
                      [_vm._v("Sign up today")]
                    )
                  ])
                ])
              ])
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "carousel-item" }, [
              _c("img", {
                staticClass: "second-slide",
                attrs: {
                  src:
                    "data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==",
                  alt: "Second slide"
                }
              }),
              _vm._v(" "),
              _c("div", { staticClass: "container" }, [
                _c("div", { staticClass: "carousel-caption" }, [
                  _c("h1", [_vm._v("Another example headline.")]),
                  _vm._v(" "),
                  _c("p", [
                    _vm._v(
                      "Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit."
                    )
                  ]),
                  _vm._v(" "),
                  _c("p", [
                    _c(
                      "a",
                      {
                        staticClass: "btn btn-lg btn-primary",
                        attrs: { href: "#", role: "button" }
                      },
                      [_vm._v("Learn more")]
                    )
                  ])
                ])
              ])
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "carousel-item" }, [
              _c("img", {
                staticClass: "third-slide",
                attrs: {
                  src:
                    "data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==",
                  alt: "Third slide"
                }
              }),
              _vm._v(" "),
              _c("div", { staticClass: "container" }, [
                _c("div", { staticClass: "carousel-caption text-right" }, [
                  _c("h1", [_vm._v("One more for good measure.")]),
                  _vm._v(" "),
                  _c("p", [
                    _vm._v(
                      "Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit."
                    )
                  ]),
                  _vm._v(" "),
                  _c("p", [
                    _c(
                      "a",
                      {
                        staticClass: "btn btn-lg btn-primary",
                        attrs: { href: "#", role: "button" }
                      },
                      [_vm._v("Browse gallery")]
                    )
                  ])
                ])
              ])
            ])
          ]),
          _vm._v(" "),
          _c(
            "a",
            {
              staticClass: "carousel-control-prev",
              attrs: {
                href: "#myCarousel",
                role: "button",
                "data-slide": "prev"
              }
            },
            [
              _c("span", {
                staticClass: "carousel-control-prev-icon",
                attrs: { "aria-hidden": "true" }
              }),
              _vm._v(" "),
              _c("span", { staticClass: "sr-only" }, [_vm._v("Previous")])
            ]
          ),
          _vm._v(" "),
          _c(
            "a",
            {
              staticClass: "carousel-control-next",
              attrs: {
                href: "#myCarousel",
                role: "button",
                "data-slide": "next"
              }
            },
            [
              _c("span", {
                staticClass: "carousel-control-next-icon",
                attrs: { "aria-hidden": "true" }
              }),
              _vm._v(" "),
              _c("span", { staticClass: "sr-only" }, [_vm._v("Next")])
            ]
          )
        ]
      )
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/FrontEndComponents/CarouselComponent2.vue":
/*!***************************************************************************!*\
  !*** ./resources/js/components/FrontEndComponents/CarouselComponent2.vue ***!
  \***************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _CarouselComponent2_vue_vue_type_template_id_3f44043e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./CarouselComponent2.vue?vue&type=template&id=3f44043e& */ "./resources/js/components/FrontEndComponents/CarouselComponent2.vue?vue&type=template&id=3f44043e&");
/* harmony import */ var _CarouselComponent2_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./CarouselComponent2.vue?vue&type=script&lang=js& */ "./resources/js/components/FrontEndComponents/CarouselComponent2.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _CarouselComponent2_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./CarouselComponent2.vue?vue&type=style&index=0&lang=scss& */ "./resources/js/components/FrontEndComponents/CarouselComponent2.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _CarouselComponent2_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _CarouselComponent2_vue_vue_type_template_id_3f44043e___WEBPACK_IMPORTED_MODULE_0__["render"],
  _CarouselComponent2_vue_vue_type_template_id_3f44043e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/FrontEndComponents/CarouselComponent2.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/FrontEndComponents/CarouselComponent2.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************!*\
  !*** ./resources/js/components/FrontEndComponents/CarouselComponent2.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CarouselComponent2_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./CarouselComponent2.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/FrontEndComponents/CarouselComponent2.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CarouselComponent2_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/FrontEndComponents/CarouselComponent2.vue?vue&type=style&index=0&lang=scss&":
/*!*************************************************************************************************************!*\
  !*** ./resources/js/components/FrontEndComponents/CarouselComponent2.vue?vue&type=style&index=0&lang=scss& ***!
  \*************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_sass_loader_dist_cjs_js_ref_9_3_node_modules_vue_loader_lib_index_js_vue_loader_options_CarouselComponent2_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader!../../../../node_modules/css-loader!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--9-2!../../../../node_modules/sass-loader/dist/cjs.js??ref--9-3!../../../../node_modules/vue-loader/lib??vue-loader-options!./CarouselComponent2.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/FrontEndComponents/CarouselComponent2.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_sass_loader_dist_cjs_js_ref_9_3_node_modules_vue_loader_lib_index_js_vue_loader_options_CarouselComponent2_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_sass_loader_dist_cjs_js_ref_9_3_node_modules_vue_loader_lib_index_js_vue_loader_options_CarouselComponent2_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_sass_loader_dist_cjs_js_ref_9_3_node_modules_vue_loader_lib_index_js_vue_loader_options_CarouselComponent2_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_sass_loader_dist_cjs_js_ref_9_3_node_modules_vue_loader_lib_index_js_vue_loader_options_CarouselComponent2_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_sass_loader_dist_cjs_js_ref_9_3_node_modules_vue_loader_lib_index_js_vue_loader_options_CarouselComponent2_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/components/FrontEndComponents/CarouselComponent2.vue?vue&type=template&id=3f44043e&":
/*!**********************************************************************************************************!*\
  !*** ./resources/js/components/FrontEndComponents/CarouselComponent2.vue?vue&type=template&id=3f44043e& ***!
  \**********************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CarouselComponent2_vue_vue_type_template_id_3f44043e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./CarouselComponent2.vue?vue&type=template&id=3f44043e& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/FrontEndComponents/CarouselComponent2.vue?vue&type=template&id=3f44043e&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CarouselComponent2_vue_vue_type_template_id_3f44043e___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CarouselComponent2_vue_vue_type_template_id_3f44043e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);