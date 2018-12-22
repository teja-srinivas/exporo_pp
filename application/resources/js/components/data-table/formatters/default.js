const passthrough = val => val;

export default {
  /**
   * Formats the given value as appropiate by this type.
   *
   * @param obj
   * @param name
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
   * Returns a value that can be used when ordering rows.
   */
  orderBy: passthrough,

  /**
   * A dictionary of possible aggregates that his formatter can display.
   */
  aggregates: {},

  /**
   * The key for an aggregator that will be used for grouping rows.
   */
  defaultAggregator: null,
};
