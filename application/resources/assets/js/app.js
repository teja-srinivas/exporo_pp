
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Vue from 'vue';

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
