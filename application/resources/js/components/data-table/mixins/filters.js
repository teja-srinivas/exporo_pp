import every from 'lodash/every';
import filter from 'lodash/filter';
import reduce from 'lodash/reduce';

export default {
  data() {
    return {
      filters: reduce(this.columns, (obj, column) => {
        obj[column.name] = '';
        return obj;
      }, {}),
    };
  },

  computed: {
    filterFunctions() {
      return reduce(this.columnsOptimized, (ctx, column) => {
        const query = this.filters[column.name];

        if (query.length > 0) {
          ctx[column.name] = {
            matches: column.formatter.filterFunction(query),
            value: column.formatter.format,
            options: column.options,
          };
        }

        return ctx;
      }, {});
    },

    /**
     * Filters all rows based on the queries per column.
     *
     * @return {default.props.rows|{default, type}}
     */
    filtered() {
      if (!this.filterable) {
        return this.rows;
      }

      return filter(this.rows, row => every(this.filterFunctions, (column, name) => {
        return column.matches(column.value(row[name], column.options, row));
      }));
    },
  },
};
