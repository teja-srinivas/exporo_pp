const moneyFormatter = new Intl.NumberFormat('de-DE', {
  style: 'currency',
  currency: 'EUR',
  minimumFractionDigits: 0,
});

const numberFormatter = new Intl.NumberFormat('de-DE');

export const formatMoney = moneyFormatter.format;
export const formatNumber = numberFormatter.format;
