<!--
  v1 of the commissions table
  I'll clean this up later when everything got approved.
-->
<template>
  <div :class="{[$style.disabled]: isLoading}">
    <div class="card border-0 shadow-sm my-3 rounded p-2 flex-row align-items-center">
      <b class="ml-1">Zeitraum</b>
      <span class="ml-3 mr-2">von</span>
      <flat-pickr
        v-model="filter.rangeFrom"
        :config="flatpickrConfigFrom"
        class="form-control shadow-none border-0"
      />
      <span class="ml-3 mr-2">bis</span>
      <flat-pickr
        v-model="filter.rangeTo"
        :config="flatpickrConfigTo"
        class="form-control shadow-none border-0"
      />
    </div>

    <!-- Content -->
    <div class="card border-0 shadow-sm accent-primary">
      <table :class="$style.table" class="table table-sm table-hover table-striped mb-0 table-sticky">
        <colgroup>
          <col width="90">
          <col width="50%">
          <col width="50%">
          <col width="150">
        </colgroup>

        <!-- Header -->
        <thead>
        <tr>
          <th colspan="5">
            <div class="row d-flex flex-row justify-content-end">
              <div class="col-md-2">Typ</div>
              <div class="col-md-2">Overhead</div>
              <div class="col-md-2">Legal Setup</div>
            </div>
            <div class="row d-flex flex-row justify-content-end">
              <div class="col-md-2">
                <select
                  v-model="filter.type"
                  class="form-control form-control-sm w-auto py-0 px-1 h-auto"
                >
                  <option value="">(Alle)</option>
                  <option value="investor">Registrierung</option>
                  <option value="investment">Projektinvestment</option>
                  <option value="first-investment">Erstinvestment</option>
                  <option value="further-investment">Folgeinvestment</option>
                  <option value="correction">Sonderbuchung</option>
                </select>
              </div>
              <div class="col-md-2">
                <select
                  v-model="filter.overhead"
                  class="form-control form-control-sm w-auto py-0 px-1 h-auto ml-1"
                >
                  <option value="">(Overhead)</option>
                  <option value="true">Ja</option>
                  <option value="false">Nein</option>
                </select>
              </div>
              <div class="col-md-2 mr-2">
                <select
                  v-model="filter.legalSetup"
                  class="form-control form-control-sm w-auto py-0 px-1 h-auto ml-1"
                >
                  <option value="">Alle</option>
                  <option value="silentParticipation">silentParticipation</option>
                  <option value="investmentLaw2a">investmentLaw2a</option>
                  <option value="investmentLaw2">investmentLaw2</option>
                  <option value="subordinatedLoan">subordinatedLoan</option>
                  <option value="bond">bond</option>
                  <option value="bondLight">bondLight</option>
                  <option value="priip">priip</option>
                </select>
              </div>
            </div>
          </th>
        </tr>
        <tr>
          <th class="border-bottom-1 align-top">
            ID
            <input
              type="text"
              class="mt-1 p-1 d-block w-100 form-control form-control-sm"
              v-model="filter.id"
              placeholder="ID"
              @click.stop
            >
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
            <input
              slot="below"
              type="text"
              class="mt-1 p-1 d-block w-100 form-control form-control-sm"
              v-model="filter.model"
              placeholder="Projektname"
              @click.stop
            >
          </th>

          <filter-button element="th" v-model="sort" name="money" class="border-bottom-1 align-top">
            Betrag

            <input
              slot="below"
              type="text"
              class="mt-1 p-1 d-block w-100 form-control form-control-sm"
              v-model="filter.money"
              placeholder="Brutto-Filter"
              @click.stop
            >
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
              :class="{ 'table-warning': hasDifferingInterestRate(commission) }"
              @click="commission.showDetails = !commission.showDetails"
            >
              <td class="text-muted pl-1 align-middle">
                <div class="d-flex align-items-center justify-content-between">
                  <font-awesome-icon
                    :icon="commission.showDetails ? 'chevron-down' : 'chevron-right'"
                    fixed-width
                  />
                  <a
                    v-if="commission.model"
                    href="#"
                    class="ml-1 small text-muted"
                    v-text="commission.model.id"
                    @click.stop="filterById(commission.model.id)"
                  />
                </div>
              </td>

              <td colspan="2">
                <div class="d-flex align-items-center">
                  <a :href="commission.user.links.self" target="_blank" class="mr-1" @click.stop style="line-height: 0">
                    <font-awesome-icon icon="share-square" size="xs" />
                  </a>
                  <a href="#" class="text-muted small mr-2"
                     v-b-tooltip.hover.right="displayNameUser(commission.user)"
                     @click.prevent="filter.user = `${commission.user.id}`">
                    {{ commission.user.id }}
                  </a>

                  <div v-if="commission.model && commission.model.project !== undefined" class="flex-fill">
                    <schema :value="commission.model.project.schema" :commission="commission" />
                  </div>
                  <div v-else-if="commission.type === 'correction'" class="flex-fill">
                    <strong>Sonderbuchung:</strong> {{ commission.note.public}}
                  </div>
                  <div v-else class="row flex-fill">
                    <div class="col-5" v-text="displayNameUser(commission.user)" />

                    <template v-if="commission.model">
                      <div class="col-5" v-text="displayNameUser(commission.model)" />
                      <div class="col-2 pl-0" v-text="commission.model.activatedAt" />
                    </template>
                  </div>

                  <div v-if="commission.childUser !== null" title="Overhead" class="mx-1">
                    <font-awesome-icon icon="users" fixed-width size="sm" class="align-baseline text-muted" />
                  </div>

                  <div v-if="commission.type === 'investment' && commission.model && commission.model.isFirst" title="Erstinvestment" class="mx-1">
                    <font-awesome-icon icon="flag" fixed-width size="sm" class="align-baseline text-danger" />
                  </div>

                  <div v-if="commission.type === 'investment'" title="Projektinvestment">
                    <font-awesome-icon icon="home" fixed-width size="sm" class="align-baseline text-primary" />
                  </div>
                  <div v-else-if="commission.type === 'investor'" title="Registrierung">
                    <font-awesome-icon icon="user" fixed-width size="sm" class="align-baseline text-success" />
                  </div>
                  <div v-else-if="commission.type === 'correction'" title="Sonderbuchung">
                    <font-awesome-icon icon="euro-sign" fixed-width size="sm" class="align-baseline text-dark" />
                  </div>
                </div>
              </td>

              <td v-if="commission.showDetails" class="text-right" rowspan="2">
                <strong>Netto</strong>: {{ formatMoney(commission.net) }}<br>
                <strong>Brutto</strong>: {{ formatMoney(commission.gross) }}<br>
                <font-awesome-icon
                  v-if="commission.modified"
                  v-b-tooltip.left="'Betrag wurde manuell angepasst'"
                  icon="exclamation-circle"
                  class="text-info"
                />
              </td>

              <td
                v-else
                class="text-right"
              >
                <font-awesome-icon
                  v-if="commission.modified"
                  v-b-tooltip.left="'Betrag wurde manuell angepasst'"
                  icon="exclamation-circle"
                  class="text-info"
                />

                {{ formatMoney(commission.vatIncluded ? commission.gross : commission.net) }}
              </td>
            </tr>

            <!-- Commission Details -->
            <tr
              v-if="commission.showDetails"
              :key="`${commission.id}-details`"
            >
              <td colspan="3" class="small border-right pl-3" :class="$style.infoBox">
                <div v-if="commission.childUser !== null" class="py-2 row align-items-center">
                  <div class="col-sm-2"><strong>Unterpartner:</strong></div>
                  <div class="col-sm-10">
                    <a :href="commission.childUser.links.self" target="_blank" class="mr-1" @click.stop style="line-height: 0">
                      <font-awesome-icon icon="share-square" size="xs" />
                    </a>

                    <a href="#" class="text-body" @click.prevent="filter.user = `${commission.childUser.id}`">
                      #{{ commission.childUser.id }} {{ displayNameUser(commission.childUser) }}
                    </a>
                  </div>
                </div>

                <template v-if="commission.type === 'investment' && commission.model">
                  <div class="py-2 row align-items-center">
                    <div class="col-sm-2"><strong>Projekt:</strong></div>
                    <div class="col-sm-10 d-flex align-items-center">
                      <a :href="commission.model.project.links.self" target="_blank" class="mr-1" @click.stop style="line-height: 0">
                        <font-awesome-icon icon="share-square" size="xs" />
                      </a>

                      <a href="#" class="text-body" @click.prevent="filter.model = `${commission.model.project.name}`">
                        {{ commission.model.project.name }}
                      </a>
                    </div>
                  </div>

                  <div class="py-1 row align-items-center">
                    <div class="col-sm-2"><strong>Investor:</strong></div>
                    <div class="col-sm-10">
                      <input type="text" readonly class="form-control-plaintext"
                             :value="`#${commission.model.investor.id} ${displayNameUser(commission.model.investor)}`">
                    </div>
                  </div>

                  <div class="py-1 row align-items-center">
                    <div class="col-sm-2"><strong>Investmentdatum:</strong></div>
                    <div class="col-sm-10">
                      <input type="text" readonly class="form-control-plaintext"
                             :value="commission.model.createdAt">
                    </div>
                  </div>

                  <div class="py-1 row align-items-center">
                    <div class="col-sm-2"><strong>Investmentzins:</strong></div>
                    <div class="col-sm-10">
                      <input type="text" readonly class="form-control-plaintext"
                             :value="commission.model.interestRate">
                    </div>
                  </div>

                  <div class="py-1 row align-items-center">
                    <div class="col-sm-2"><strong>Projektzins:</strong></div>
                    <div class="col-sm-10">
                      <input type="text" readonly class="form-control-plaintext"
                             :value="commission.model.project.interestRate">
                    </div>
                  </div>
                </template>

                <div v-if="commission.type === 'correction'" class="my-1 row align-items-center">
                  <div class="col-sm-2">
                    <strong>{{ commission.vatIncluded ? 'Brutto' : 'Netto' }} in EUR:</strong>
                  </div>
                  <div class="col-sm-10">
                    {{ commission.vatIncluded ? commission.gross : commission.net }}
                  </div>
                </div>

                <div v-if="commission.type === 'investment'" class="my-1 row align-items-center">
                  <div class="col-sm-2 pr-1">
                    <strong>Bonus:</strong>
                  </div>
                  <div class="col-sm-10">
                    {{ commission.bonus }}
                  </div>
                </div>

                <div
                  v-if="permissions.delete && commission.model === null"
                  class="my-2 text-right"
                >
                  <button
                    type="button"
                    @click="confirm('Wirklich löschen?', () => destroy(commission))"
                    class="btn btn-sm btn-danger"
                  >
                    Provision löschen
                  </button>
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
          <td colspan="1" class="text-center align-middle small">
            <font-awesome-icon v-if="isLoading" icon="sync" spin />
            <span v-else-if="hasFilters">{{ meta.total }} / {{ totals.count }}</span>
            <span v-else>{{ meta.total }} insg.</span>
          </td>
          <td colspan="2" class="text-center">
            <div class="d-flex align-items-center">
              <div class="flex-fill">
                <b-pagination
                  size="sm"
                  class="justify-content-center mb-0"
                  v-model="currentPage"
                  :disabled="isLoading"
                  :total-rows="meta.total"
                  :per-page="meta.per_page"
                  :limit="10"
                />
              </div>
              <strong class="text-right">
                Brutto:
              </strong>
            </div>
          </td>
          <td class="text-right lead font-weight-bold">
            {{ formatMoney(filteredTotal || 0) }}
          </td>
        </tr>
        </tfoot>
      </table>
    </div>

    <div class="mt-3 d-flex justify-content-between align-items-center">
      <div class="p-1 bg-white shadow-sm">
        <strong class="ml-1 mr-2">Filter:</strong>
        <button
          class="btn btn-sm btn-outline-secondary"
          @click="clearFilters"
        >Alle löschen</button>
      </div>
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
import mapValues from 'lodash/mapValues';
import reduce from 'lodash/reduce';
import set from 'lodash/set';

