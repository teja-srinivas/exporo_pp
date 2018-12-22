import mapValues from 'lodash/mapValues';
import number from './number';

// Inherit from our number formatter
export default {
  ...number,

  // Overwrite the inherited aggregator format with our
  aggregates: mapValues(number.aggregates, aggregate => ({
    ...aggregate,
    format: 'currency',
  })),

  // Format currency in tables always with 2 fractions
  format: (val, options) => val.toLocaleString('de-DE', {
    style: 'currency',
    currency: (options.currency || 'EUR').toUpperCase(),
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }),
};
