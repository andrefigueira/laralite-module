export default {
    uuidv4 () {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    },

    createErrorsList(errorsObject) {
        let errors = '<ul class="errors">';
        for (let [key, value] of Object.entries(errorsObject)) {
            errors += '<li>' + value + '</li>';
        }
        errors += '</ul>';

        return errors;
    },

    loadComponent(namespace, component) {
        Vue.component(component, () => import('./components/' + namespace + component + '.vue'));
    },

    isInView(elem) {
        return $(elem).offset().top - $(window).scrollTop() < $(elem).height() ;
    }
}
