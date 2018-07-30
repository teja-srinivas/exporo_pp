
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import './icons';


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Vue from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { FormCheckbox, FormGroup, FormInput, Pagination } from 'bootstrap-vue/es/components';
import Notifications from 'vue-notification';
import velocity from 'velocity-animate'

Vue.use(FormCheckbox);
Vue.use(FormGroup);
Vue.use(FormInput);
Vue.use(Pagination);
Vue.use(Notifications, { velocity });

Vue.component('commission-approval', () => import('./components/CommissionApproval/index.vue'));
Vue.component('font-awesome-icon', FontAwesomeIcon);

document.addEventListener('DOMContentLoaded', () => {
  for (const el of document.querySelectorAll('vue')) {
    const component = el.dataset.is;
    const props = JSON.parse(el.dataset.props);

    new Vue({
      el,
      render: createElement => createElement(component, { props }),
    });
  }
});
