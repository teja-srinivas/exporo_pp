import defaults from './default';

const useDisplayValue = (_, options, obj) => obj[options.name];

/**
 * Represents a formatter that shows a different value
 * when being asked to be displayed.
 */
export default {
  ...defaults,

  format: useDisplayValue,
  groupFormat: 'display',

  filterFunction: query => {
    const lowercase = query.toLowerCase();
    return (_, options, obj) => options.format(_, options, obj).indexOf(lowercase) !== -1;
  }
};
