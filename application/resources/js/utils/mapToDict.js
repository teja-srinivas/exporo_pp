import reduce from 'lodash/reduce';

export default (array, key) => reduce(array, (obj, element) => {
  obj[element[key]] = element;
  return obj;
}, {});
