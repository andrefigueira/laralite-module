(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[0],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/AdminPageComponents/AccordionComponent.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/AdminPageComponents/AccordionComponent.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ckeditor_ckeditor5_build_classic__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @ckeditor/ckeditor5-build-classic */ "./node_modules/@ckeditor/ckeditor5-build-classic/build/ckeditor.js");
/* harmony import */ var _ckeditor_ckeditor5_build_classic__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_ckeditor_ckeditor5_build_classic__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _app__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../app */ "./resources/js/app.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  },
  props: {
    value: {},
    id: {
      type: String,
      "default": ''
    }
  },
  methods: {
    addAccordionSection: function addAccordionSection() {
      if (this.value.sections === undefined) {
        this.value.sections = [];
      }

      this.value.sections.push({
        title: this.title,
        content: this.editorData
      });
      this.title = '';
      this.editorData = '';
      this.$emit('input', {
        sections: this.value.sections
      });
    },
    removeSection: function removeSection(section) {
      var index = this.value.sections.indexOf(section);
      this.value.sections.splice(index, 1);
    }
  },
  data: function data() {
    return {
      title: '',
      editor: _ckeditor_ckeditor5_build_classic__WEBPACK_IMPORTED_MODULE_0___default.a,
      editorData: '',
      editorConfig: {}
    };
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/AdminPageComponents/ContentComponent.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/AdminPageComponents/ContentComponent.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ckeditor_ckeditor5_build_classic__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @ckeditor/ckeditor5-build-classic */ "./node_modules/@ckeditor/ckeditor5-build-classic/build/ckeditor.js");
/* harmony import */ var _ckeditor_ckeditor5_build_classic__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_ckeditor_ckeditor5_build_classic__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _app__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../app */ "./resources/js/app.js");
//
//
//
//
//
//


/* harmony default export */ __webpack_exports__["default"] = ({
  mounted: function mounted() {
    console.log('Component mounted.');
  },
  props: {
    value: {
      type: Object,
      "default": {}
    },
    id: {
      type: String,
      "default": ''
    }
  },
  methods: {
    onChange: function onChange(data) {
      _app__WEBPACK_IMPORTED_MODULE_1__["bus"].$emit(this.id + '-admin-content-component-change', {
        componentId: this.id,
        properties: {
          editorData: data
        }
      });
      this.$emit('input', {
        editorData: data
      });
    }
  },
  data: function data() {
    return {
      editor: _ckeditor_ckeditor5_build_classic__WEBPACK_IMPORTED_MODULE_0___default.a,
      editorData: '',
      editorConfig: {}
    };
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/PageComponents.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/PageComponents.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _app__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../app */ "./resources/js/app.js");
/* harmony import */ var _helpers__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../helpers */ "./resources/js/helpers.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  props: ['value', 'template'],
  data: function data() {
    return {
      options: [],
      sectionOptions: [],
      component: {},
      section: {},
      components: []
    };
  },
  watch: {
    value: function value() {
      if (this.value !== undefined) {
        this.components = this.value;
      }
    },
    template: function template() {
      if (this.template.sections !== undefined) {
        this.section = this.template.sections[0];
        this.sectionOptions = this.template.sections;
      }
    }
  },
  methods: {
    load: function load() {
      var _this = this;

      axios.get('/api/component').then(function (response) {
        _this.options = response.data;
        _this.component = _this.options[0];
      })["catch"](function (error) {// handle error
      });
    },
    removeComponent: function removeComponent(component) {
      var _this2 = this;

      this.$bvModal.msgBoxConfirm('Are you sure?').then(function (value) {
        if (value) {
          var index = _this2.components.indexOf(component);

          _this2.components.splice(index, 1);
        }
      })["catch"](function (error) {// An error occurred
      });
    },
    addComponent: function addComponent() {
      var _this3 = this;

      if (this.component.name === undefined) {
        alert('Select a component first!');
        return;
      }

      var componentId = _helpers__WEBPACK_IMPORTED_MODULE_1__["default"].uuidv4();
      var componentName = 'admin-' + this.component.name.toLowerCase() + '-component';
      var componentIndex = this.components.push({
        id: componentId,
        name: componentName,
        section: this.section.slug,
        frontendName: this.component.name.toLowerCase() + '-component',
        properties: {}
      });
      componentIndex -= 1;
      _app__WEBPACK_IMPORTED_MODULE_0__["bus"].$on(componentId + '-' + componentName + '-change', function (response) {
        _this3.components[componentIndex].properties = response.properties;

        _this3.$emit('input', _this3.components);
      });
    }
  }
});

