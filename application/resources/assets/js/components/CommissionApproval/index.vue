<!--
  v1 of the commissions table
  I'll clean this up later when everything got approved.
-->
<template>
  <div :class="{[$style.disabled]: isLoading}">
    <notifications
      animation-type="velocity"
      :animation="animation"
      position="bottom right"
    />

    <!-- Filter -->
    <div class="card border-0 shadow-sm mb-2 rounded align-items-center">
      <div class="p-1">
        <strong>Legende:</strong>

        <b-form-checkbox class="mx-2" :class="$style.green" :checked="true" disabled>
          Überprüft
        </b-form-checkbox>

        <b-form-checkbox class="mx-2" :class="$style.yellow" :checked="true" disabled>
          Zurückhalten
        </b-form-checkbox>

        <b-form-checkbox class="mx-2" :class="$style.red" :checked="true" disabled>
          Ablehnen
        </b-form-checkbox>
      </div>
    </div>

    <!-- Content -->
    <div class="card border-0 shadow-sm accent-primary">
      <table :class="$style.table" class="table table-sm table-hover table-striped mb-0 table-sticky">
        <colgroup>
          <col width="25">
          <col width="75">
          <col width="50%">
          <col width="50%">
          <col width="165">
        </colgroup>

        <!-- Header -->
        <thead>
          <tr>
            <th class="border-bottom-1 align-top pr-0" colspan="2">
              <div class="d-flex flex-column justify-content-between">
                <div>Status</div>

                <div class="d-inline-flex align-self-end mt-2">
                  <b-form-checkbox
                    :checked="filter.reviewed"
                    @change="val => {
                      this.filter.onHold = false;
                      this.filter.rejected = false;
                      this.filter.reviewed = val;
                    }"
                    :class="$style.green"
                    class="m-0"
                  />

                  <b-form-checkbox
                    :checked="filter.onHold"
                    @change="val => {
                      this.filter.onHold = val;
                      this.filter.rejected = false;
                      this.filter.reviewed = false;
                    }"
                    :class="$style.yellow"
                    class="m-0"
                  />

                  <b-form-checkbox
                    :checked="filter.rejected"
                    @change="val => {
                      this.filter.onHold = false;
                      this.filter.rejected = val;
                      this.filter.reviewed = false;
                    }"
                    :class="$style.red"
                    class="m-0"
                  />
                </div>
              </div>
            </th>

            <filter-button element="th" v-model="sort" name="user" class="border-bottom-1">
              Partner
              <input
                slot="below"
                type="text"
                class="mt-1 p-1 d-block w-100 form-control form-control-sm"
                v-model="filter.user"
                placeholder="Partner-ID"
                @click.stop
              >
            </filter-button>

            <th class="border-bottom-1">
              Typ
              <input
                slot="below"
                type="text"
                class="mt-1 p-1 d-block w-100 form-control form-control-sm"
                v-model="filter.model"
                placeholder="Projektname oder Investor-ID"
                @click.stop
              >
            </th>

            <filter-button element="th" v-model="sort" name="money" class="border-bottom-1 align-top">
              Netto
              <radio-switch v-model="paymentType" left="net" right="gross" />
              Brutto
            </filter-button>
          </tr>
        </thead>

        <!-- Body -->
        <tbody>
        <template v-if="commissions.length > 0" >
          <template v-for="commission in commissions">
            <!-- Main Info -->
            <tr
              :key="commission.id"
              @click="commission.showDetails = !commission.showDetails"
            >
              <td class="border-right-0 text-muted pr-1">
                <font-awesome-icon v-if="commission.showDetails" icon="chevron-down" fixed-width />
                <font-awesome-icon v-else icon="chevron-right" fixed-width />
              </td>

              <td :rowspan="commission.showDetails ? 2 : 1" class="pl-1 pr-0 pb-0 border-left-0">
                <div class="d-inline-flex">
                  <b-form-checkbox
                    :checked="commission.reviewed"
                    @change="val => updateMultiple(commission, {
                      onHold: false,
                      rejected: false,
                      reviewed: val,
                    })"
                    :class="$style.green"
                    class="m-0"
                  />
                  <b-form-checkbox
                    :checked="commission.onHold"
                    @change="val => updateMultiple(commission, {
                      onHold: val,
                      rejected: false,
                      reviewed: false,
                    })"
                    :class="$style.yellow"
                    class="m-0"
                  />
                  <b-form-checkbox
                    :checked="commission.rejected"
                    @change="val => updateMultiple(commission, {
                      onHold: false,
                      rejected: val,
                      reviewed: false,
                    })"
                    :class="$style.red"
                    class="m-0"
                  />
                </div>
              </td>

              <td>
                <span class="text-muted small mr-1">
                  #{{ commission.user.id }}
                </span>
                {{ displayNameUser(commission.user) }}
              </td>

              <td>
                <font-awesome-icon icon="home" fixed-width size="sm" class="align-baseline" style="color: #aaa" />
                {{ commission.model.project.name }}
              </td>

              <td v-if="commission.showDetails" class="text-right" rowspan="2">
                <strong>Netto</strong>: {{ formatEuro(commission.net) }}<br>
                <strong>Brutto</strong>: {{ formatEuro(commission.gross) }}
              </td>

              <td v-else class="text-right">
                {{ formatEuro(commission[paymentType]) }}
              </td>
            </tr>

            <!-- Commission Details -->
            <tr
              v-if="commission.showDetails"
              :key="`${commission.id}-details`"
            >
              <td colspan="4" class="small border-right pl-3" :class="$style.infoBox">
                <div class="mt-1 mb-2 row align-items-center">
                  <div class="col-sm-3"><strong>Investor:</strong></div>
                  <div class="col-sm-9">{{ displayNameUser(commission.model.investor) }}</div>
                </div>

                <div class="mb-1 row align-items-center">
                  <div class="col-sm-3">
                    <strong>Rechnungsnotiz:</strong>
                  </div>
                  <div class="col-sm-9">
                    <input
                      :value="commission.note.public"
                      @change="e => updateValue(commission, 'note.public', e.target.value.trim())"
                      class="form-control form-control-sm"
                      placeholder="Steht auf der Rechnung"
                    />
                  </div>
                </div>

                <div class="mb-1 row align-items-center">
                  <div class="col-sm-3">
                    <strong>Notizen für Intern:</strong>
                  </div>
                  <div class="col-sm-9">
                    <input
                      :value="commission.note.private"
                      @change="e => updateValue(commission, 'note.private', e.target.value.trim())"
                      class="form-control form-control-sm"
                      placeholder="Privat"
                    />
                  </div>
                </div>
              </td>
            </tr>
          </template>
        </template>

        <!-- Loading / Empty State -->
        <tr v-else>
          <td colspan="5" class="text-center text-muted">
            <span v-if="isLoading">
              Provisionen werden geladen...
            </span>
            <span v-else>
              Keine Provisionen für die aktuelle Auswahl gefunden
            </span>
          </td>
        </tr>
        </tbody>

        <!-- Footer -->
        <tfoot>
        <tr>
          <td colspan="2" class="text-center align-middle small">
            <font-awesome-icon v-if="isLoading" icon="sync" spin />
            <span v-else-if="hasFilters">{{ meta.total }} / {{ totals.count }}</span>
            <span v-else>{{ meta.total }} insg.</span>
          </td>
          <td colspan="2" class="text-center">
            <b-pagination
              size="sm"
              class="justify-content-center mb-0"
              v-model="currentPage"
              :disabled="isLoading"
              :total-rows="meta.total"
              :per-page="meta.per_page"
              :limit="10"
            />
          </td>
          <td class="text-right lead font-weight-bold">
            {{ formatEuro(totals[paymentType] || 0) }}
          </td>
        </tr>
        </tfoot>
      </table>
    </div>

    <div class="mt-3 d-flex justify-content-between align-items-center">
      <div class="p-1 bg-white shadow-sm">
        <strong class="ml-1 mr-2">Auswahl:</strong>

        <button
          class="btn btn-sm btn-outline-success"
          @click="confirm('Wirklich alle bestätigen?', () => updateAll({
            onHold: false,
            rejected: false,
            reviewed: true,
          }))"
        >Bestätigen</button>

        <button
          class="btn btn-sm btn-outline-warning"
          @click="confirm('Wirklich alle zurückhalten?', () => updateAll({
            onHold: true,
            rejected: false,
            reviewed: false,
          }))"
        >Zurückhalten</button>

        <button
          class="btn btn-sm btn-outline-danger"
          @click="confirm('Wirklich alle ablehnen?', () => updateAll({
            onHold: false,
            rejected: true,
            reviewed: false,
          }))"
        >Ablehnen</button>
      </div>

      <a class="btn btn-primary" href="/bills/create">Rechnungen Erstellen</a>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

