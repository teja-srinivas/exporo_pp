<template>
  <div :class="$style.overflowHidden" class="border rounded">
    <table class="table table-borderless table-hover table-sm table-striped mb-0" style="table-layout: fixed">
      <colgroup>
        <col width="40%">
        <col width="40%">
        <col width="20%">
        <col width="90">
      </colgroup>
      <thead>
      <tr>
        <th>Typ</th>
        <th>Für</th>
        <th class="text-right">Wert</th>
        <th class="text-right">
          <button
            class="btn btn-sm btn-link p-0"
            type="button"
            @click="showCreateItem"
          >
            <font-awesome-icon
              :class="$style.ignoreEvents"
              icon="plus"
            />

            Hinzufügen
          </button>
        </th>
      </tr>
      </thead>
      <tbody v-if="items.length > 0">
        <template v-for="(group, typeId) in itemsById">
          <tr
            v-for="(item, index) in group"
            :key="`${typeId}-${index}`"
          >
            <td
              v-if="index === 0"
              :rowspan="group.length"
              class="border-top bg-white"
            >
              <strong v-text="commissionTypes[typeId]"></strong>
            </td>

            <td
              :class="{ 'border-top': index === 0 }"
              class="align-middle"
            >
              {{ calculationTypes[item.calculationType] }}

              <div
                v-if="item.overhead"
                class="badge badge-info"
              >
                Overhead
              </div>
            </td>

            <td
              :class="{ 'border-top': index === 0 }"
              class="text-right align-bottom"
            >
              <!-- Form field generation for saving it in the DB -->
              <input type="hidden" :name="inputPrefix(item, 'type_id')" :value="item.type">
              <input type="hidden" :name="inputPrefix(item, 'calculation_type')" :value="item.calculationType">
              <input type="hidden" :name="inputPrefix(item, 'value')" :value="item.value">
              <input type="hidden" :name="inputPrefix(item, 'is_percentage')" :value="item.isPercentage ? 1 : 0">
              <input type="hidden" :name="inputPrefix(item, 'is_overhead')" :value="item.overhead ? 1 : 0">

              <span
                :class="$style.leadingNone"
                class="lead"
                v-text="formatValue(item)"
              />
            </td>

            <td
              :class="{ 'border-top': index === 0 }"
              class="text-right"
            >
              <div class="d-flex justify-content-between">
                <button
                  :id="makeEditId(item)"
                  type="button"
                  class="btn btn-outline-primary border-0 p-1 btn-sm"
                  @click="e => showEditor(item, e.target)"
                >
                  <font-awesome-icon
                    :class="$style.ignoreEvents"
                    fixed-width
                    icon="pen"
                  />
                </button>

                <button
                  type="button"
                  class="btn btn-outline-primary border-0 p-1 btn-sm"
                  @click="copyItem(item)"
                >
                  <font-awesome-icon
                    :class="$style.ignoreEvents"
                    fixed-width
                    icon="copy"
                  />
                </button>

                <button
                  type="button"
                  class="btn btn-outline-danger border-0 p-1 btn-sm"
                >
                  <font-awesome-icon
                    :class="$style.ignoreEvents"
                    fixed-width
                    icon="trash"
                  />
                </button>
              </div>
            </td>
          </tr>
        </template>
      </tbody>
      <tbody v-else>
      <tr>
        <td colspan="4" class="disabled text-muted text-center">
          Kein Vergütungsschema angegeben
        </td>
      </tr>
      </tbody>
    </table>

    <editor
      :commission-types="commissionTypes"
      :calculation-types="calculationTypes"
      :target="editTarget"
      :value="editItem"
      @input="udpateOrAddItem"
      @close="editItem = null"
    />
  </div>
</template>

<script>
import map from 'lodash/map';
import findIndex from 'lodash/findIndex';
import groupBy from 'lodash/groupBy';
import { formatMoney, formatNumber } from '../../utils/formatters';
import Editor from './Editor.vue';

let tempIdCounter = -1;

export default {
  components: {
    Editor,
  },

  props: {
    bonuses: {
      type: Array,
      default: () => [],
    },

    commissionTypes: {
      type: Object,
      required: true,
    },

    calculationTypes: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      editItem: null,
      editTarget: null,
      items: map(this.bonuses, bonus => ({
        id: bonus.id,
        overhead: bonus.is_overhead,
        isPercentage: bonus.is_percentage,
        value: bonus.value,
        type: bonus.type_id,
        calculationType: bonus.calculation_type,
      })),
    };
  },

  computed: {
    itemsById() {
      return groupBy(this.items, 'type');
    },
  },

  methods: {
    showCreateItem(event) {
      this.showEditor(({
        id: 0, // indicates that this is a fresh entry
        type: '',
        calculationType: '',
        value: 1,
        isPercentage: true,
        overhead: false,
      }), event.target);
    },

    copyItem(item) {
      const copy = {
        ...item,
        id: tempIdCounter--,
      };

      this.addItem(copy);

      this.$nextTick(() => {
        this.showEditor(copy, this.makeEditId(copy));
      });
    },

    showEditor(item, target) {
      if (this.editItem !== null && this.editItem.id === item.id) {
        return;
      }

      // Clear the item first to close the popup
      // so the new editTarget takes effect
      this.editItem = null;

      this.$nextTick(() => {
        this.editTarget = target;
        this.editItem = {
          ...item,
          value: item.isPercentage ? item.value * 100 : item.value,
        };
      })
    },

    udpateOrAddItem(item) {
      if (item.isPercentage) {
        item.value = item.value / 100;
      }

      if (item.id === 0) {
        item.id = tempIdCounter--; // temporary Id until we saved the entry
        this.addItem(item);
        return;
      }

      // TODO API call
      const index = findIndex(this.items, ({ id }) => id === item.id);
      this.$set(this.items, index, item);
    },

    addItem(item) {
      this.items.push(item);
      this.editItem = null;
    },

    formatValue({ isPercentage, value }) {
      return isPercentage ? `${formatNumber(value * 100)} %` : formatMoney(value);
    },

    inputPrefix(item, key) {
      return `bonuses[${item.id}][${key}]`;
    },

    makeEditId(item) {
      return `bb-edit-${item.id}`;
    },
  },
};
</script>

<style lang="scss" module>
  .overflowHidden {
    overflow: hidden;
  }

  .ignoreEvents {
    pointer-events: none;
  }

  .leadingNone {
    line-height: 1;
  }
</style>
