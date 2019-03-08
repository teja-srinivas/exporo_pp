import mapValues from 'lodash/mapValues';
import number from './number';

const formatters = {};

const getFormatter = currency => {
  const ucCurrency = currency.toUpperCase();
  let formatter = formatters[ucCurrency];

  if (formatter === undefined) {
    formatter = new Intl.NumberFormat('de-DE', {
      style: 'currency',
      currency: ucCurrency,
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });

    formatters[ucCurrency] = formatter;
  }

  return formatter;
};

// Inherit from our number formatter
export default {
  ...number,

  // Overwrite the inherited aggregator format with our
  aggregates: mapValues(number.aggregates, aggregate => ({
    ...aggregate,
    format: 'currency',
  })),

  // Format currency in tables always with 2 fractions
  format: (val, options) => getFormatter(options.currency || 'EUR').format(val),
};
