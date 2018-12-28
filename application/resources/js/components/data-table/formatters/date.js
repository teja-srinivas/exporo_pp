import format from 'date-fns/format';
import defaults from './default';

const formatDate = (value) => {
  return format(value, 'DD.MM.YYYY');
};

export default {
  ...defaults,

  align: 'right',
  format: formatDate,
};