/***/ }),

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

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/PagesFormComponent.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/PagesFormComponent.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _app__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../app */ "./resources/js/app.js");
/* harmony import */ var _helpers__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../helpers */ "./resources/js/helpers.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  props: {
    type: {
      type: String,
      "default": 'create'
    },
    page: {
      type: Object,
      "default": {}
    }
  },
  data: function data() {
    return {
      saving: false,
      alertShow: false,
      alertType: 'primary',
      alertMessage: '',
      primaryOptions: [{
        title: 'Primary',
        value: 1
      }, {
        title: 'Standard',
        value: 0
      }],
      primary: {
        title: 'Standard',
        value: 0
      },
      pages: [],
      template: {},
      templates: [],
      id: '',
      parent: {},
      name: '',
      slug: '',
      components: {},
      meta: {
        title: '',
        keywords: '',
        author: '',
        description: ''
      }
    };
  },
  computed: {
    button: function button() {
      if (this.type === 'create') {
        return 'Create';
      }

      return 'Save changes';
    },
    formEndpoint: function formEndpoint() {
      var endpoint = '/api/page';

      if (this.type === 'edit') {
        endpoint = '/api/page/' + this.page.id;
      }

      return endpoint;
    },
    formMethod: function formMethod() {
      var method = 'post';

      if (this.type === 'edit') {
        method = 'patch';
      }

      return method;
    }
  },
  watch: {
    slug: function slug() {
      if (this.slug !== '') {
        var lastPart = this.slug.split("/").pop();
        this.slug = '/' + lastPart;
      }
    }
  },
  methods: {
    loadParentOptions: function loadParentOptions(defaultOption) {
      var _this = this;

      axios.get('/api/page').then(function (response) {
        _this.pages = response.data.data;

        _this.pages.unshift(defaultOption);

        if (_this.page.id !== undefined) {
          _this.parent = _this.pages.filter(function (parentPage) {
            return parentPage.id === _this.page.parent_id;
          })[0];
          _this.pages = _this.pages.filter(function (result) {
            return result.id !== _this.page.id;
          });
        }
      })["catch"](function (error) {// handle error
      });
    },
    loadDefaultFormValues: function loadDefaultFormValues(defaultParentValue) {
      var _this2 = this;

      if (this.page.id === undefined) {
        this.parent = defaultParentValue;
      } else {
        this.id = this.page.id;
        this.primary = this.primaryOptions.filter(function (option) {
          return option.value === _this2.page.primary;
        })[0];
        this.name = this.page.name;
        this.slug = this.page.slug;
        this.components = this.page.components;
        this.meta = this.page.meta;
      }
    },
    loadTemplateOptions: function loadTemplateOptions() {
      var _this3 = this;

      axios.get('/api/template').then(function (response) {
        _this3.templates = response.data.data;

        if (_this3.page.template_id === undefined) {
          _this3.template = _this3.templates[0];
        } else {
          _this3.template = _this3.templates.filter(function (pageTemplate) {
            return pageTemplate.id === _this3.page.template_id;
          })[0];
        }
      })["catch"](function (error) {// handle error
      });
    },
    load: function load() {
      var defaultParentValue = {
        id: null,
        name: 'No parent'
      };
      this.loadParentOptions(defaultParentValue);
      this.loadTemplateOptions();
      this.loadDefaultFormValues(defaultParentValue);
    },
    generateSlug: function generateSlug() {
      this.slug = '/' + this.name.toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
    },
    save: function save() {
      var _this4 = this;

      this.saving = true;
      axios({
        method: this.formMethod,
        url: this.formEndpoint,
        data: {
          primary: this.primary.value,
          parent_id: this.parent.id,
          template_id: this.template.id,
          name: this.name,
          slug: this.slug,
          components: this.components,
          meta: this.meta
        }
      }).then(function (response) {
        _this4.saving = false;
        _app__WEBPACK_IMPORTED_MODULE_0__["bus"].$emit('page-created', response.data.data);

        if (_this4.type === 'create') {
          window.location.replace('/admin/pages');
        }

        _this4.alertShow = true;
        _this4.alertMessage = 'Saved changes to page';
        _this4.alertType = 'success';
      })["catch"](function (error) {
        _this4.saving = false; // 409 status code: conflict, i.e. already exists in system

        if (error.response.status === 409) {
          console.log('Page already exists in system');
          _this4.alertShow = true;
          _this4.alertType = 'danger';
          _this4.alertMessage = 'Page already exists!';
          return;
        }

        if (error.response.status === 422) {
          console.log('Validation failed');
          _this4.alertShow = true;
          _this4.alertType = 'danger';
          _this4.alertMessage = _helpers__WEBPACK_IMPORTED_MODULE_1__["default"].createErrorsList(error.response.data.errors);
          return;
        }

        console.log(error);
        _this4.alertShow = true;
        _this4.alertType = 'danger';
        _this4.alertMessage = 'Failed to create page try again later';
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/RecursivePageTableRowNode.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/RecursivePageTableRowNode.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  mounted: function mounted() {
    console.log('Component mounted.');
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/RecursiveTableRow.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/RecursiveTableRow.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  mounted: function mounted() {
    console.log('Component mounted.');
  },
  props: {
    data: {
      type: Array,
      "default": []
    },
    parentSlug: {
      type: String,
      "default": ''
    },
    nodeLevel: {
      type: Number,
      "default": 0
    }
  },
  methods: {
    confirmDelete: function confirmDelete(page) {
      var _this = this;

      if (page.primary === 1) {
        this.$bvModal.msgBoxOk('Cannot delete primary page').then(function (value) {
          return false;
        })["catch"](function (error) {
          return false;
        });
        return false;
      }

      if (page.children.length > 0) {
        this.$bvModal.msgBoxOk('Unable to delete page, children exist').then(function (value) {
          return false;
        })["catch"](function (error) {
          return false;
        });
        return false;
      }

      this.$bvModal.msgBoxConfirm('Are you sure?').then(function (value) {
        if (value) {
          var index = _this.data.indexOf(page);

          var self = _this;
          axios["delete"]('/api/page/' + page.id).then(function (response) {
            self.data.splice(index, 1);
          })["catch"](function (error) {// handle error
          });
        }
      })["catch"](function (error) {// An error occurred
      });
    }
  }
});

/***/ }),

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

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/TemplatesFormComponent.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/TemplatesFormComponent.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _app__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../app */ "./resources/js/app.js");
/* harmony import */ var _helpers__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../helpers */ "./resources/js/helpers.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  props: {
    type: {
      type: String,
      "default": 'create'
    },
    template: {
      type: Object,
      "default": {}
    }
  },
  data: function data() {
    return {
      saving: false,
      alertShow: false,
      alertType: 'primary',
      alertMessage: '',
      id: '',
      name: '',
      description: '',
      sections: [],
      sectionName: '',
      sectionSlug: '',
      sectionColumn: 3,
      sectionOrder: 0,
      sectionWrapperClass: ''
    };
  },
  computed: {
    button: function button() {
      if (this.type === 'create') {
        return 'Create';
      }

      return 'Save changes';
    },
    formEndpoint: function formEndpoint() {
      var endpoint = '/api/template';

      if (this.type === 'edit') {
        endpoint = '/api/template/' + this.template.id;
      }

      return endpoint;
    },
    formMethod: function formMethod() {
      var method = 'post';

      if (this.type === 'edit') {
        method = 'patch';
      }

      return method;
    }
  },
  methods: {
    load: function load() {
      if (this.template.id !== undefined) {
        this.id = this.template.id;
        this.name = this.template.name;
        this.description = this.template.description;
        this.sections = this.template.sections;
      }
    },
    save: function save() {
      var _this = this;

      this.saving = true;
      axios({
        method: this.formMethod,
        url: this.formEndpoint,
        data: {
          name: this.name,
          description: this.description,
          sections: this.sections
        }
      }).then(function (response) {
        _this.saving = false;
        _app__WEBPACK_IMPORTED_MODULE_0__["bus"].$emit('template-created', response.data.data);

        if (_this.type === 'create') {
          window.location.replace('/admin/templates');
        }

        _this.alertShow = true;
        _this.alertMessage = 'Saved changes to template';
        _this.alertType = 'success';
      })["catch"](function (error) {
        _this.saving = false; // 409 status code: conflict, i.e. already exists in system

        if (error.response.status === 409) {
          console.log('Template already exists in system');
          _this.alertShow = true;
          _this.alertType = 'danger';
          _this.alertMessage = 'Template already exists!';
          return;
        }

        if (error.response.status === 422) {
          console.log('Validation failed');
          _this.alertShow = true;
          _this.alertType = 'danger';
          _this.alertMessage = _helpers__WEBPACK_IMPORTED_MODULE_1__["default"].createErrorsList(error.response.data.errors);
          return;
        }

        console.log(error);
        _this.alertShow = true;
        _this.alertType = 'danger';
        _this.alertMessage = 'Failed to create template try again later';
      });
    },
    generateSlug: function generateSlug() {
      this.sectionSlug = this.sectionName.toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
    },
    addSection: function addSection() {
      var _this2 = this;

      var sectionExists = this.sections.filter(function (section) {
        return section.name === _this2.sectionName && section.slug === _this2.sectionSlug;
      });

      if (this.sectionName === '' || this.sectionSlug === '') {
        this.alertShow = true;
        this.alertType = 'danger';
        this.alertMessage = 'Section name and slug must not be empty';
        return;
      }

      if (sectionExists.length > 0) {
        this.alertShow = true;
        this.alertType = 'danger';
        this.alertMessage = 'Section with name or slug already exists, pick a unique name';
        return;
      }

      this.sections.push({
        name: this.sectionName,
        slug: this.sectionSlug,
        column: this.sectionColumn,
        order: this.sectionOrder,
        wrapper: this.sectionWrapperClass
      });
      this.sectionName = '';
      this.sectionSlug = '';
      this.sectionColumn = 3;
      this.sectionOrder = 0;
      this.alertShow = false;
    },
    removeSection: function removeSection(section) {
      var index = this.sections.indexOf(section);
      this.sections.splice(index, 1);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/UsersComponent.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/UsersComponent.vue?vue&type=script&lang=js& ***!
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
      users: []
    };
  },
  methods: {
    load: function load() {
      var _this = this;

      axios.get('/api/user').then(function (response) {
        _this.users = response.data.data;

        if (_this.users.length > 0) {
          _this.showResults = true;
        }

        _this.loading = false;
      })["catch"](function (error) {// handle error
      });
    },
    confirmDelete: function confirmDelete(user) {
      var _this2 = this;

      this.$bvModal.msgBoxConfirm('Are you sure?').then(function (value) {
        if (value) {
          var index = _this2.users.indexOf(user);

          var self = _this2;
          axios["delete"]('/api/user/' + user.id).then(function (response) {
            self.users.splice(index, 1);

            if (self.users.length < 1) {
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

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/UsersFormComponent.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/UsersFormComponent.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _app__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../app */ "./resources/js/app.js");
/* harmony import */ var _helpers__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../helpers */ "./resources/js/helpers.js");
/* harmony import */ var vuelidate__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vuelidate */ "./node_modules/vuelidate/lib/index.js");
/* harmony import */ var vuelidate__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(vuelidate__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! vuelidate/lib/validators */ "./node_modules/vuelidate/lib/validators/index.js");
/* harmony import */ var vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_3__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  mixins: [vuelidate__WEBPACK_IMPORTED_MODULE_2__["validationMixin"]],
  mounted: function mounted() {
    console.log('Component mounted.');
    this.load();
  },
  props: {
    type: {
      type: String,
      "default": 'create'
    },
    user: {
      type: Object,
      "default": {}
    }
  },
  data: function data() {
    return {
      saving: false,
      alertShow: false,
      alertType: 'primary',
      alertMessage: '',
      form: {
        id: '',
        name: '',
        email: '',
        password: '',
        confirmPassword: ''
      }
    };
  },
  validations: {
    form: {
      name: {
        required: vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_3__["required"],
        minLength: Object(vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_3__["minLength"])(3)
      },
      email: {
        required: vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_3__["required"],
        email: vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_3__["email"]
      },
      password: {
        required: Object(vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_3__["requiredIf"])('noUserDefined'),
        minLength: Object(vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_3__["minLength"])(6)
      },
      confirmPassword: {
        confirmPassword: Object(vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_3__["sameAs"])('password')
      }
    }
  },
  computed: {
    noUserDefined: function noUserDefined() {
      debugger;
      return this.user.id !== undefined;
    },
    button: function button() {
      if (this.type === 'create') {
        return 'Create';
      }

      return 'Save changes';
    },
    formEndpoint: function formEndpoint() {
      var endpoint = '/api/user';

      if (this.type === 'edit') {
        endpoint = '/api/user/' + this.user.id;
      }

      return endpoint;
    },
    formMethod: function formMethod() {
      var method = 'post';

      if (this.type === 'edit') {
        method = 'patch';
      }

      return method;
    }
  },
  methods: {
    validateState: function validateState(name) {
      var _this$$v$form$name = this.$v.form[name],
          $dirty = _this$$v$form$name.$dirty,
          $error = _this$$v$form$name.$error;
      return $dirty ? !$error : null;
    },
    load: function load() {
      if (this.user.id !== undefined) {
        this.form.id = this.user.id;
        this.form.name = this.user.name;
        this.form.email = this.user.email;
      }
    },
    save: function save() {
      var _this = this;

      this.$v.form.$touch();

      if (this.$v.form.$anyError) {
        return;
      }

      this.saving = true;
      axios({
        method: this.formMethod,
        url: this.formEndpoint,
        data: {
          name: this.form.name,
          email: this.form.email,
          password: this.form.password
        }
      }).then(function (response) {
        _this.saving = false;
        _app__WEBPACK_IMPORTED_MODULE_0__["bus"].$emit('user-created', response.data.data);

        if (_this.type === 'create') {
          window.location.replace('/admin/users');
        }

        _this.alertShow = true;
        _this.alertMessage = 'Saved changes to user';
        _this.alertType = 'success';
      })["catch"](function (error) {
        _this.saving = false; // 409 status code: conflict, i.e. already exists in system

        if (error.response.status === 409) {
          console.log('User already exists in system');
          _this.alertShow = true;
          _this.alertType = 'danger';
          _this.alertMessage = 'User already exists!';
          return;
        }

        if (error.response.status === 422) {
          console.log('Validation failed');
          _this.alertShow = true;
          _this.alertType = 'danger';
          _this.alertMessage = _helpers__WEBPACK_IMPORTED_MODULE_1__["default"].createErrorsList(error.response.data.errors);
          return;
        }

        console.log(error);
        _this.alertShow = true;
        _this.alertType = 'danger';
        _this.alertMessage = 'Failed to create user try again later';
      });
    },
    generateSlug: function generateSlug() {
      this.sectionSlug = this.sectionName.toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/RecursiveTableRow.vue?vue&type=style&index=0&lang=scss&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--9-2!./node_modules/sass-loader/dist/cjs.js??ref--9-3!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/RecursiveTableRow.vue?vue&type=style&index=0&lang=scss& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, ".row-button {\n  margin-top: -4px;\n  margin-bottom: 4px;\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/RecursiveTableRow.vue?vue&type=style&index=0&lang=scss&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--9-2!./node_modules/sass-loader/dist/cjs.js??ref--9-3!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/RecursiveTableRow.vue?vue&type=style&index=0&lang=scss& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../node_modules/css-loader!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/src??ref--9-2!../../../node_modules/sass-loader/dist/cjs.js??ref--9-3!../../../node_modules/vue-loader/lib??vue-loader-options!./RecursiveTableRow.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/RecursiveTableRow.vue?vue&type=style&index=0&lang=scss&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/AdminPageComponents/AccordionComponent.vue?vue&type=template&id=6d71189b&":
/*!*****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/AdminPageComponents/AccordionComponent.vue?vue&type=template&id=6d71189b& ***!
  \*****************************************************************************************************************************************************************************************************************************************/
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
        "b-form-group",
        {
          attrs: {
            id: "page-accordion-title-group",
            label: "Title",
            "label-for": "accordion-title"
          }
        },
        [
          _c("b-form-input", {
            attrs: {
              id: "accordion-title",
              required: "",
              placeholder: "e.g. What is it we do?"
            },
            model: {
              value: _vm.title,
              callback: function($$v) {
                _vm.title = $$v
              },
              expression: "title"
            }
          })
        ],
        1
      ),
      _vm._v(" "),
      _c("ckeditor", {
        attrs: { editor: _vm.editor, config: _vm.editorConfig },
        model: {
          value: _vm.editorData,
          callback: function($$v) {
            _vm.editorData = $$v
          },
          expression: "editorData"
        }
      }),
      _vm._v(" "),
      _c(
        "b-button",
        {
          staticClass: "mt-2 mb-2",
          attrs: { variant: "success" },
          on: {
            click: function($event) {
              return _vm.addAccordionSection()
            }
          }
        },
        [_vm._v("Add accordion section")]
      ),
      _vm._v(" "),
      _c(
        "h5",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value:
                _vm.value.sections !== undefined &&
                _vm.value.sections.length > 0,
              expression:
                "value.sections !== undefined && value.sections.length > 0"
            }
          ]
        },
        [_vm._v("Preview")]
      ),
      _vm._v(" "),
      _c(
        "div",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value:
                _vm.value.sections !== undefined &&
                _vm.value.sections.length > 0,
              expression:
                "value.sections !== undefined && value.sections.length > 0"
            }
          ],
          attrs: { role: "tablist" }
        },
        _vm._l(_vm.value.sections, function(section, index) {
          return _c(
            "b-card",
            { staticClass: "mb-1", attrs: { "no-body": "" } },
            [
              _c(
                "b-card-header",
                {
                  staticClass: "p-1",
                  attrs: { "header-tag": "header", role: "tab" }
                },
                [
                  _c(
                    "b-button",
                    {
                      directives: [
                        {
                          name: "b-toggle",
                          rawName: "v-b-toggle",
                          value: "accordion-" + index,
                          expression: "'accordion-' + index"
                        }
                      ],
                      staticClass: "w-50",
                      attrs: { href: "#", variant: "primary" }
                    },
                    [
                      _vm._v(
                        "\n                    " +
                          _vm._s(section.title) +
                          "\n                "
                      )
                    ]
                  ),
                  _vm._v(" "),
                  _c(
                    "b-button",
                    {
                      staticClass: "float-right",
                      attrs: { variant: "danger" },
                      on: {
                        click: function($event) {
                          return _vm.removeSection(section)
                        }
                      }
                    },
                    [_vm._v("Delete")]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "b-collapse",
                {
                  attrs: {
                    id: "accordion-" + index,
                    visible: "",
                    accordion: "my-accordion",
                    role: "tabpanel"
                  }
                },
                [
                  _c(
                    "b-card-body",
                    [
                      _c("b-card-text", {
                        domProps: { innerHTML: _vm._s(section.content) }
                      })
                    ],
                    1
                  )
                ],
                1
              )
            ],
            1
          )
        }),
        1
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/AdminPageComponents/ContentComponent.vue?vue&type=template&id=00b3c48a&":
/*!***************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/AdminPageComponents/ContentComponent.vue?vue&type=template&id=00b3c48a& ***!
  \***************************************************************************************************************************************************************************************************************************************/
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
      _c("ckeditor", {
        attrs: {
          editor: _vm.editor,
          value: _vm.value.editorData,
          config: _vm.editorConfig
        },
        on: { input: _vm.onChange }
      })
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/PageComponents.vue?vue&type=template&id=03117bb0&":
/*!*****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/PageComponents.vue?vue&type=template&id=03117bb0& ***!
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
  return _c("div", [
    _c("label", { attrs: { for: "components" } }, [_vm._v("Component")]),
    _vm._v(" "),
    _c("div", { staticClass: "row" }, [
      _c(
        "div",
        { staticClass: "col-md-5" },
        [
          _c("v-select", {
            attrs: {
              id: "components",
              label: "name",
              options: _vm.options,
              clearable: false
            },
            model: {
              value: _vm.component,
              callback: function($$v) {
                _vm.component = $$v
              },
              expression: "component"
            }
          })
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "col-md-5" },
        [
          _c("v-select", {
            attrs: {
              id: "section",
              label: "name",
              options: _vm.sectionOptions,
              clearable: false
            },
            model: {
              value: _vm.section,
              callback: function($$v) {
                _vm.section = $$v
              },
              expression: "section"
            }
          })
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "col-md-2" },
        [
          _c(
            "b-button",
            {
              staticClass: "btn-sm w-100",
              attrs: { variant: "success" },
              on: {
                click: function($event) {
                  return _vm.addComponent()
                }
              }
            },
            [_vm._v("Add Component")]
          )
        ],
        1
      )
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "hr mb-4 mt-4" }),
    _vm._v(" "),
    _c("div", { staticClass: "row" }, [
      _c(
        "div",
        { staticClass: "col-md-12" },
        _vm._l(_vm.components, function(pageComponent) {
          return _c(
            "div",
            [
              _c(
                "b-card",
                { staticClass: "mb-2" },
                [
                  _c(
                    "h4",
                    [
                      _vm._v(_vm._s(pageComponent.frontendName) + " "),
                      _c(
                        "b-btn",
                        {
                          staticClass: "float-right btn-sm",
                          attrs: { variant: "danger" },
                          on: {
                            click: function($event) {
                              return _vm.removeComponent(pageComponent)
                            }
                          }
                        },
                        [_vm._v("Remove ×")]
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c("h5", [
                    _vm._v("Section: " + _vm._s(pageComponent.section))
                  ]),
                  _vm._v(" "),
                  _c(pageComponent.name, {
                    tag: "component",
                    staticClass: "mb-2",
                    attrs: { id: pageComponent.id },
                    model: {
                      value: pageComponent.properties,
                      callback: function($$v) {
                        _vm.$set(pageComponent, "properties", $$v)
                      },
                      expression: "pageComponent.properties"
                    }
                  })
                ],
                1
              )
            ],
            1
          )
        }),
        0
      )
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/PagesFormComponent.vue?vue&type=template&id=749c9260&":
/*!*********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/PagesFormComponent.vue?vue&type=template&id=749c9260& ***!
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
  return _c("div", [
    _c("div", { staticClass: "row" }, [
      _c(
        "div",
        { staticClass: "col-md-12" },
        [
          _c("h2", { staticClass: "admin-title" }, [
            _vm._v(
              "\n                " +
                _vm._s(
                  _vm.type === "create" ? "Create new page" : "Edit page "
                ) +
                "\n                "
            ),
            _c(
              "strong",
              {
                directives: [
                  {
                    name: "show",
                    rawName: "v-show",
                    value: _vm.type === "edit",
                    expression: "type === 'edit'"
                  }
                ]
              },
              [_vm._v(_vm._s(_vm.page.name))]
            )
          ]),
          _vm._v(" "),
          _c("b-alert", {
            attrs: {
              show: _vm.alertShow,
              variant: _vm.alertType,
              dismissible: ""
            },
            domProps: { innerHTML: _vm._s(_vm.alertMessage) }
          })
        ],
        1
      )
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-md-9" }, [
        _c("div", { staticClass: "page-section p-4 mb-4" }, [
          _c("div", { staticClass: "row" }, [
            _c(
              "div",
              { staticClass: "col-md-6" },
              [
                _c(
                  "b-form-group",
                  {
                    attrs: {
                      id: "page-name-group",
                      label: "Page name",
                      "label-for": "page-name"
                    }
                  },
                  [
                    _c("b-form-input", {
                      attrs: {
                        id: "page-name-input",
                        required: "",
                        placeholder: "Enter page name"
                      },
                      on: {
                        keyup: function($event) {
                          return _vm.generateSlug()
                        }
                      },
                      model: {
                        value: _vm.name,
                        callback: function($$v) {
                          _vm.name = $$v
                        },
                        expression: "name"
                      }
                    })
                  ],
                  1
                )
              ],
              1
            ),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "col-md-6" },
              [
                _c(
                  "b-form-group",
                  {
                    attrs: {
                      id: "page-slug-group",
                      label: "Page slug",
                      "label-for": "page-slug"
                    }
                  },
                  [
                    _c("b-form-input", {
                      attrs: {
                        id: "page-slug-input",
                        required: "",
                        placeholder: "e.g. /home"
                      },
                      model: {
                        value: _vm.slug,
                        callback: function($$v) {
                          _vm.slug = $$v
                        },
                        expression: "slug"
                      }
                    })
                  ],
                  1
                )
              ],
              1
            )
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "row" }, [
          _c("div", { staticClass: "col-md-12" }, [
            _c(
              "div",
              { staticClass: "page-section p-4" },
              [
                _c(
                  "b-card",
                  { attrs: { "no-body": "" } },
                  [
                    _c(
                      "b-tabs",
                      { attrs: { pills: "", card: "", end: "" } },
                      [
                        _c(
                          "b-tab",
                          { attrs: { title: "Components", active: "" } },
                          [
                            _c(
                              "b-card-text",
                              [
                                _c("page-components", {
                                  attrs: { template: _vm.template },
                                  model: {
                                    value: _vm.components,
                                    callback: function($$v) {
                                      _vm.components = $$v
                                    },
                                    expression: "components"
                                  }
                                })
                              ],
                              1
                            )
                          ],
                          1
                        ),
                        _vm._v(" "),
                        _c(
                          "b-tab",
                          { attrs: { title: "Meta" } },
                          [
                            _c(
                              "b-card-text",
                              [
                                _c(
                                  "b-form-group",
                                  {
                                    attrs: {
                                      id: "page-title-group",
                                      label: "Title",
                                      "label-for": "page-title"
                                    }
                                  },
                                  [
                                    _c("b-form-input", {
                                      attrs: {
                                        id: "page-title-input",
                                        required: "",
                                        placeholder: "e.g. Homepage"
                                      },
                                      model: {
                                        value: _vm.meta.title,
                                        callback: function($$v) {
                                          _vm.$set(_vm.meta, "title", $$v)
                                        },
                                        expression: "meta.title"
                                      }
                                    })
                                  ],
                                  1
                                ),
                                _vm._v(" "),
                                _c(
                                  "b-form-group",
                                  {
                                    attrs: {
                                      id: "page-keywords-group",
                                      label: "Keywords",
                                      "label-for": "page-keywords"
                                    }
                                  },
                                  [
                                    _c("b-form-input", {
                                      attrs: {
                                        id: "page-keywords-input",
                                        required: "",
                                        placeholder: "e.g. trap,music,museum"
                                      },
                                      model: {
                                        value: _vm.meta.keywords,
                                        callback: function($$v) {
                                          _vm.$set(_vm.meta, "keywords", $$v)
                                        },
                                        expression: "meta.keywords"
                                      }
                                    })
                                  ],
                                  1
                                ),
                                _vm._v(" "),
                                _c(
                                  "b-form-group",
                                  {
                                    attrs: {
                                      id: "page-author-group",
                                      label: "Author",
                                      "label-for": "page-author"
                                    }
                                  },
                                  [
                                    _c("b-form-input", {
                                      attrs: {
                                        id: "page-author-input",
                                        required: "",
                                        placeholder: "e.g. Andre Figueira"
                                      },
                                      model: {
                                        value: _vm.meta.author,
                                        callback: function($$v) {
                                          _vm.$set(_vm.meta, "author", $$v)
                                        },
                                        expression: "meta.author"
                                      }
                                    })
                                  ],
                                  1
                                ),
                                _vm._v(" "),
                                _c(
                                  "b-form-group",
                                  {
                                    attrs: {
                                      id: "page-description-group",
                                      label: "Description",
                                      "label-for": "page-description"
                                    }
                                  },
                                  [
                                    _c("b-textarea", {
                                      attrs: {
                                        id: "page-description-input",
                                        required: ""
                                      },
                                      model: {
                                        value: _vm.meta.description,
                                        callback: function($$v) {
                                          _vm.$set(_vm.meta, "description", $$v)
                                        },
                                        expression: "meta.description"
                                      }
                                    })
                                  ],
                                  1
                                )
                              ],
                              1
                            )
                          ],
                          1
                        )
                      ],
                      1
                    )
                  ],
                  1
                )
              ],
              1
            )
          ])
        ])
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "col-md-3" }, [
        _c(
          "div",
          { staticClass: "page-section p-4" },
          [
            _c("label", { attrs: { for: "primary-option" } }, [
              _vm._v("Page type")
            ]),
            _vm._v(" "),
            _c("v-select", {
              staticClass: "mb-3",
              attrs: {
                id: "primary-option",
                label: "title",
                options: _vm.primaryOptions,
                clearable: false
              },
              model: {
                value: _vm.primary,
                callback: function($$v) {
                  _vm.primary = $$v
                },
                expression: "primary"
              }
            }),
            _vm._v(" "),
            _c("label", { attrs: { for: "parent" } }, [_vm._v("Parent")]),
            _vm._v(" "),
            _c("v-select", {
              staticClass: "mb-3",
              attrs: {
                id: "parent",
                label: "name",
                options: _vm.pages,
                clearable: false
              },
              model: {
                value: _vm.parent,
                callback: function($$v) {
                  _vm.parent = $$v
                },
                expression: "parent"
              }
            }),
            _vm._v(" "),
            _c("label", { attrs: { for: "template" } }, [_vm._v("Template")]),
            _vm._v(" "),
            _c("v-select", {
              staticClass: "mb-3",
              attrs: {
                id: "template",
                label: "name",
                options: _vm.templates,
                clearable: false
              },
              model: {
                value: _vm.template,
                callback: function($$v) {
                  _vm.template = $$v
                },
                expression: "template"
              }
            }),
            _vm._v(" "),
            _c(
              "b-button",
              {
                attrs: { variant: "success", disabled: _vm.saving },
                on: {
                  click: function($event) {
                    return _vm.save()
                  }
                }
              },
              [_vm._v(_vm._s(_vm.button))]
            )
          ],
          1
        )
      ])
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/RecursivePageTableRowNode.vue?vue&type=template&id=aee17718&":
/*!****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/RecursivePageTableRowNode.vue?vue&type=template&id=aee17718& ***!
  \****************************************************************************************************************************************************************************************************************************/
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
  return _c("div")
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/RecursiveTableRow.vue?vue&type=template&id=46c51fa3&":
/*!********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/RecursiveTableRow.vue?vue&type=template&id=46c51fa3& ***!
  \********************************************************************************************************************************************************************************************************************/
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
    {
      directives: [
        {
          name: "show",
          rawName: "v-show",
          value: _vm.data.length > 0,
          expression: "data.length > 0"
        }
      ]
    },
    _vm._l(_vm.data, function(page) {
      return _c("div", { key: page.id, staticClass: "row" }, [
        _c("div", { staticClass: "col-md-12 table-row" }, [
          _c("div", { staticClass: "row" }, [
            _c("div", { staticClass: "col-md-3" }, [
              _c("span", { class: "ml-" + _vm.nodeLevel }, [_vm._v("↳")]),
              _vm._v(" "),
              _c(
                "a",
                {
                  staticClass: "text-dark",
                  attrs: { href: "/admin/pages/edit/" + page.id }
                },
                [_vm._v(_vm._s(page.name))]
              )
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "col-md-3" }, [_vm._v(_vm._s(page.slug))]),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "col-md-2" },
              [
                _c(
                  "b-badge",
                  {
                    attrs: {
                      variant: page.primary === 1 ? "primary" : "secondary"
                    }
                  },
                  [_vm._v(_vm._s(page.primary === 1 ? "Primary" : "Standard"))]
                )
              ],
              1
            ),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "col-md-2" },
              [
                _c("b-badge", { attrs: { variant: "primary" } }, [
                  _vm._v(_vm._s(page.template.name))
                ])
              ],
              1
            ),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "col-md-2" },
              [
                _c(
                  "b-button",
                  {
                    staticClass: "float-right row-button",
                    attrs: { variant: "danger", size: "sm" },
                    on: {
                      click: function($event) {
                        return _vm.confirmDelete(page)
                      }
                    }
                  },
                  [_vm._v("Delete")]
                ),
                _vm._v(" "),
                _c(
                  "a",
                  {
                    staticClass:
                      "btn btn-sm btn-primary float-right mr-1 row-button",
                    attrs: { href: "/admin/pages/edit/" + page.id }
                  },
                  [_vm._v("Edit")]
                )
              ],
              1
            )
          ])
        ]),
        _vm._v(" "),
        _c(
          "div",
          {
            directives: [
              {
                name: "show",
                rawName: "v-show",
                value: page.children.length > 0,
                expression: "page.children.length > 0"
              }
            ],
            staticClass: "col-md-12"
          },
          [
            _c("recursive-table-row", {
              attrs: {
                nodeLevel: _vm.nodeLevel + 2,
                parentSlug: page.slug,
                data: page.children
              }
            })
          ],
          1
        )
      ])
    }),
    0
  )
}
var staticRenderFns = []
render._withStripped = true



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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/TemplatesFormComponent.vue?vue&type=template&id=f18a93ea&":
/*!*************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/TemplatesFormComponent.vue?vue&type=template&id=f18a93ea& ***!
  \*************************************************************************************************************************************************************************************************************************/
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
  return _c("div", [
    _c("div", { staticClass: "row" }, [
      _c(
        "div",
        { staticClass: "col-md-12" },
        [
          _c("h2", { staticClass: "admin-title" }, [
            _vm._v(
              "\n                " +
                _vm._s(
                  _vm.type === "create"
                    ? "Create new template"
                    : "Edit template "
                ) +
                "\n                "
            ),
            _c(
              "strong",
              {
                directives: [
                  {
                    name: "show",
                    rawName: "v-show",
                    value: _vm.type === "edit",
                    expression: "type === 'edit'"
                  }
                ]
              },
              [_vm._v(_vm._s(_vm.template.name))]
            )
          ]),
          _vm._v(" "),
          _c("b-alert", {
            attrs: {
              show: _vm.alertShow,
              variant: _vm.alertType,
              dismissible: ""
            },
            domProps: { innerHTML: _vm._s(_vm.alertMessage) }
          })
        ],
        1
      )
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-md-12" }, [
        _c("div", { staticClass: "page-section p-4 mb-4" }, [
          _c("div", { staticClass: "row" }, [
            _c(
              "div",
              { staticClass: "col-md-12" },
              [
                _c(
                  "b-form-group",
                  {
                    attrs: {
                      id: "template-name-group",
                      label: "Template name",
                      "label-for": "template-name"
                    }
                  },
                  [
                    _c("b-form-input", {
                      attrs: {
                        id: "template-name-input",
                        required: "",
                        placeholder: "Enter template name"
                      },
                      model: {
                        value: _vm.name,
                        callback: function($$v) {
                          _vm.name = $$v
                        },
                        expression: "name"
                      }
                    })
                  ],
                  1
                ),
                _vm._v(" "),
                _c(
                  "b-form-group",
                  {
                    attrs: {
                      id: "template-description-group",
                      label: "Template description",
                      "label-for": "template-description"
                    }
                  },
                  [
                    _c("b-form-textarea", {
                      attrs: { id: "template-name-input", required: "" },
                      model: {
                        value: _vm.description,
                        callback: function($$v) {
                          _vm.description = $$v
                        },
                        expression: "description"
                      }
                    })
                  ],
                  1
                ),
                _vm._v(" "),
                _c("div", { staticClass: "row" }, [
                  _c(
                    "div",
                    { staticClass: "col-md-3" },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            id: "section-name-group",
                            label: "Section name",
                            "label-for": "section-name"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: {
                              id: "section-name-input",
                              required: "",
                              placeholder: "Enter section name"
                            },
                            on: {
                              keyup: function($event) {
                                return _vm.generateSlug()
                              }
                            },
                            model: {
                              value: _vm.sectionName,
                              callback: function($$v) {
                                _vm.sectionName = $$v
                              },
                              expression: "sectionName"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "col-md-2" },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            id: "section-slug-group",
                            label: "Section slug",
                            "label-for": "section-slug"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: {
                              id: "section-slug-input",
                              required: "",
                              placeholder: "Enter section slug"
                            },
                            model: {
                              value: _vm.sectionSlug,
                              callback: function($$v) {
                                _vm.sectionSlug = $$v
                              },
                              expression: "sectionSlug"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "col-md-2" },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            id: "section-column-group",
                            label: "Section column size",
                            "label-for": "section-column"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: {
                              id: "section-column-input",
                              required: "",
                              placeholder: "Enter section column size"
                            },
                            model: {
                              value: _vm.sectionColumn,
                              callback: function($$v) {
                                _vm.sectionColumn = $$v
                              },
                              expression: "sectionColumn"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "col-md-2" },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            id: "section-order-group",
                            label: "Section order",
                            "label-for": "section-order"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: {
                              id: "section-order-input",
                              required: "",
                              placeholder: "Enter section order"
                            },
                            model: {
                              value: _vm.sectionOrder,
                              callback: function($$v) {
                                _vm.sectionOrder = $$v
                              },
                              expression: "sectionOrder"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "col-md-3" },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            id: "section-wrapper-class-group",
                            label: "Section wrapper class",
                            "label-for": "section-wrapper-class"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: {
                              id: "section-wrapper-class-input",
                              required: "",
                              placeholder: "Enter section wrapper class"
                            },
                            model: {
                              value: _vm.sectionWrapperClass,
                              callback: function($$v) {
                                _vm.sectionWrapperClass = $$v
                              },
                              expression: "sectionWrapperClass"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  )
                ]),
                _vm._v(" "),
                _c(
                  "b-button",
                  {
                    staticClass: "mb-2",
                    attrs: { variant: "success" },
                    on: {
                      click: function($event) {
                        return _vm.addSection()
                      }
                    }
                  },
                  [_vm._v("Add section")]
                ),
                _vm._v(" "),
                _c(
                  "b-list-group",
                  _vm._l(_vm.sections, function(section) {
                    return _c(
                      "b-list-group-item",
                      { key: section.slug },
                      [
                        _c("strong", [_vm._v("Name: ")]),
                        _vm._v(" " + _vm._s(section.name)),
                        _c("br"),
                        _vm._v(" "),
                        _c("strong", [_vm._v("Slug: ")]),
                        _vm._v(" " + _vm._s(section.slug)),
                        _c("br"),
                        _vm._v(" "),
                        _c("strong", [_vm._v("Column: ")]),
                        _vm._v(" " + _vm._s(section.column)),
                        _c("br"),
                        _vm._v(" "),
                        _c("strong", [_vm._v("Order: ")]),
                        _vm._v(" " + _vm._s(section.order)),
                        _c("br"),
                        _vm._v(" "),
                        _c("strong", [_vm._v("Wrapper class: ")]),
                        _vm._v(
                          " " +
                            _vm._s(section.wrapper) +
                            "\n                                "
                        ),
                        _c(
                          "b-button",
                          {
                            staticClass: "btn-sm float-right",
                            attrs: { variant: "danger" },
                            on: {
                              click: function($event) {
                                return _vm.removeSection(section)
                              }
                            }
                          },
                          [_vm._v("×")]
                        )
                      ],
                      1
                    )
                  }),
                  1
                ),
                _vm._v(" "),
                _c(
                  "b-button",
                  {
                    staticClass: "mt-2",
                    attrs: { variant: "success", disabled: _vm.saving },
                    on: {
                      click: function($event) {
                        return _vm.save()
                      }
                    }
                  },
                  [_vm._v(_vm._s(_vm.button))]
                )
              ],
              1
            )
          ])
        ])
      ])
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/UsersComponent.vue?vue&type=template&id=47d47080&":
/*!*****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/UsersComponent.vue?vue&type=template&id=47d47080& ***!
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
          _vm._v("No users added yet · "),
          _c("a", { attrs: { href: "/admin/users/create" } }, [
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
          _vm._l(_vm.users, function(user) {
            return _c("tr", [
              _c("td", [_vm._v(_vm._s(user.name))]),
              _vm._v(" "),
              _c("td", [_vm._v(_vm._s(user.email))]),
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
                          return _vm.confirmDelete(user)
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
                      attrs: { href: "/admin/users/edit/" + user.id }
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
      _c("th", [_vm._v("Email")]),
      _vm._v(" "),
      _c("th")
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/UsersFormComponent.vue?vue&type=template&id=cb4476c8&":
/*!*********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/UsersFormComponent.vue?vue&type=template&id=cb4476c8& ***!
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
  return _c("div", [
    _c("div", { staticClass: "row" }, [
      _c(
        "div",
        { staticClass: "col-md-12" },
        [
          _c("h2", { staticClass: "admin-title" }, [
            _vm._v(
              "\n                " +
                _vm._s(
                  _vm.type === "create" ? "Create new user" : "Edit user "
                ) +
                "\n                "
            ),
            _c(
              "strong",
              {
                directives: [
                  {
                    name: "show",
                    rawName: "v-show",
                    value: _vm.type === "edit",
                    expression: "type === 'edit'"
                  }
                ]
              },
              [_vm._v(_vm._s(_vm.user.name))]
            )
          ]),
          _vm._v(" "),
          _c("b-alert", {
            attrs: {
              show: _vm.alertShow,
              variant: _vm.alertType,
              dismissible: ""
            },
            domProps: { innerHTML: _vm._s(_vm.alertMessage) }
          })
        ],
        1
      )
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-md-12" }, [
        _c("div", { staticClass: "page-section p-4 mb-4" }, [
          _c("div", { staticClass: "row" }, [
            _c(
              "div",
              { staticClass: "col-md-6" },
              [
                _c(
                  "b-form-group",
                  {
                    attrs: {
                      id: "user-name-group",
                      label: "User name",
                      "label-for": "user-name"
                    }
                  },
                  [
                    _c("b-form-input", {
                      attrs: {
                        id: "user-name-input",
                        required: "",
                        state: _vm.validateState("name"),
                        placeholder: "Enter user name"
                      },
                      model: {
                        value: _vm.form.name,
                        callback: function($$v) {
                          _vm.$set(_vm.form, "name", $$v)
                        },
                        expression: "form.name"
                      }
                    }),
                    _vm._v(" "),
                    _c("b-form-invalid-feedback", [
                      _vm._v("Enter a valid name with more than 3 characters")
                    ])
                  ],
                  1
                )
              ],
              1
            ),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "col-md-6" },
              [
                _c(
                  "b-form-group",
                  {
                    attrs: {
                      id: "user-email-group",
                      label: "User email",
                      "label-for": "user-email"
                    }
                  },
                  [
                    _c("b-form-input", {
                      attrs: {
                        type: "email",
                        id: "user-email-input",
                        required: "",
                        state: _vm.validateState("email"),
                        placeholder: "Enter user email"
                      },
                      model: {
                        value: _vm.form.email,
                        callback: function($$v) {
                          _vm.$set(_vm.form, "email", $$v)
                        },
                        expression: "form.email"
                      }
                    }),
                    _vm._v(" "),
                    _c("b-form-invalid-feedback", [
                      _vm._v("Enter a valid email address")
                    ])
                  ],
                  1
                )
              ],
              1
            ),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "col-md-6" },
              [
                _c(
                  "b-form-group",
                  {
                    attrs: {
                      id: "user-password-group",
                      label: "Password",
                      "label-for": "user-password"
                    }
                  },
                  [
                    _c("b-form-input", {
                      attrs: {
                        type: "password",
                        id: "user-password-input",
                        required: "",
                        state: _vm.validateState("password"),
                        placeholder: "Enter user password"
                      },
                      model: {
                        value: _vm.form.password,
                        callback: function($$v) {
                          _vm.$set(_vm.form, "password", $$v)
                        },
                        expression: "form.password"
                      }
                    }),
                    _vm._v(" "),
                    _c("b-form-invalid-feedback", [
                      _vm._v("Enter a valid password")
                    ])
                  ],
                  1
                )
              ],
              1
            ),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "col-md-6" },
              [
                _c(
                  "b-form-group",
                  {
                    attrs: {
                      id: "user-confirm-password-group",
                      label: "Confirm password",
                      "label-for": "user-confirm-password"
                    }
                  },
                  [
                    _c("b-form-input", {
                      attrs: {
                        type: "password",
                        id: "user-confirm-password-input",
                        required: "",
                        state: _vm.validateState("confirmPassword"),
                        placeholder: "Confirm password"
                      },
                      model: {
                        value: _vm.form.confirmPassword,
                        callback: function($$v) {
                          _vm.$set(_vm.form, "confirmPassword", $$v)
                        },
                        expression: "form.confirmPassword"
                      }
                    }),
                    _vm._v(" "),
                    _c("b-form-invalid-feedback", [
                      _vm._v("Passwords must match")
                    ])
                  ],
                  1
                )
              ],
              1
            ),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "col-md-12" },
              [
                _c(
                  "b-button",
                  {
                    staticClass: "mt-2",
                    attrs: { variant: "success", disabled: _vm.saving },
                    on: {
                      click: function($event) {
                        return _vm.save()
                      }
                    }
                  },
                  [_vm._v(_vm._s(_vm.button))]
                )
              ],
              1
            )
          ])
        ])
      ])
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/*! exports provided: bus */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "bus", function() { return bus; });
/* harmony import */ var bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! bootstrap-vue */ "./node_modules/bootstrap-vue/esm/index.js");
/* harmony import */ var _ckeditor_ckeditor5_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @ckeditor/ckeditor5-vue */ "./node_modules/@ckeditor/ckeditor5-vue/dist/ckeditor.js");
/* harmony import */ var _ckeditor_ckeditor5_vue__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_ckeditor_ckeditor5_vue__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var vuelidate__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vuelidate */ "./node_modules/vuelidate/lib/index.js");
/* harmony import */ var vuelidate__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(vuelidate__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var vue_select__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! vue-select */ "./node_modules/vue-select/dist/vue-select.js");
/* harmony import */ var vue_select__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(vue_select__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var bootstrap_dist_css_bootstrap_css__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! bootstrap/dist/css/bootstrap.css */ "./node_modules/bootstrap/dist/css/bootstrap.css");
/* harmony import */ var bootstrap_dist_css_bootstrap_css__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(bootstrap_dist_css_bootstrap_css__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var bootstrap_vue_dist_bootstrap_vue_css__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! bootstrap-vue/dist/bootstrap-vue.css */ "./node_modules/bootstrap-vue/dist/bootstrap-vue.css");
/* harmony import */ var bootstrap_vue_dist_bootstrap_vue_css__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(bootstrap_vue_dist_bootstrap_vue_css__WEBPACK_IMPORTED_MODULE_5__);
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
__webpack_require__(/*! ./bootstrap */ "./resources/js/bootstrap.js");

