/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Bootstrap vue brought in for majority of frontend
 */
import {BootstrapVue, IconsPlugin} from 'bootstrap-vue'
/**
 * CKEditor used in one of the components
 */
import CKEditor from '@ckeditor/ckeditor5-vue';
/**
 * Used for validation in Vue
 */
import Vuelidate from 'vuelidate';
/**
 * Custom select in vue
 */
import vSelect from 'vue-select'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
/**
 * Form wizards, for step by step UI
 */
import VueFormWizard from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
/**
 * Used for auto-formatting inputs when inputting formats, e.g. dates
 */
import Cleave from 'cleave.js';

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

Vue.use(CKEditor);

Vue.use(Vuelidate);

Vue.use(VueFormWizard);

Vue.directive('cleave', {
    inserted: (el, binding) => {
        el.cleave = new Cleave(el, binding.value || {})
    },
    update: (el) => {
        const event = new Event('input', {bubbles: true});
        setTimeout(function () {
            el.value = el.cleave.properties.result
            el.dispatchEvent(event)
        }, 100);
    }
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i);

files.keys().map((key) => {
    let componentId = key.split('/').pop().split('.')[0];

    Vue.component(componentId, require(`${key}`).default)
});

Vue.component('v-select', vSelect);

Vue.directive('focus', {
    inserted: function (el) {
        el.focus();
    }
});

export const bus = new Vue();

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const admin = new Vue({
    el: '#app'
});
