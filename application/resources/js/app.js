
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import './icons';

import './utils/iframeToClipboard';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Vue from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import FormCheckbox from 'bootstrap-vue/es/components/form-checkbox';
import FormGroup from 'bootstrap-vue/es/components/form-group';
import FormInput from 'bootstrap-vue/es/components/form-input';
import FormRadio from 'bootstrap-vue/es/components/form-radio';
import Pagination from 'bootstrap-vue/es/components/pagination';
import Popover from 'bootstrap-vue/es/components/popover';
import Notifications from 'vue-notification';
import velocity from 'velocity-animate';
import VueDropzone from 'vue2-dropzone';

import UrlInput from './components/url-input/index.vue';
import App from './components/App.vue';
import DataTable from './components/data-table/index.vue';

Vue.use(FormCheckbox);
Vue.use(FormGroup);
Vue.use(FormInput);
Vue.use(FormRadio);
Vue.use(Pagination);
Vue.use(Popover);
Vue.use(Notifications, { velocity });

Vue.component('bonus-bundle-editor', () => import('./components/BonusBundleEditor/index.vue'));
Vue.component('commission-approval', () => import('./components/CommissionApproval/index.vue'));
Vue.component('font-awesome-icon', FontAwesomeIcon);
Vue.component('data-table', DataTable);
Vue.component('banner-viewer', () => import('./components/banner-viewer.vue'));
Vue.component('vue-dropzone', VueDropzone);
Vue.component('url-input', UrlInput);

document.addEventListener('DOMContentLoaded', () => {
  let usesVue;

  for (const el of document.querySelectorAll('vue')) {
    const component = el.dataset.is;
    if (component === undefined) {
      continue;
    }

    const props = el.dataset.props !== undefined ? JSON.parse(el.dataset.props) : {};

    if (el.dataset.html !== undefined) {
      props.innerHTML = el.innerHTML;
    }

    new Vue({
      el,
      render: createElement => createElement(component, {
        class: el.className,
        props,
      }),
    });

    usesVue = true;
  }

  // Insert global instance whenever we use a vue component on the page
  // This takes care of things like notifications
  if (usesVue) {
    const root = document.createElement('div');
    document.body.appendChild(root);

    new Vue({
      el: root,
      render: h => h(App),
    });
  }
});