window.Vue = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.common.js");
 // Install BootstrapVue

Vue.use(bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BootstrapVue"]); // Optionally install the BootstrapVue icon components plugin

Vue.use(bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["IconsPlugin"]);

Vue.use(_ckeditor_ckeditor5_vue__WEBPACK_IMPORTED_MODULE_1___default.a);

Vue.use(vuelidate__WEBPACK_IMPORTED_MODULE_2___default.a);



/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', __webpack_require__(/*! ./components/ExampleComponent.vue */ "./resources/js/components/ExampleComponent.vue")["default"]);
Vue.component('pages', __webpack_require__(/*! ./components/PagesComponent.vue */ "./resources/js/components/PagesComponent.vue")["default"]);
Vue.component('templates', __webpack_require__(/*! ./components/TemplatesComponent.vue */ "./resources/js/components/TemplatesComponent.vue")["default"]);
Vue.component('users', __webpack_require__(/*! ./components/UsersComponent.vue */ "./resources/js/components/UsersComponent.vue")["default"]);
Vue.component('pages-form', __webpack_require__(/*! ./components/PagesFormComponent.vue */ "./resources/js/components/PagesFormComponent.vue")["default"]);
Vue.component('page-components', __webpack_require__(/*! ./components/PageComponents.vue */ "./resources/js/components/PageComponents.vue")["default"]);
Vue.component('recursive-page-row', __webpack_require__(/*! ./components/RecursivePageTableRowNode.vue */ "./resources/js/components/RecursivePageTableRowNode.vue")["default"]);
Vue.component('recursive-table-row', __webpack_require__(/*! ./components/RecursiveTableRow.vue */ "./resources/js/components/RecursiveTableRow.vue")["default"]);
Vue.component('templates-form', __webpack_require__(/*! ./components/TemplatesFormComponent.vue */ "./resources/js/components/TemplatesFormComponent.vue")["default"]);
Vue.component('users-form', __webpack_require__(/*! ./components/UsersFormComponent.vue */ "./resources/js/components/UsersFormComponent.vue")["default"]);
Vue.component('admin-content-component', __webpack_require__(/*! ./components/AdminPageComponents/ContentComponent.vue */ "./resources/js/components/AdminPageComponents/ContentComponent.vue")["default"]);
Vue.component('admin-accordion-component', __webpack_require__(/*! ./components/AdminPageComponents/AccordionComponent.vue */ "./resources/js/components/AdminPageComponents/AccordionComponent.vue")["default"]);
Vue.component('accordion-component', __webpack_require__(/*! ./components/FrontEndComponents/AccordionComponent.vue */ "./resources/js/components/FrontEndComponents/AccordionComponent.vue")["default"]);
Vue.component('v-select', vue_select__WEBPACK_IMPORTED_MODULE_3___default.a);
var bus = new Vue();
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

var app = new Vue({
  el: '#app',
  components: {}
});

/***/ }),

