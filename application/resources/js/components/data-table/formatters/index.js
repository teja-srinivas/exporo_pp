import currency from './currency';
import defaults from './default';
import date from './date';
import display from './display';
import number from './number';
import roles from './roles';
import user from './user';

export default {
  currency,
  date,
  display,
  number,
  roles,
  user,

  // Default formatter (fallback)
  default: defaults,
};
