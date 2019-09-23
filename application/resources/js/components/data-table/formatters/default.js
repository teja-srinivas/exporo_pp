const passthrough = val => val;

export default {
  /**
   * Initial call for this column to initialize on page load.
   * Typically used to configure any subsequent calls.
   *
   * @param {object} options
   */
  initialize: null,

  /**
   * Formats the given value as appropriate by this type.
   *
   * @param name
   * @param obj
   * @param row
   * @return {*}
   */
  format: passthrough,

  /**
   * Returns a key the given object can be grouped by.
   *
   * @param obj
   * @param name
   * @param row
   * @return {string|int}
   */
  groupBy: passthrough,

  /**
   * Indicates the format for the value returned by the groupBy clause.
   */
  groupFormat: 'default',

  /**
   * Indicates whether the given value can be displayed by this formatter.
   *
   * @param value
   * @return {boolean}
   */
  isValid: (value) => (value !== undefined && value !== null),

  /**
   * Returns a value that can be used when ordering rows.
   */
  orderBy: val => (typeof val === 'string' ? val.toLowerCase()
      .replace('ä', 'ae')
      .replace('ö', 'oe')
      .replace('ü', 'ue')
      : val),

  /**
   * Returns a function that we can filter values by.
   *
   * @param {string} query
   * @return {function(*): boolean}
   */
  filterFunction: query => {
    const lowercase = query.toLowerCase();
    return val => ((typeof val !== 'string' ? `${val}` : val).toLowerCase().indexOf(lowercase) !== -1);
  },

  /**
   * A dictionary of possible aggregates that his formatter can display.
   */
  aggregates: {},

  /**
   * The key for an aggregator that will be used for grouping rows.
   */
  defaultAggregator: null,
};