/***/ "./resources/js/components/AdminPageComponents/AccordionComponent.vue":
/*!****************************************************************************!*\
  !*** ./resources/js/components/AdminPageComponents/AccordionComponent.vue ***!
  \****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _AccordionComponent_vue_vue_type_template_id_6d71189b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AccordionComponent.vue?vue&type=template&id=6d71189b& */ "./resources/js/components/AdminPageComponents/AccordionComponent.vue?vue&type=template&id=6d71189b&");
/* harmony import */ var _AccordionComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AccordionComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/AdminPageComponents/AccordionComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _AccordionComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _AccordionComponent_vue_vue_type_template_id_6d71189b___WEBPACK_IMPORTED_MODULE_0__["render"],
  _AccordionComponent_vue_vue_type_template_id_6d71189b___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/AdminPageComponents/AccordionComponent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/AdminPageComponents/AccordionComponent.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************!*\
  !*** ./resources/js/components/AdminPageComponents/AccordionComponent.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AccordionComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./AccordionComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/AdminPageComponents/AccordionComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AccordionComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/AdminPageComponents/AccordionComponent.vue?vue&type=template&id=6d71189b&":
/*!***********************************************************************************************************!*\
  !*** ./resources/js/components/AdminPageComponents/AccordionComponent.vue?vue&type=template&id=6d71189b& ***!
  \***********************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AccordionComponent_vue_vue_type_template_id_6d71189b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./AccordionComponent.vue?vue&type=template&id=6d71189b& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/AdminPageComponents/AccordionComponent.vue?vue&type=template&id=6d71189b&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AccordionComponent_vue_vue_type_template_id_6d71189b___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AccordionComponent_vue_vue_type_template_id_6d71189b___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/AdminPageComponents/ContentComponent.vue":
/*!**************************************************************************!*\
  !*** ./resources/js/components/AdminPageComponents/ContentComponent.vue ***!
  \**************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ContentComponent_vue_vue_type_template_id_00b3c48a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ContentComponent.vue?vue&type=template&id=00b3c48a& */ "./resources/js/components/AdminPageComponents/ContentComponent.vue?vue&type=template&id=00b3c48a&");
