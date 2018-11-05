export const formatMoney = val => val.toLocaleString('de-DE', {
  style: 'currency',
  currency: 'EUR',
  minimumFractionDigits: 0,
});

export const formatNumber = val => val.toLocaleString('de-DE');