import formatDate from 'date-fns/format';
import startOfMonth from 'date-fns/start_of_month';
import endOfMonth from 'date-fns/end_of_month';
import subMonths from 'date-fns/sub_months';

import BTooltip from 'bootstrap-vue';
import FlatPickr from 'vue-flatpickr-component';
import { German } from 'flatpickr/dist/l10n/de';
import 'flatpickr/dist/flatpickr.css';

import { confirm } from '../../alert';
import { formatMoney } from '../../utils/formatters';

import FilterButton from '../FilterButton';
import Schema from '../CommissionApproval/Schema.vue';

export default {
  name: 'CommissionPending',

  components: {
    FilterButton,
    FlatPickr,
    Schema,
  },

  directives: {
    BTooltip,
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
    userDetailsApi: {
      type: String,
      required: true,
    },
    permissions: {
      type: Object,
      default: () => ({
        create: false,
        update: false,
        delete: false,
      }),
    },
  },

  data() {
    return {
      isLoading: false,
      commissions: [],
      meta: {},

      filter: {
        id: '',
        model: '',
        legalSetup: '',
        type: '',
        user: '',
        overhead: '',
        reviewed: false,
        onHold: false,
        rejected: false,
        money: '',
        rangeFrom: formatDate(startOfMonth(subMonths(new Date(), 1)), 'YYYY-MM-DD'),
        rangeTo: formatDate(endOfMonth(subMonths(new Date(), 1)), 'YYYY-MM-DD'),
      },

      flatpickrConfig: {
        altFormat: 'd.m.Y',
        altInput: true,
        locale: German,
        weekNumbers: true,
        maxDate: 'today',
      },

      sort: {
        name: 'money',
        order: '',
      },
    };
  },

  computed: {
    flatpickrConfigFrom() {
      return {
        ...this.flatpickrConfig,
        maxDate: this.filter.rangeTo || this.flatpickrConfig.maxDate,
      };
    },

    flatpickrConfigTo() {
      return {
        ...this.flatpickrConfig,
        minDate: this.filter.rangeFrom || this.flatpickrConfig.minDate,
      };
    },

    filteredTotal() {
      return this.meta.totalGross || this.totals.gross;
    },

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
        map[`filter[${key}]`] = (val === true || val.length > 0) ? val : undefined;
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
    confirm,
    formatMoney,

    async getPage(number = 1) {
      if (this.isLoading) {
        return;
      }

      this.isLoading = true;

      // Returns a paginated response
      try {
        const { data } = await axios.get(this.api, {
          params: {
            page: number,
            sort: this.sortParams,
            ...this.filterParams,
          },
        });

        this.meta = data.meta;
        this.commissions = map(data.data, this.addLocalFields);
      } catch(e) {
        this.$notify({
          title: 'Fehler beim Laden der Daten',
          text: e.message,
          type: 'error',
        });

        throw e;
      } finally {
        this.$nextTick(() => {
          this.isLoading = false;
        });
      }
    },

    displayNameUser({ firstName, lastName }) {
      return `${lastName}, ${firstName}`;
    },

    hasDifferingInterestRate(commission) {
      if (commission.type !== 'investment' || !commission.model) {
        return false;
      }

      return commission.model.interestRate !== commission.model.project.interestRate;
    },


    /**
     * Adds additional fields to the given commission.
     *
     * @param commission
     * @return Object
     */
    addLocalFields(commission) {
      return {
        ...commission,
        showDetails: false,
      };
    },

    clearFilters() {
      this.filter = mapValues(this.filter, () => '');
    },

    filterById(id) {
      this.clearFilters();
      this.filter.id = `${id}`;
    },

    checkPermission(name) {
      if (!this.permissions[name]) {
        throw new Error('Keine Berechtigung');
      }
    },

    async refreshAll() {
      try {
        this.checkPermission('delete');

        this.$notify('Einträge werden neu berechnet');
        await axios.delete(`${this.api}`, {
          params: this.filterParams,
        });

        this.$notify('Einträge wurden neu berechnet');
        this.getPage(this.currentPage);

      } catch (e) {
        this.$notify({
          title: 'Fehler bei Neuberechnung der Einträge',
          text: e.message,
          type: 'error',
        });

        throw e;
      }
    },
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
  .custom-control-label::after {
    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3E%3Cpath fill='%23888' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26 2.974 7.25 8 2.193z'/%3E%3C/svg%3E");
  }

  .custom-control-input:checked ~ .custom-control-label::before {
    background-color: $green !important;
    border-color: mix($green, black, 80%);
  }
}

.yellow :global .custom-control-input:checked ~ .custom-control-label::before {
  background-color: $yellow !important;
  border-color: mix($yellow, black, 80%);
}

.red :global .custom-control-input:checked ~ .custom-control-label::before {
  background-color: $red !important;
  border-color: mix($red, black, 80%);
}
</style>