/* harmony import */ var _ContentComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ContentComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/AdminPageComponents/ContentComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ContentComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ContentComponent_vue_vue_type_template_id_00b3c48a___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ContentComponent_vue_vue_type_template_id_00b3c48a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/AdminPageComponents/ContentComponent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/AdminPageComponents/ContentComponent.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************!*\
  !*** ./resources/js/components/AdminPageComponents/ContentComponent.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ContentComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./ContentComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/AdminPageComponents/ContentComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ContentComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/AdminPageComponents/ContentComponent.vue?vue&type=template&id=00b3c48a&":
/*!*********************************************************************************************************!*\
  !*** ./resources/js/components/AdminPageComponents/ContentComponent.vue?vue&type=template&id=00b3c48a& ***!
  \*********************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ContentComponent_vue_vue_type_template_id_00b3c48a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./ContentComponent.vue?vue&type=template&id=00b3c48a& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/AdminPageComponents/ContentComponent.vue?vue&type=template&id=00b3c48a&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ContentComponent_vue_vue_type_template_id_00b3c48a___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ContentComponent_vue_vue_type_template_id_00b3c48a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/PageComponents.vue":
/*!****************************************************!*\
  !*** ./resources/js/components/PageComponents.vue ***!
  \****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _PageComponents_vue_vue_type_template_id_03117bb0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./PageComponents.vue?vue&type=template&id=03117bb0& */ "./resources/js/components/PageComponents.vue?vue&type=template&id=03117bb0&");
