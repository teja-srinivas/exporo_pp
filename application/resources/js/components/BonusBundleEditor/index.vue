<template>
  <div :class="$style.overflowHidden" class="border rounded">
    <table class="table table-borderless table-hover table-sm table-striped table-fixed mb-0">
      <colgroup>
        <col width="40%">
        <col width="40%">
        <col width="20%">
        <col width="90" v-if="editable">
      </colgroup>
      <thead>
      <tr>
        <th>Typ</th>
        <th>Für</th>
        <th class="text-right">Wert</th>
        <th class="text-right" v-if="editable">
          <button
            class="btn btn-sm btn-link p-0 text-nowrap"
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
              :class="{ 'border-top': index === 0, [$style.leadingNone]: true }"
              class="text-right align-middle text-nowrap"
            >
              <!-- Form field generation for saving it in the DB -->
              <template v-if="!api">
                <input type="hidden" :name="inputPrefix(item, 'type_id')" :value="item.type">
                <input type="hidden" :name="inputPrefix(item, 'calculation_type')" :value="item.calculationType">
                <input type="hidden" :name="inputPrefix(item, 'value')" :value="item.value">
                <input type="hidden" :name="inputPrefix(item, 'is_percentage')" :value="item.isPercentage ? 1 : 0">
                <input type="hidden" :name="inputPrefix(item, 'is_overhead')" :value="item.overhead ? 1 : 0">
              </template>

              <span
                class="lead"
                v-text="formatValue(item)"
              />
            </td>

            <td
              v-if="editable"
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
                  @click="deleteItem(item)"
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
import axios from 'axios';
import each from 'lodash/each';
import filter from 'lodash/filter';
import find from 'lodash/find';
import findIndex from 'lodash/findIndex';
import groupBy from 'lodash/groupBy';
import map from 'lodash/map';
import { confirm } from '../../alert';
import { formatMoney, formatNumber } from '../../utils/formatters';
import Editor from './Editor.vue';
import { toForeign, toLocal } from './mapper';

// Temporary ID for entries until we persist them in the DB (either via a form or ajax)
let tempIdCounter = -1;

export default {
  components: {
    Editor,
  },

  props: {
    api: {
      type: String,
      default: '',
    },

    editable: {
      type: Boolean,
      default: true,
    },

    legacy: {
      type: Boolean,
      default: true,
    },

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
    let items = map(this.bonuses, toLocal);

    if (this.legacy) {
      // For the legacy mode, show only the difference
      // between the normal and the overhead bonus(es)
      const isOverhead = filter(items, ['overhead', true]);
      const isPercentage = filter(isOverhead, ['isPercentage', true]);

      each(isPercentage, item => {
        const related = this.findRelated(items, item);
        if (related) {
          item.value = item.value - related.value;
        }
      });
    }

    const data = {
      editItem: null,
      editTarget: null,
      items: items,
    };

    return data;
  },

  computed: {
    itemsById() {
      return groupBy(this.items, 'type');
    },

    editItemId() {
      return this.editItem !== null ? this.editItem.id : null;
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
      if (this.editItemId === item.id) {
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
        item.id = tempIdCounter--;
        this.addItem(item);
        return;
      }

      const index = findIndex(this.items, ({ id }) => id === item.id);
      const copy = this.items[index];

      this.updateOrRollBack(
        item,
        () => this.$set(this.items, index, copy),
      );

      this.$set(this.items, index, item);
    },

    async addItem(item) {
      const itemId = item.id;

      this.items.push(item);
      this.editItem = null;

      if (!this.api) {
        return;
      }

      try {
        const { data } = await axios.post(this.api, toForeign(item));

        if (this.editItemId === itemId) {
          this.editItem.id = data.id;
        }

        item = find(this.items, ['id', itemId]);

        if (item) {
          item.id = data.id;
        }

        this.$notify('Eintrag angelegt');

      } catch (e) {
        this.$notify({
          title: 'Fehler beim Anlegen',
          text: e.message + '. Eintrag wird wieder gelöscht.',
          type: 'error',
        });

        this.items = this.items.slice(0, this.items.length - 1);
        throw e;
      }
    },

    formatValue({ isPercentage, value }) {
      const multiplier = this.legacy ? 0.02 : 1;
      return isPercentage ? `${formatNumber(value * 100 * multiplier)} %` : formatMoney(value);
    },

    inputPrefix(item, key) {
      return `bonuses[${item.id}][${key}]`;
    },

    makeEditId(item) {
      return `bb-edit-${item.id}`;
    },

    async updateOrRollBack(bonus, rollbackCallback) {
      if (!this.api) {
        return;
      }

      try {
        await axios.put(`${this.api}/${bonus.id}`, toForeign(bonus));
        this.$notify('Änderungen gespeichert');

      } catch (e) {
        this.$notify({
          title: 'Fehler beim Speichern',
          text: e.message + '. Änderungen werden wieder zurückgesetzt.',
          type: 'error',
        });

        this.$nextTick(rollbackCallback);
        throw e;
      }
    },

    async deleteItem(item) {
      await confirm('Eintrag wirklich löschen?', async () => {
        try {
          this.items.splice(findIndex(this.items, ['id', item.id]), 1);

          if (!this.api) {
            return;
          }

          await axios.delete(`${this.api}/${item.id}`);
          this.$notify('Eintrag gelöscht');

        } catch (e) {
          this.$notify({
            title: 'Fehler beim Speichern',
            text: e.message + '. Änderungen werden wieder zurückgesetzt.',
            type: 'error',
          });

          this.items.push(item);
          throw e;
        }
      });
    },

    findRelated(items, overhead) {
      // Find the related item that has not been labeled as overhead
      return find(
        filter(items, ['overhead', false]),
        ({ type, calculationType }) => (
          type === overhead.type &&
          calculationType === overhead.calculationType
        ),
      );
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
