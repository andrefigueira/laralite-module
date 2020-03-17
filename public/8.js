(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[8],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _helpers__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../helpers */ "./resources/js/helpers.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  name: 'TrapMusicParralaxComponent',
  mounted: function mounted() {
    console.log('Component mounted.');
    var st, lastScrollTop;
    $(window).scroll(function (e) {
      var parralaxImage1 = $('.parralax-image-1');
      var parralaxImage2 = $('.parralax-image-2');
      var parralaxImage3 = $('.parralax-image-3');
      st = $(this).scrollTop();

      if (st < lastScrollTop) {
        console.log('scrolling up');
        parralaxImage1.css({
          right: '-=100'
        });
        parralaxImage2.css({
          left: '-=100'
        });
        parralaxImage3.css({
          right: '-=100'
        });
      } else {
        console.log('scrolling down');
        parralaxImage1.css({
          right: '+=100'
        });
        parralaxImage2.css({
          left: '+=100'
        });
        parralaxImage3.css({
          right: '+=100'
        });
      }

      lastScrollTop = st;
    });
  },
  props: {
    sections: {}
  },
  data: function data() {
    return {};
  },
  methods: {}
});

/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue?vue&type=style&index=0&lang=scss&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--8-2!./node_modules/sass-loader/dist/cjs.js??ref--8-3!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue?vue&type=style&index=0&lang=scss& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, ".hr {\n  border-bottom: 1px solid #D9D9D9;\n}\n.h2 {\n  font-size: 1.5rem !important;\n  margin: 0 !important;\n}\n.table-top-border-0 tr th {\n  border-top: none;\n}\ndiv.table {\n  padding: 1rem;\n}\ndiv.table .table-header {\n  font-weight: bold;\n  padding-bottom: 1rem;\n  border-bottom: 1px solid #DDD;\n}\ndiv.table .table-row {\n  padding-top: 0.7rem;\n  padding-bottom: 0.3rem;\n  border-bottom: 1px solid #CCC;\n}\n.page-section {\n  border-radius: 4px;\n  background: #FFF;\n  box-shadow: 0 1px 2px #D9D9D9;\n  border: 1px solid #E9E9E9;\n}\n.font-size-0 {\n  font-size: 1rem;\n}\n.font-size-1 {\n  font-size: 2rem;\n}\n.font-size-2 {\n  font-size: 3rem;\n}\n.font-size-3 {\n  font-size: 4rem;\n}\n.font-size-4 {\n  font-size: 5rem;\n}\n.image-section {\n  padding-left: 3rem !important;\n  padding-right: 3rem !important;\n}\n.image-section blockquote {\n  display: inline;\n  text-transform: uppercase;\n  background-color: rgba(0, 0, 0, 0.5);\n  color: #FFF;\n  padding: 0.2rem;\n  margin: auto;\n  font-size: 1.5rem;\n  font-family: \"Raleway\", sans-serif;\n}\n.image-section .source {\n  display: block;\n  text-transform: uppercase;\n  color: #FFF;\n  font-size: 2rem;\n  font-family: \"Raleway\", sans-serif;\n}\n.image-section .image-section-title {\n  color: #FFF;\n  text-transform: uppercase;\n  text-align: left;\n  font-family: \"Oswald\", sans-serif;\n}\n.image-section .image-section-title .highlight-text {\n  display: block;\n  font-size: 5rem;\n}\n.image-section .image-section-subtitle {\n  color: #FFF;\n  text-transform: uppercase;\n  text-align: left;\n  font-family: \"Oswald\", sans-serif;\n}\n.image-section .image-section-supplemental-title {\n  color: #FFF;\n  text-transform: uppercase;\n  text-align: right;\n  font-size: 4rem;\n  font-family: \"Oswald\", sans-serif;\n}\n.image-section .image-section-supplemental-content {\n  color: #FFF;\n  text-transform: uppercase;\n  text-align: right;\n  font-size: 2rem;\n  font-family: \"Oswald\", sans-serif;\n}\n.parralax-image-1 {\n  right: -300px;\n  transition: all ease 1s;\n}\n.parralax-image-2 {\n  left: -300px;\n  transition: all ease 1s;\n}\n.parralax-image-3 {\n  right: -300px;\n  transition: all ease 1s;\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue?vue&type=style&index=0&lang=scss&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--8-2!./node_modules/sass-loader/dist/cjs.js??ref--8-3!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue?vue&type=style&index=0&lang=scss& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--8-2!../../../../node_modules/sass-loader/dist/cjs.js??ref--8-3!../../../../node_modules/vue-loader/lib??vue-loader-options!./TrapMusicParralaxComponent.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue?vue&type=style&index=0&lang=scss&");

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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue?vue&type=template&id=0b74301e&":
/*!************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue?vue&type=template&id=0b74301e& ***!
  \************************************************************************************************************************************************************************************************************************************************/
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
      _c("div", { staticClass: "row" }, [
        _c("div", { staticClass: "col-md-12" }, [
          _c("img", {
            staticClass: "logo",
            attrs: { src: "/images/trap-music-museum-logo.png", alt: "" }
          }),
          _vm._v(" "),
          _c("div", { staticClass: "row mb-5" }, [
            _c("div", { staticClass: "col-md-2 offset-5" }, [
              _c(
                "a",
                {
                  staticClass: "btn btn-secondary call-to-action",
                  attrs: { href: "" }
                },
                [
                  _vm._v("Purchase tickets "),
                  _c("i", { staticClass: "fas fa-chevron-right" })
                ]
              ),
              _vm._v(" "),
              _c(
                "a",
                {
                  staticClass: "btn btn-secondary call-to-action",
                  attrs: { href: "" }
                },
                [
                  _vm._v("Escape room "),
                  _c("i", { staticClass: "fas fa-chevron-right" })
                ]
              )
            ])
          ])
        ])
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "spacer", staticStyle: { height: "30rem" } }),
      _vm._v(" "),
      _c("div", { staticClass: "row" }, [
        _c("div", { staticClass: "col-md-12 image-section image-section-1" }, [
          _c("h3", { staticClass: "image-section-title" }, [
            _vm._v(
              "\n                The Trap Music Museum isn't just about music but also about the culture that "
            ),
            _c("span", { staticClass: "highlight-text" }, [_vm._v("Inspires")])
          ]),
          _vm._v(" "),
          _c("h4", { staticClass: "image-section-subtitle" }, [
            _vm._v("- The Source")
          ]),
          _vm._v(" "),
          _c("img", {
            staticClass: "parralax-image-1",
            staticStyle: {
              position: "absolute",
              height: "50rem",
              width: "auto"
            },
            attrs: {
              src: "/images/parralax-image-1.png",
              alt: "Parralex Image 1"
            }
          }),
          _vm._v(" "),
          _c("div", {
            staticClass: "spacer",
            staticStyle: { height: "52rem" }
          }),
          _vm._v(" "),
          _c("img", {
            staticClass: "parralax-image-2",
            staticStyle: {
              position: "absolute",
              height: "70rem",
              width: "auto"
            },
            attrs: {
              src: "/images/parralax-image-2.png",
              alt: "Parralex Image 2"
            }
          }),
          _vm._v(" "),
          _c("img", {
            staticClass: "parralax-image-3",
            staticStyle: {
              position: "absolute",
              margin: "20rem 0 0 0",
              height: "50rem",
              width: "auto"
            },
            attrs: {
              src: "/images/parralax-image-3.png",
              alt: "Parralex Image 3"
            }
          }),
          _vm._v(" "),
          _c("h3", { staticClass: "image-section-supplemental-title" }, [
            _vm._v("MUSEUM HOURS")
          ]),
          _vm._v(" "),
          _c("p", { staticClass: "image-section-supplemental-content" }, [
            _vm._v("\n                Thursday (HAPPY HOUR)  4pm-10pm"),
            _c("br"),
            _vm._v("\n                FRIDAY 4pm - 12AM"),
            _c("br"),
            _vm._v("\n                Saturday 12pm - 12am"),
            _c("br"),
            _vm._v("\n                Sunday 2pm -10pm"),
            _c("br"),
            _vm._v(" "),
            _c(
              "a",
              { staticClass: "btn btn-outline-danger", attrs: { href: "/" } },
              [
                _vm._v("More Information "),
                _c("i", { staticClass: "fas fa-chevron-right" })
              ]
            )
          ])
        ])
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "spacer", staticStyle: { height: "52rem" } }),
      _vm._v(" "),
      _c("div", { staticClass: "row" }, [
        _c("div", { staticClass: "col-md-12 image-section" }, [
          _c("h3", { staticClass: "image-section-title font-size-3" }, [
            _vm._v("\n                ESCAPE ROOM HOURS\n            ")
          ]),
          _vm._v(" "),
          _c("h4", { staticClass: "image-section-subtitle font-size-1" }, [
            _vm._v("\n                Open 7 Days a week"),
            _c("br"),
            _vm._v("\n                Click here to book\n            ")
          ])
        ])
      ])
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue":
/*!***********************************************************************************!*\
  !*** ./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue ***!
  \***********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _TrapMusicParralaxComponent_vue_vue_type_template_id_0b74301e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./TrapMusicParralaxComponent.vue?vue&type=template&id=0b74301e& */ "./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue?vue&type=template&id=0b74301e&");