/* harmony import */ var _PageComponents_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./PageComponents.vue?vue&type=script&lang=js& */ "./resources/js/components/PageComponents.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _PageComponents_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _PageComponents_vue_vue_type_template_id_03117bb0___WEBPACK_IMPORTED_MODULE_0__["render"],
  _PageComponents_vue_vue_type_template_id_03117bb0___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/PageComponents.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/PageComponents.vue?vue&type=script&lang=js&":
/*!*****************************************************************************!*\
  !*** ./resources/js/components/PageComponents.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PageComponents_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./PageComponents.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/PageComponents.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PageComponents_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/PageComponents.vue?vue&type=template&id=03117bb0&":
/*!***********************************************************************************!*\
  !*** ./resources/js/components/PageComponents.vue?vue&type=template&id=03117bb0& ***!
  \***********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PageComponents_vue_vue_type_template_id_03117bb0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./PageComponents.vue?vue&type=template&id=03117bb0& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/PageComponents.vue?vue&type=template&id=03117bb0&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PageComponents_vue_vue_type_template_id_03117bb0___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PageComponents_vue_vue_type_template_id_03117bb0___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



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



/***/ }),

/***/ "./resources/js/components/PagesFormComponent.vue":
/*!********************************************************!*\
  !*** ./resources/js/components/PagesFormComponent.vue ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _PagesFormComponent_vue_vue_type_template_id_749c9260___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./PagesFormComponent.vue?vue&type=template&id=749c9260& */ "./resources/js/components/PagesFormComponent.vue?vue&type=template&id=749c9260&");
