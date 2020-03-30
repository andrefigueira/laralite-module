/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

// Install BootstrapVue
Vue.use(BootstrapVue);

// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin);

import CKEditor from '@ckeditor/ckeditor5-vue';

Vue.use(CKEditor);

import Vuelidate from 'vuelidate';

Vue.use(Vuelidate);

import vSelect from 'vue-select'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('pages', require('./components/PagesComponent.vue').default);
Vue.component('templates', require('./components/TemplatesComponent.vue').default);
Vue.component('users', require('./components/UsersComponent.vue').default);
Vue.component('pages-form', require('./components/PagesFormComponent.vue').default);
Vue.component('page-components', require('./components/PageComponents.vue').default);
Vue.component('recursive-page-row', require('./components/RecursivePageTableRowNode.vue').default);
Vue.component('recursive-table-row', require('./components/RecursiveTableRow.vue').default);
Vue.component('templates-form', require('./components/TemplatesFormComponent.vue').default);
Vue.component('users-form', require('./components/UsersFormComponent.vue').default);
Vue.component('admin-content-component', require('./components/AdminPageComponents/ContentComponent.vue').default);
Vue.component('admin-accordion-component', require('./components/AdminPageComponents/AccordionComponent.vue').default);
Vue.component('accordion-component', require('./components/FrontEndComponents/AccordionComponent.vue').default);

Vue.component('portal-renderer', require('./components/PortalRenderer.vue').default);

// Find way to load these dynamically
Vue.component('content-component', require('./components/FrontEndComponents/ContentComponent.vue').default);
Vue.component('carousel-component', require('./components/FrontEndComponents/CarouselComponent.vue').default);
Vue.component('page-loaded', require('./components/FrontEndComponents/PageLoadedComponent.vue').default);
Vue.component('side-nav', require('./components/FrontEndComponents/SideNavComponent.vue').default);
Vue.component('top-nav', require('./components/FrontEndComponents/TopNavComponent.vue').default);
Vue.component('footer-component', require('./components/FrontEndComponents/FooterComponent.vue').default);
Vue.component('accordion-component', require('./components/FrontEndComponents/AccordionComponent.vue').default);
Vue.component('trapmusicparralax-component', require('./components/FrontEndComponents/TrapMusicParralaxComponent.vue').default);
Vue.component('location-component', require('./components/FrontEndComponents/LocationComponent.vue').default);
Vue.component('contact-component', require('./components/FrontEndComponents/ContactComponent.vue').default);
Vue.component('waiver-component', require('./components/FrontEndComponents/WaiverComponent.vue').default);

Vue.component('v-select', vSelect);

export const bus = new Vue();

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    components: {

    }
});