/* harmony import */ var _TrapMusicParralaxComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./TrapMusicParralaxComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _TrapMusicParralaxComponent_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./TrapMusicParralaxComponent.vue?vue&type=style&index=0&lang=scss& */ "./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _TrapMusicParralaxComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _TrapMusicParralaxComponent_vue_vue_type_template_id_0b74301e___WEBPACK_IMPORTED_MODULE_0__["render"],
  _TrapMusicParralaxComponent_vue_vue_type_template_id_0b74301e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************!*\
  !*** ./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TrapMusicParralaxComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./TrapMusicParralaxComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TrapMusicParralaxComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue?vue&type=style&index=0&lang=scss&":
/*!*********************************************************************************************************************!*\
  !*** ./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue?vue&type=style&index=0&lang=scss& ***!
  \*********************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_TrapMusicParralaxComponent_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader!../../../../node_modules/css-loader!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--8-2!../../../../node_modules/sass-loader/dist/cjs.js??ref--8-3!../../../../node_modules/vue-loader/lib??vue-loader-options!./TrapMusicParralaxComponent.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_TrapMusicParralaxComponent_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_TrapMusicParralaxComponent_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_TrapMusicParralaxComponent_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_TrapMusicParralaxComponent_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_TrapMusicParralaxComponent_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue?vue&type=template&id=0b74301e&":
/*!******************************************************************************************************************!*\
  !*** ./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue?vue&type=template&id=0b74301e& ***!
  \******************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TrapMusicParralaxComponent_vue_vue_type_template_id_0b74301e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./TrapMusicParralaxComponent.vue?vue&type=template&id=0b74301e& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/FrontEndComponents/TrapMusicParralaxComponent.vue?vue&type=template&id=0b74301e&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TrapMusicParralaxComponent_vue_vue_type_template_id_0b74301e___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TrapMusicParralaxComponent_vue_vue_type_template_id_0b74301e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);