
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('slick-carousel');

import './sentry';
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
import { FormCheckboxPlugin, FormGroupPlugin, FormInputPlugin, FormRadioPlugin, PaginationPlugin, PopoverPlugin } from 'bootstrap-vue';
import Notifications from 'vue-notification';
import velocity from 'velocity-animate';
import VueDropzone from 'vue2-dropzone';

import UrlInput from './components/url-input/index.vue';
import VariableInput from './components/variable-input/index.vue';
import App from './components/App.vue';
import DataTable from './components/data-table/index.vue';
import SubuserTable from './components/SubuserTable.vue';
import VueApexCharts from 'vue-apexcharts';

Vue.use(FormCheckboxPlugin);
Vue.use(FormGroupPlugin);
Vue.use(FormInputPlugin);
Vue.use(FormRadioPlugin);
Vue.use(PaginationPlugin);
Vue.use(PopoverPlugin);
Vue.use(Notifications, { velocity });
Vue.use(VueApexCharts);

Vue.component('bonus-bundle-editor', () => import('./components/BonusBundleEditor/index.vue'));
Vue.component('commission-approval', () => import('./components/CommissionApproval/index.vue'));
Vue.component('commission-pending', () => import('./components/CommissionPending/index.vue'));
Vue.component('font-awesome-icon', FontAwesomeIcon);
Vue.component('data-table', DataTable);
Vue.component('subuser-table', SubuserTable);
Vue.component('banner-viewer', () => import('./components/banner-viewer.vue'));
Vue.component('embed-viewer', () => import('./components/embed-viewer.vue'));

Vue.component('investments-viewer', () => import('./components/dashboard/index.vue'));
Vue.component('campaign-editor', () => import('./components/campaign-editor.vue'));
Vue.component('projects-container', () => import('./components/Projects/container.vue'));
Vue.component('projects-switch', () => import('./components/Projects/switch.vue'));

Vue.component('affiliate-dashboard', () => import('./components/affiliate-dashboard.vue'));

Vue.component('vue-dropzone', VueDropzone);
Vue.component('url-input', UrlInput);
Vue.component('variable-input', VariableInput);
Vue.component('apexchart', VueApexCharts);

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
