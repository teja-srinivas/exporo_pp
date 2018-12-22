<template>
  <table
    :class="localGroups.length === 0 ? ['table-hover', 'table-striped'] : []"
    class="table table-sm bg-white shadow-sm accent-primary table-sticky table-borderless table-fixed"
  >
    <!-- Column settings -->
    <thead>
      <tr>
        <select-box
          v-if="selectable"
          element="th"
        />

        <th :width="localGroups.length * 32" />

        <filter-button
          v-for="column in columnsOptimized"
          v-model="sort"
          :key="column.name"
          :class="{
            [$style.th]: true,
            [$style.active]: sort.name === column.name,
          }"
          :name="column.name"
          :width="column.width"
          element="th"
        >
          {{ column.label }}

          <div
            v-if="canBeGroupedBy(column)"
            slot="action"
            :class="$style.thButton"
            @click.stop="addGroupBy(column)"
            class="px-1 mr-1"
          >
            <font-awesome-icon icon="plus" size="sm" />
          </div>

          <template
            v-if="filterable"
            slot="below"
          >
            <input
              :class="$style.input"
              class="form-control form-control-sm mt-1"
              type="text"
              @click.stop
            >
          </template>
        </filter-button>
      </tr>
    </thead>

    <!-- Group settings -->
    <tbody
      v-show="localGroups.length > 0"
      key="#group-settings"
    >
      <tr>
        <td
          :colspan="columnCount"
          class="p-2 bg-white border-bottom leading-sm"
        >
          <div class="d-flex align-items-center flex-wrap small font-weight-bold">
            <div>Gruppiert nach:</div>
            <div
              v-for="group in localGroups"
              :key="group.column"
              :class="$style.group"
              class="d-flex align-items-center p-1 ml-2"
            >
              <div class="p-1">
                {{ columnsByName[group.column].label }}
              </div>

              <filter-button
                :value="group.sort"
                :name="group.sort.name"
                :class="$style.groupFilter"
                class="pl-1 pr-2 bg-light mr-1"
                @input="({ order }) => group.sort.order = order"
              >
                <select
                  :class="$style.groupSelect"
                  class="m-0 p-1 border-0 bg-transparent mr-1 border-right rounded-0"
                  v-model="group.sort.name"
                  @click.stop
                >
                  <option disabled>Sortierung:</option>
                  <option
                    v-for="column in columnsOptimized"
                    v-if="column.name === group.column || column.hasAggregates"
                    :value="column.name"
                    v-text="column.label"
                  />
                </select>
              </filter-button>

              <div
                class="close px-1"
                @click="removeGroupBy(group)"
              >&times;</div>
            </div>
          </div>
        </td>
      </tr>
    </tbody>

    <!-- Table contents -->
    <row
      v-if="filtered.length > 0"
      :rows="rootRows"
      :columns="columnsOptimized"
      :column-count="columnCount"
      :selectable="selectable"
      :expanded="expanded"
      :primary="primary"
      :depth="0"
      key="#table-contents"
    />

    <tbody
      v-else
      key="#table-empty"
    >
      <tr>
        <td
          :colspan="columnCount"
          class="text-center p-3 text-muted"
        >
          Keine Einträge vorhanden
        </td>
      </tr>
    </tbody>

    <!-- Total Aggregates -->
    <tfoot>
    </tfoot>
  </table>
</template>

<script>
import map from 'lodash/map';
import mapToDict from '../../utils/mapToDict';

import formatters from './formatters';
import groups from './mixins/groups';

import FilterButton from '../FilterButton.vue';
import SelectBox from './select-box';
import Row from './row.vue';

export default {
  mixins: [
    groups,
  ],

  components: {
    FilterButton,
    SelectBox,
    Row,
  },

  props: {
    primary: {
      type: String,
      required: true,
    },

    selectable: {
      type: Boolean,
      default: false,
    },

    filterable: {
      type: Boolean,
      default: false,
    },

    columns: {
      type: Array,
      required: true,
    },

    rows: {
      type: Array,
      default: () => [],
    },
  },

  data() {
    return {
      selection: [],
      sort: {
        name: '',
        order: 'asc',
      },
    };
  },


  computed: {
    /**
     * The number of actual columns our table has.
     * Will change depending on the settings.
     *
     * @return {*}
     */
    columnCount() {
      return this.columns.length + (this.selectable ? 1 : 0) + 1; // +1 for the depth offset
    },

    columnsOptimized() {
      // Make all columns equal-sized by default
      const width = `${(1 / this.columns.length) * 100}%`;

      return map(this.columns, (column) => {
        const defaultFormat = 'default';
        const formatter = formatters[column.format || defaultFormat];

        return {
          // Defaults
          format: defaultFormat,
          options: {},
          width,

          // User settings
          ...column,

          // Custom fields
          formatter,
          hasAggregates: formatter.aggregates[formatter.defaultAggregator] !== undefined,
        };
      });
    },

    /**
     * Lookup for all columns by their internal name.
     *
     * @return {*}
     */
    columnsByName() {
      return mapToDict(this.columnsOptimized, 'name');
    },

    /**
     * Filters all rows based on the queries per column.
     *
     * @return {default.props.rows|{default, type}}
     */
    filtered() {
      return this.filterable ? this.rows : this.rows;
    },

    /**
     * Calculates the root group for our table.
     * This contains all the sub-groups (ordered, filtered and the like).
     *
     * @return {Object}
     */
    rootRows() {
      return this.mapGroup(this.filtered, this.localGroups);
    },
  },
};
</script>

<style lang="scss" module>
  @import '../../../sass/variables';

  $border-radius-group: 2rem;

  .th {
    &:hover,
    &.active {
      background-color: $gray-100;
      cursor: ns-resize;
    }
  }

  .group {
    background: $gray-200;
    // cursor: pointer;
    border-radius: $border-radius-group;
    // transition: $transition-base;

    &:hover {
      // background: $gray-300;
    }

    :global(.close) {
      border-radius: $border-radius-group;
      background-color: $white;
      opacity: 0.8;
      color: $gray-600;
      transition: $transition-fade;
    }
  }

  .thButton {
    color: $gray-400;
    cursor: pointer;
    transition: $transition-base;

    &:hover {
      color: $gray-600;
    }
  }

  .input {
    border-color: rgba($input-border-color, 0.66);
  }

  .groupFilter {
    border-radius: $border-radius-group;
  }

  .groupSelect {
    appearance: none;
  }
</style>
