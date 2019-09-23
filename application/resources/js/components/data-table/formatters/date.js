import format from 'date-fns/format';
import defaults from './default';

const formatDate = (value) => {
  return format(value, 'DD.MM.YYYY');
};

export default {
  ...defaults,

  isValid: value => (typeof value === 'string' && value.length > 0),
  align: 'right',
  format: formatDate,

  filterFunction: query => raw => formatDate(raw).indexOf(query) !== -1,
};
