<template>
  <tbody v-if="true">
    <template v-for="(row, index) in rows">
      <!-- Groups -->
      <template v-if="row.isGroup">
        <!-- Group Header (aggregated row) -->
        <tr
          :key="`${row.key}-header`"
          :class="{
            'border-bottom': depth === 0 || index < rows.length - 1 || showDetails(row),
            'border-top': index > 0 && (!rows[index - 1].isGroup || !showDetails(rows[index - 1])),
            [$style.trChildStart]: index === 0,
            [$style.trChildEnd]: depth > 0 && index === rows.length - 1,
          }"
          class="font-weight-bold"
        >
          <td
            v-if="depth > 0"
            :width="depth * 32"
            class="bg-light border-right"
          />

          <select-box
            v-if="selectable"
            :size="getNonGroupValueCount(row)"
            :count="getSelectedValueCount(row)"
            @change="toggleGroupSelection(row)"
          />

          <td
            :class="$style.tdChevron"
            width="32"
            @click="toggleDetails(row)"
          >
            <font-awesome-icon
              icon="chevron-right"
              :rotation="showDetails(row) ? 90 : undefined"
              :class="$style.chevron"
            />
          </td>

          <template v-for="column in columns">
            <!-- Use the regular cell when it's the one we're grouping by -->
            <cell
              v-if="row.groupColumns.indexOf(column.name) >= 0"
              :key="column.name"
              :column="column"
              :value="formatValue(row.groupValue, column)"
            />

            <!-- otherwise, use the aggregates -->
            <cell
              v-else
              :key="column.name"
              :column="row.columns[column.name]"
              :value="formatValue(row.row, row.columns[column.name])"
            />
          </template>
        </tr>

        <!-- Group contents -->
        <tr
          v-if="showDetails(row)"
          :class="$style.innerTable"
          :key="`${row.key}-contents`"
        >
          <td
            :colspan="columnCount + (depth > 0 ? 1 : 0)"
            :class="{ 'border-bottom': depth === 0 || index < rows.length - 1 }"
            class="p-0"
          >
            <table
              class="table table-sm table-striped bg-transparent m-0 table-fixed"
            >
              <table-group v-bind="buildChildProps(row)" />
            </table>
          </td>
        </tr>
      </template>

      <!-- Regular rows -->
      <tr
        v-else
        :key="row[primary]"
        :class="{
          [$style.trChildStart]: index === 0,
          [$style.trChildEnd]: depth > 0 && index === rows.length - 1,
        }"
      >
        <td
          v-if="depth > 0"
          :width="depth * 32"
          class="bg-light border-right"
        />

        <select-box
          v-if="selectable"
          :size="1"
          :count="itemIsSelected(row) ? 1 : 0"
          element="td"
          @change="toggleItemSelection(row)"
        />

        <td
          v-if="(groupCount - depth) > 0"
          width="32"
        />

        <cell
          v-for="column in columns"
          :key="column.name"
          :column="column"
          :value="formatValue(row, column, true)"
        />
      </tr>
    </template>
  </tbody>
</template>

<script>
import get from 'lodash/get';
import isArray from 'lodash/isArray';

import SelectionMixin from './mixins/selection';

import bus, {
  TOGGLE_DETAILS,
  TOGGLE_SELECTION_GROUP,
  TOGGLE_SELECTION_ITEM,
} from './events';

import SelectBox from './select-box';
import Cell from "./cell";

export default {
  name: 'table-group',

  mixins: [
    SelectionMixin,
  ],

  components: {
    Cell,
    SelectBox,
  },

  props: {
    primary: {
      type: String,
      required: true,
    },

    selectable: {
      type: Boolean,
      required: true,
    },

    columns: {
      type: Array,
      required: true,
    },

    columnCount: {
      type: Number,
      required: true,
    },

    groupCount: {
      type: Number,
      required: true,
    },

    rows: {
      type: [Array, Object],
      required: true,
    },

    depth: {
      type: Number,
      required: true,
    },

    selection: {
      type: Array,
      required: true,
    },

    expanded: {
      type: Array,
      required: true,
    },
  },

  methods: {
    isArray,

    showDetails(group) {
      return this.expanded.indexOf(group.hash) >= 0;
    },

    formatValue(row, column = {}, allowLinks = false) {
      let value = row[column.name];

      if (value === undefined || column === undefined) {
        return '';
      }

      if (column.formatter && column.formatter.format) {
        value = column.formatter.format(value, column.options, row);
      }

      if (allowLinks && column.link !== undefined) {
        return `<a href="${get(row, column.link)}">${value}</a>`;
      }

      return value;
    },

    buildChildProps(group) {
      return {
        ...this.$props,
        depth: this.depth + 1,
        rows: group.values,
      };
    },

    toggleDetails(group) {
      bus.$emit(TOGGLE_DETAILS, group);
    },

    toggleGroupSelection(group) {
      bus.$emit(TOGGLE_SELECTION_GROUP, group);
    },

    toggleItemSelection(row) {
      bus.$emit(TOGGLE_SELECTION_ITEM, row);
    },
  },
};
</script>

<style lang="scss" module>
  .innerTable {
    background-color: transparent !important;
  }

  .trChildStart {
    box-shadow: inset 0 6px 5px -5px rgba(black, 0.1);
  }

  .trChildEnd {
    box-shadow: inset 0 -6px 5px -5px rgba(black, 0.1);
  }

  .chevron {
    width: 1em !important;
  }

  .tdChevron {
    cursor: pointer;
    transition: background-color 150ms ease;

    &:hover {
      background-color: rgba(black, 0.05);
      box-shadow: inset 0 2px 6px -1px rgba(black, 0.1);
    }

    > svg {
      transition: transform 200ms ease;
    }
  }
</style>
