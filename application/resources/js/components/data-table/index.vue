<template>
  <table
    class="table table-sm bg-white shadow-sm accent-primary table-sticky
           table-borderless table-hover table-striped table-fixed"
  >
    <!-- Column settings -->
    <thead>
      <tr>
        <select-box
          v-if="selectable"
          :size="rows.length"
          :count="selection.length"
          element="th"
          key="#global-select"
          @change="toggleGlobalSelection"
        />

        <th
          v-if="groupCount > 0"
          :width="groupCount * 32"
          :colspan="groupCount"
          key="#delimiter"
        />

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
              v-model="filters[column.name]"
              @click.stop
            >
          </template>
        </filter-button>
      </tr>
    </thead>

    <!-- Group settings -->
    <tbody
      v-show="groupCount > 0"
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
      :group-count="groupCount"
      :selectable="selectable"
      :expanded="expanded"
      :selection="selection"
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
      <tr v-if="selectable && actions.length > 0">
        <td :colspan="columnCount" class="pr-1">
          <div class="d-flex align-items-center justify-content-between">
            <div class="text-nowrap">
              <span class="text-muted">Auswahl:</span>
              <span class="text-dark">
              <strong>{{ selection.length }}</strong>
              <template v-if="selection.length === 1">Eintrag</template>
              <template v-else>Einträge</template>
            </span>
            </div>
            <div>
              <form
                v-for="action in actions"
                :key="action.label"
                :action="action.action"
                :method="action.method"
              >
                <input
                  v-for="item in selection"
                  :key="item"
                  type="hidden"
                  name="selection[]"
                  :value="item"
                >
                <button
                  :disabled="selection.length === 0"
                  class="btn btn-sm btn-outline-primary"
                  v-text="action.label"
                />
              </form>
            </div>
          </div>
        </td>
      </tr>
    </tfoot>
  </table>
</template>

<script>
import difference from 'lodash/difference';
import filter from 'lodash/filter';
import map from 'lodash/map';
import isNumber from 'lodash/isNumber';
import mapToDict from '../../utils/mapToDict';
import toggleInArray from '../../utils/toggleInArray';

import bus, { TOGGLE_SELECTION_ITEM, TOGGLE_SELECTION_GROUP } from './events';

import formatters from './formatters';

import filters from './mixins/filters';
import groups from './mixins/groups';
import selection from './mixins/selection';

import FilterButton from '../FilterButton.vue';
import SelectBox from './select-box';
import Row from './row.vue';

export default {
  mixins: [
    filters,
    groups,
    selection,
  ],

  components: {
    FilterButton,
    SelectBox,
    Row,
  },

  props: {
    primary: {
      type: String,
      default: 'id',
    },

    selectable: {
      type: Boolean,
      default: false,
    },

    filterable: {
      type: Boolean,
      default: true,
    },

    sortable: {
      type: Boolean,
      default: true,
    },

    groupable: {
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

    actions: {
      type: Array,
      default: () => [],
    },

    defaultSort: {
      type: Object,
      default: () => ({
        name: '',
        order: 'asc',
      }),
    },
  },

  data() {
    return {
      selection: [],
      sort: this.defaultSort,
    };
  },

  created() {
    bus.$on(TOGGLE_SELECTION_ITEM, this.toggleItemSelection);
    bus.$on(TOGGLE_SELECTION_GROUP, this.toggleGroupSelection);
  },

  destroyed() {
    bus.$on(TOGGLE_SELECTION_ITEM, this.toggleItemSelection);
    bus.$on(TOGGLE_SELECTION_GROUP, this.toggleGroupSelection);
  },

  computed: {
    /**
     * The number of actual columns our table has.
     * Will change depending on the settings.
     *
     * @return {*}
     */
    columnCount() {
      return this.columns.length + (this.selectable ? 1 : 0) + this.groupCount;
    },

    columnsOptimized() {
      // Make all columns equal-sized by default
      const equalWidth = `${((1 / this.columns.length) * 100).toFixed(3)}%`;

      return map(this.columns, (column) => {
        const defaultFormat = 'default';
        const formatter = formatters[column.format || defaultFormat];

        const groupBy = this.groupable && column.groupBy;

        // Auto-expand column widths so we don't have to worry about button sizes
        const width = isNumber(column.width)
          ? column.width + (groupBy ? 28 : 0) + (this.sortable ? 28 : 0)
          : column.width !== undefined ? column.width : equalWidth;

        return {
          // Defaults
          format: defaultFormat,
          options: {},

          // User settings
          ...column,
          groupBy,
          width,

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
     * Calculates the root group for our table.
     * This contains all the sub-groups (ordered, filtered and the like).
     *
     * @return {Object}
     */
    rootRows() {
      return this.mapGroup(this.filtered, this.localGroups);
    },
  },

  methods: {
    toggleItemSelection(item) {
      toggleInArray(this.selection, item[this.primary]);
    },

    toggleGroupSelection(group) {
      const isFullySelected = this.isFullySelected(group);
      const selectionIds = this.getSelectionKeysInGroup(group);

      if (isFullySelected) {
        this.selection = difference(this.selection, selectionIds);
        return;
      }

      this.selection.push(...filter(selectionIds, id => this.selection.indexOf(id) < 0));
    },

    toggleGlobalSelection() {
      if (this.selection.length === this.rows.length) {
        this.selection = [];
        return;
      }

      this.selection = map(this.rows, value => value[this.primary]);
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