import debounce from 'lodash/debounce';
import filter from 'lodash/filter';
import forEach from 'lodash/forEach';
import get from 'lodash/get';
import map from 'lodash/map';
import reduce from 'lodash/reduce';
import set from 'lodash/set';

import { confirm } from '../../alert';

import FilterButton from './FilterButton';
import RadioSwitch from '../RadioSwitch';

export default {
  name: 'CommissionApproval',

  components: {
    FilterButton,
    RadioSwitch,
  },

  props: {
    api: {
      type: String,
      required: true,
    },
    totals: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      isLoading: false,
      commissions: [],
      meta: {},
      paymentType: 'net',

      label: {
        gross: 'Brutto',
        net: 'Netto',
      },

      filter: {
        model: '',
        user: '',
        reviewed: false,
        onHold: false,
        rejected: false,
      },

      sort: {
        name: 'money',
        order: '',
      },

      animation: {
        enter: ({ clientHeight }) => ({
          height: [clientHeight, 0],
          opacity: 1,
        }),
        leave: {
          height: 0,
          opacity: 0,
        },
      },
    };
  },

  computed: {
    currentPage: {
      get() {
        return this.meta.current_page;
      },
      set(val) {
        this.getPage(val);
      },
    },

    sortParams() {
      return this.sort.order !== ''
        ? `${this.sort.order === 'desc' ? '-' : ''}${this.sort.name}`
        : undefined;
    },

    filterParams() {
      return reduce(this.filter, (map, val, key) => {
        map[`filter[${key}]`] = val === true || val.length > 0 ? val : undefined;
        return map;
      }, {});
    },

    hasFilters() {
      return filter(this.filterParams, filter => filter !== undefined).length > 0;
    },
  },

  created() {
    this.getPage(1);
  },

  methods: {
    formatEuro(number) {
      return number.toLocaleString('de-DE', { style: 'currency', currency: 'EUR' });
    },

    async getPage(number = 1) {
      this.isLoading = true;

      // Returns a paginated response
      try {
        const { data } = await axios.get(this.api, {
          params: {
            page: number,
            sort: this.sortParams,
            ...this.filterParams,
          },
        })

        this.meta = data.meta;
        this.commissions = map(data.data, commission => ({
          ...commission,
          showDetails: false,
        }));
      } catch(e) {
        this.$notify({
          title: 'Fehler beim Laden der Daten',
          text: e.message,
          type: 'error',
        });
      }

      this.isLoading = false;
    },

    displayNameUser({ firstName, lastName }) {
      return `${lastName}, ${firstName}`;
    },

    // updateDatedValue(commission, key, value) {
    //   if (value) {
    //     commission[key] = {
    //
    //     }
    //   } else {
    //     this.$set(commission, key, {
    //       date: '',
    //       user: '',
    //     });
    //
    //     this.updateOrRollBack(commission, key, value, () => {
    //
    //     })
    //   }
    // },

    async confirm(message, callback) {
      const close = await confirm(message);
      await callback();
      close();
    },

    async updateValue(commission, key, value) {
      const prev = get(commission, key);
      set(commission, key, value);

      await this.updateOrRollBack(
        commission,
        { [key]: value },
        () => set(commission, key, prev)
      );
    },

    async updateMultiple(commission, props) {
      const prev = map(props, (val, key) => {
        const value = get(commission, key);
        set(commission, key, val);

        return value;
      });

      await this.updateOrRollBack(
        commission,
        props,
        () => forEach(prev, (val, key) => set(commission, key, val))
      );
    },

    async updateAll(props) {
      try {
        await axios.put(this.api, props, {
          params: this.filterParams,
        });

        this.$notify('Änderungen gespeichert');

        forEach(this.commissions, commission => {
          forEach(props, (val, key) => set(commission, key, val));
        });
      } catch (e) {
        this.$notify({
          title: 'Fehler beim Speichern',
          text: e.message,
          type: 'error',
        });
      }
    },

    async updateOrRollBack(commission, props, rollbackCallback) {
      try {
        await axios.put(`${this.api}/${commission.id}`, props);
        this.$notify('Änderungen gespeichert');

      } catch (e) {
        this.$notify({
          title: 'Fehler beim Speichern',
          text: e.message + '. Wert wird wieder zurückgesetzt.',
          type: 'error',
        });

        rollbackCallback();
      }
    }
  },

  watch: {
    filter: {
      deep: true,
      handler: debounce(function () {
        this.getPage();
      }, 300),
    },
    sort: {
      deep: true,
      handler() {
        this.getPage();
      },
    },
  },
};
</script>

<style lang="scss" module>
  @import '../../../sass/variables';

  .table {
    table-layout: fixed;
  }

  .disabled :hover {
    cursor: wait;
  }

  .infoBox {
    box-shadow: inset 1px 1px 3px $gray-200;
    background-color: $gray-100;
  }

  .green :global {
//    .custom-control-label::before {
//       background-color: rgba($green, 0.2);
//    }

    .custom-control-label::after {
      background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3E%3Cpath fill='%23888' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26 2.974 7.25 8 2.193z'/%3E%3C/svg%3E");
    }

    .custom-control-input:checked ~ .custom-control-label::before {
      background-color: $green !important;
    }
  }

  .yellow :global(.custom-control-input:checked ~ .custom-control-label::before) {
    background-color: $yellow !important;
  }

  .red :global(.custom-control-input:checked ~ .custom-control-label::before) {
    background-color: $red !important;
  }
</style>