/* harmony import */ var _PagesFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./PagesFormComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/PagesFormComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _PagesFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _PagesFormComponent_vue_vue_type_template_id_749c9260___WEBPACK_IMPORTED_MODULE_0__["render"],
  _PagesFormComponent_vue_vue_type_template_id_749c9260___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/PagesFormComponent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/PagesFormComponent.vue?vue&type=script&lang=js&":
/*!*********************************************************************************!*\
  !*** ./resources/js/components/PagesFormComponent.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PagesFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./PagesFormComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/PagesFormComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PagesFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/PagesFormComponent.vue?vue&type=template&id=749c9260&":
/*!***************************************************************************************!*\
  !*** ./resources/js/components/PagesFormComponent.vue?vue&type=template&id=749c9260& ***!
  \***************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PagesFormComponent_vue_vue_type_template_id_749c9260___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./PagesFormComponent.vue?vue&type=template&id=749c9260& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/PagesFormComponent.vue?vue&type=template&id=749c9260&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PagesFormComponent_vue_vue_type_template_id_749c9260___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PagesFormComponent_vue_vue_type_template_id_749c9260___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/RecursivePageTableRowNode.vue":
/*!***************************************************************!*\
  !*** ./resources/js/components/RecursivePageTableRowNode.vue ***!
  \***************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _RecursivePageTableRowNode_vue_vue_type_template_id_aee17718___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./RecursivePageTableRowNode.vue?vue&type=template&id=aee17718& */ "./resources/js/components/RecursivePageTableRowNode.vue?vue&type=template&id=aee17718&");
/* harmony import */ var _RecursivePageTableRowNode_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./RecursivePageTableRowNode.vue?vue&type=script&lang=js& */ "./resources/js/components/RecursivePageTableRowNode.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _RecursivePageTableRowNode_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _RecursivePageTableRowNode_vue_vue_type_template_id_aee17718___WEBPACK_IMPORTED_MODULE_0__["render"],
  _RecursivePageTableRowNode_vue_vue_type_template_id_aee17718___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/RecursivePageTableRowNode.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/RecursivePageTableRowNode.vue?vue&type=script&lang=js&":
