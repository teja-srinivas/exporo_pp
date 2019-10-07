import { init } from '@sentry/browser';
import { Vue as SentryVue } from '@sentry/integrations';

import Vue from 'vue';

init({
  dsn: process.env.MIX_SENTRY_LARAVEL_DSN,
  integrations: [
    new SentryVue({
      Vue,
      attachProps: true,
    })
  ]
});
