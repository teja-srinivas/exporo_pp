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

  filterFunction: query => {
    // match <, >, = and the following number
    const match = /([!><=]{1,2})?(?:\s+)?([-\d]+)/g.exec(query);

    return (val) => {
      if (!match) {
        return true;
      }

      const number = parseInt(match[2], 10);
      const that = parseInt(val, 10);

      switch (match[1]) {
        case '>': return that > number;
        case '<': return that < number;
        case '=>': case '>=': return that >= number;
        case '=<': case '<=': return that <= number;
        case '<>': case '><': case '!': return that != number;
        default: return that == number;
      }
    };
  },

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
