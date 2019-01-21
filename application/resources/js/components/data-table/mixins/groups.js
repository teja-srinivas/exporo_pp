import each from 'lodash/each';
import findIndex from 'lodash/findIndex';
import groupBy from 'lodash/groupBy';
import map from 'lodash/map';
import orderBy from 'lodash/orderBy';
import reject from 'lodash/reject';

import toggleInArray from '../../../utils/toggleInArray';

import formatters from '../formatters';
import bus, { TOGGLE_DETAILS } from '../events';

export default {
  props: {
    groups: {
      type: Array,
      default: () => [],
    },
  },

  data() {
    return {
      localGroups: this.groupable ? this.groups : [],
      expanded: [],
    };
  },

  created() {
    bus.$on(TOGGLE_DETAILS, this.toggleGroupDetails);
  },

  destroyed() {
    bus.$off(TOGGLE_DETAILS, this.toggleGroupDetails);
  },

  computed: {
    groupCount() {
      return this.localGroups.length;
    },

    totalAggregateValues() {
      if (!this.totalAggregates) {
        return {};
      }

      return map(this.columnsOptimized, (column) => {
        const aggregate = column.formatter.defaultAggregator;
        const aggregator = column.formatter.aggregates[aggregate];

        let value = null;

        if (aggregator !== undefined) {
          value = formatters[aggregator.format].format(
              aggregator.calculate(map(this.filtered, column.name)),
              column.options,
          );
        }

        return {
          name: column.name,
          align: column.formatter.align,
          value,
        }
      });
    },
  },

  methods: {
    /**
     * Indicates if our content is already being grouped by
     * the given column.
     *
     * @param column
     * @return {boolean}
     */
    hasGroupBy(column) {
      return findIndex(
        this.localGroups,
        group => group.column === column.name
      ) >= 0;
    },

    /**
     * Indicates if we can add a group by for this given column.
     * Columns can choose if they want to be grouped by or not.
     *
     * @param column
     * @return {boolean}
     */
    canBeGroupedBy(column) {
      return !this.hasGroupBy(column) && column.groupBy === true;
    },

    /**
     * Adds the given column to our "group by" clause.
     *
     * @param column
     */
    addGroupBy(column) {
      if (!this.canBeGroupedBy(column)) {
        return;
      }

      this.localGroups.push({
        column: column.name,
        sort: {
          name: column.name,
          order: 'asc',
        },
      });
    },

    /**
     * Removes the given group by clause.
     * Can either be a string, a group or a column object.
     *
     * @param {string, object} groupOrColumn
     */
    removeGroupBy(groupOrColumn) {
      this.localGroups = reject(
        this.localGroups,
        group => {
          const column = groupOrColumn.column || groupOrColumn.name || groupOrColumn;
          return group.column === column;
        },
      );
    },

    /**
     * Collapses/Expands the given group.
     *
     * @param group
     */
    toggleGroupDetails(group) {
      toggleInArray(this.expanded, group.hash);
    },

    /**
     * Sorts the given values using the given sort parameters.
     *
     * @param values A list of values
     * @param sort An object with the column name and order (asc, desc)
     * @param orderFunc
     * @return {*}
     */
    sortValues(values, sort, orderFunc) {
      const column = sort.name;

      if (column === '' || sort.order === '') {
        return values;
      }

      return orderBy(values, [orderFunc(column)], [sort.order]);
    },

    /**
     * Maps the given values into groups.
     *
     * @param values The values to group
     * @param children The (nested) groups to use
     * @return {Array|Object}
     */
    mapGroup(values, children = [], parent = { hash: '#root', groupColumns: [] }) {
      const [group, ...sub] = children;

      // If we have no group, early exit
      if (group === undefined) {
        return this.sortValues(values, this.sort, (col) => {
          // Sort regular values by their global order
          const formatter = this.columnsByName[col].formatter;
          return obj => formatter.orderBy(obj[col]);
        });
      }

      // 1. Group our contents by a displayable name
      const column = this.columnsByName[group.column];
      const grouped = groupBy(
        values,
        obj => column.formatter.groupBy(obj[group.column], column.options, obj),
      );

      // Convert into group objects
      const groups = map(grouped, (list, key) => {
        const instance = {
          isGroup: true,
          groupColumns: [group.column, ...parent.groupColumns],
          groupValue: list[0],
          hash: `${parent.hash}.${group.column}[${key}]`,
          key,

          // Use custom column settings for the aggregates
          columns: {},
          row: {},
        };

        // Do the same recursively
        instance.values = this.mapGroup(list, sub, instance);

        // In case the group only has a single element,
        // "unwrap" it again, as it's no longer needed
        if (instance.values.length === 1) {
          return instance.values[0];
        }

        // 2. Use aggregates to create a displayable row
        each(this.columnsOptimized, (column) => {
          if (column.name === group.column) {
            return;
          }

          // Otherwise try to determine an aggregated value
          const aggregate = column.formatter.defaultAggregator;
          const aggregator = column.formatter.aggregates[aggregate];

          if (aggregator === undefined) {
            instance.columns[column.name] = {
              width: column.width,
            }
            return;
          }

          const value = aggregator.calculate(map(list, column.name));
          const format = aggregator.format;

          instance.row[column.name] = value;
          instance.columns[column.name] = {
            ...column,
            formatter: formatters[format],
            format,
          };
        });
        return instance;
      });

      // 3. Sort groups by their aggregate (using the group order)
      return this.sortValues(groups, group.sort, (col) => {
        // If it's the column we're grouping by, use the actual value
        if (group.column === col) {
          const formatter = this.columnsByName[col].formatter;
          return obj => {
            let val = obj;

            if (val.isGroup) {
              val = obj.values[0];

              // Take nested groups into account
              if (val.isGroup) {
                val = val.groupValue;
              }
            }

            return formatter.orderBy(val[col]);
          };
        }

        // Otherwise, use the aggregate
        return obj => {
          if (obj.isGroup) {
            return obj.columns[col].formatter.orderBy(obj.row[col]);
          }

          return obj[col];
        };
      });
    },
  },
};