/*!****************************************************************************************!*\
  !*** ./resources/js/components/RecursivePageTableRowNode.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RecursivePageTableRowNode_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./RecursivePageTableRowNode.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/RecursivePageTableRowNode.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RecursivePageTableRowNode_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/RecursivePageTableRowNode.vue?vue&type=template&id=aee17718&":
/*!**********************************************************************************************!*\
  !*** ./resources/js/components/RecursivePageTableRowNode.vue?vue&type=template&id=aee17718& ***!
  \**********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RecursivePageTableRowNode_vue_vue_type_template_id_aee17718___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./RecursivePageTableRowNode.vue?vue&type=template&id=aee17718& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/RecursivePageTableRowNode.vue?vue&type=template&id=aee17718&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RecursivePageTableRowNode_vue_vue_type_template_id_aee17718___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RecursivePageTableRowNode_vue_vue_type_template_id_aee17718___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/RecursiveTableRow.vue":
/*!*******************************************************!*\
  !*** ./resources/js/components/RecursiveTableRow.vue ***!
  \*******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _RecursiveTableRow_vue_vue_type_template_id_46c51fa3___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./RecursiveTableRow.vue?vue&type=template&id=46c51fa3& */ "./resources/js/components/RecursiveTableRow.vue?vue&type=template&id=46c51fa3&");
/* harmony import */ var _RecursiveTableRow_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./RecursiveTableRow.vue?vue&type=script&lang=js& */ "./resources/js/components/RecursiveTableRow.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _RecursiveTableRow_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./RecursiveTableRow.vue?vue&type=style&index=0&lang=scss& */ "./resources/js/components/RecursiveTableRow.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _RecursiveTableRow_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _RecursiveTableRow_vue_vue_type_template_id_46c51fa3___WEBPACK_IMPORTED_MODULE_0__["render"],
  _RecursiveTableRow_vue_vue_type_template_id_46c51fa3___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/RecursiveTableRow.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/RecursiveTableRow.vue?vue&type=script&lang=js&":
/*!********************************************************************************!*\
  !*** ./resources/js/components/RecursiveTableRow.vue?vue&type=script&lang=js& ***!
  \********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RecursiveTableRow_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./RecursiveTableRow.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/RecursiveTableRow.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RecursiveTableRow_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/RecursiveTableRow.vue?vue&type=style&index=0&lang=scss&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/components/RecursiveTableRow.vue?vue&type=style&index=0&lang=scss& ***!
  \*****************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_sass_loader_dist_cjs_js_ref_9_3_node_modules_vue_loader_lib_index_js_vue_loader_options_RecursiveTableRow_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader!../../../node_modules/css-loader!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/src??ref--9-2!../../../node_modules/sass-loader/dist/cjs.js??ref--9-3!../../../node_modules/vue-loader/lib??vue-loader-options!./RecursiveTableRow.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/RecursiveTableRow.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_sass_loader_dist_cjs_js_ref_9_3_node_modules_vue_loader_lib_index_js_vue_loader_options_RecursiveTableRow_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_sass_loader_dist_cjs_js_ref_9_3_node_modules_vue_loader_lib_index_js_vue_loader_options_RecursiveTableRow_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_sass_loader_dist_cjs_js_ref_9_3_node_modules_vue_loader_lib_index_js_vue_loader_options_RecursiveTableRow_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_sass_loader_dist_cjs_js_ref_9_3_node_modules_vue_loader_lib_index_js_vue_loader_options_RecursiveTableRow_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_sass_loader_dist_cjs_js_ref_9_3_node_modules_vue_loader_lib_index_js_vue_loader_options_RecursiveTableRow_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/components/RecursiveTableRow.vue?vue&type=template&id=46c51fa3&":
/*!**************************************************************************************!*\
  !*** ./resources/js/components/RecursiveTableRow.vue?vue&type=template&id=46c51fa3& ***!
  \**************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RecursiveTableRow_vue_vue_type_template_id_46c51fa3___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./RecursiveTableRow.vue?vue&type=template&id=46c51fa3& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/RecursiveTableRow.vue?vue&type=template&id=46c51fa3&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RecursiveTableRow_vue_vue_type_template_id_46c51fa3___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RecursiveTableRow_vue_vue_type_template_id_46c51fa3___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



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



/***/ }),

/***/ "./resources/js/components/TemplatesFormComponent.vue":
/*!************************************************************!*\
  !*** ./resources/js/components/TemplatesFormComponent.vue ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _TemplatesFormComponent_vue_vue_type_template_id_f18a93ea___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./TemplatesFormComponent.vue?vue&type=template&id=f18a93ea& */ "./resources/js/components/TemplatesFormComponent.vue?vue&type=template&id=f18a93ea&");
/* harmony import */ var _TemplatesFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./TemplatesFormComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/TemplatesFormComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _TemplatesFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _TemplatesFormComponent_vue_vue_type_template_id_f18a93ea___WEBPACK_IMPORTED_MODULE_0__["render"],
  _TemplatesFormComponent_vue_vue_type_template_id_f18a93ea___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/TemplatesFormComponent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/TemplatesFormComponent.vue?vue&type=script&lang=js&":
/*!*************************************************************************************!*\
  !*** ./resources/js/components/TemplatesFormComponent.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TemplatesFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./TemplatesFormComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/TemplatesFormComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TemplatesFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/TemplatesFormComponent.vue?vue&type=template&id=f18a93ea&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/components/TemplatesFormComponent.vue?vue&type=template&id=f18a93ea& ***!
  \*******************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TemplatesFormComponent_vue_vue_type_template_id_f18a93ea___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./TemplatesFormComponent.vue?vue&type=template&id=f18a93ea& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/TemplatesFormComponent.vue?vue&type=template&id=f18a93ea&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TemplatesFormComponent_vue_vue_type_template_id_f18a93ea___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TemplatesFormComponent_vue_vue_type_template_id_f18a93ea___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/UsersComponent.vue":
/*!****************************************************!*\
  !*** ./resources/js/components/UsersComponent.vue ***!
  \****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _UsersComponent_vue_vue_type_template_id_47d47080___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./UsersComponent.vue?vue&type=template&id=47d47080& */ "./resources/js/components/UsersComponent.vue?vue&type=template&id=47d47080&");
/* harmony import */ var _UsersComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./UsersComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/UsersComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _UsersComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _UsersComponent_vue_vue_type_template_id_47d47080___WEBPACK_IMPORTED_MODULE_0__["render"],
  _UsersComponent_vue_vue_type_template_id_47d47080___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/UsersComponent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/UsersComponent.vue?vue&type=script&lang=js&":
/*!*****************************************************************************!*\
  !*** ./resources/js/components/UsersComponent.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_UsersComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./UsersComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/UsersComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_UsersComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/UsersComponent.vue?vue&type=template&id=47d47080&":
/*!***********************************************************************************!*\
  !*** ./resources/js/components/UsersComponent.vue?vue&type=template&id=47d47080& ***!
  \***********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_UsersComponent_vue_vue_type_template_id_47d47080___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./UsersComponent.vue?vue&type=template&id=47d47080& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/UsersComponent.vue?vue&type=template&id=47d47080&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_UsersComponent_vue_vue_type_template_id_47d47080___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_UsersComponent_vue_vue_type_template_id_47d47080___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/UsersFormComponent.vue":
/*!********************************************************!*\
  !*** ./resources/js/components/UsersFormComponent.vue ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _UsersFormComponent_vue_vue_type_template_id_cb4476c8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./UsersFormComponent.vue?vue&type=template&id=cb4476c8& */ "./resources/js/components/UsersFormComponent.vue?vue&type=template&id=cb4476c8&");
/* harmony import */ var _UsersFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./UsersFormComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/UsersFormComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _UsersFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _UsersFormComponent_vue_vue_type_template_id_cb4476c8___WEBPACK_IMPORTED_MODULE_0__["render"],
  _UsersFormComponent_vue_vue_type_template_id_cb4476c8___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/UsersFormComponent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/UsersFormComponent.vue?vue&type=script&lang=js&":
/*!*********************************************************************************!*\
  !*** ./resources/js/components/UsersFormComponent.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_UsersFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./UsersFormComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/UsersFormComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_UsersFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/UsersFormComponent.vue?vue&type=template&id=cb4476c8&":
/*!***************************************************************************************!*\
  !*** ./resources/js/components/UsersFormComponent.vue?vue&type=template&id=cb4476c8& ***!
  \***************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_UsersFormComponent_vue_vue_type_template_id_cb4476c8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./UsersFormComponent.vue?vue&type=template&id=cb4476c8& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/UsersFormComponent.vue?vue&type=template&id=cb4476c8&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_UsersFormComponent_vue_vue_type_template_id_cb4476c8___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_UsersFormComponent_vue_vue_type_template_id_cb4476c8___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);