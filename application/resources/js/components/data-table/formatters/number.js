import sum from 'lodash/sum';
import min from 'lodash/min';
import max from 'lodash/max';

import { formatNumber } from '../../../utils/formatters';

import defaults from './default';

export default {
  ...defaults,

  align: 'right',
  format: formatNumber,
  defaultAggregator: 'sum',

  aggregates: {
    sum: {
      label: 'Summe',
      calculate: sum,
      format: 'number',
    },

    min: {
      label: 'Minimum',
      calculate: min,
      format: 'number',
    },

    max: {
      label: 'Maximum',
      calculate: max,
      format: 'number',
    },
  },
};
