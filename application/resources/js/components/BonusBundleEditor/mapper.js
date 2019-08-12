import reduce from 'lodash/reduce';
import invert from 'lodash/invert';

const localToForeignKeys = {
  id: 'id',
  type: 'type_id',
  userId: 'user_id',
  calculationType: 'calculation_type',
  value: 'value',
  isPercentage: 'is_percentage',
  overhead: 'is_overhead',
};

const foreignToLocalKeys = {
  ...invert(localToForeignKeys),
  url: 'url',
};

const remap = (obj, lookup) => reduce(obj, (result, value, key) => {
  const newKey = lookup[key];

  if (newKey !== undefined) {
    result[newKey] = value;
  }

  return result;
}, {});

export const toForeign = obj => remap(obj, localToForeignKeys);
export const toLocal = obj => remap(obj, foreignToLocalKeys);
