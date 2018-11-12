<!--
  v1 of the commissions table
  I'll clean this up later when everything got approved.
-->
<template>
  <div :class="{[$style.disabled]: isLoading}">
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
          <col width="90">
          <col width="80">
          <col width="50%">
          <col width="50%">
          <col width="150">
        </colgroup>

        <!-- Header -->
        <thead>
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
            <th class="border-bottom-1 align-top pr-0">
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
              <div class="d-flex justify-content-between">
                Typ

                <div class="d-flex">
                  <select
                    v-model="filter.type"
                    class="form-control form-control-sm w-auto py-0 px-1 h-auto"
                  >
                    <option value="">(Alle)</option>
                    <option value="investor">Registrierung</option>
                    <option value="investment">Projektinvestment</option>
                    <option value="first-investment">Erstinvestment</option>
                    <option value="further-investment">Folgeinvestment</option>
                  </select>

                  <select
                    v-model="filter.overhead"
                    class="form-control form-control-sm w-auto py-0 px-1 h-auto ml-1"
                  >
                    <option value="">(Overhead)</option>
                    <option value="true">Ja</option>
                    <option value="false">Nein</option>
                  </select>
                </div>
              </div>
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
              @click="commission.showDetails = !commission.showDetails"
            >
              <td class="text-muted pl-1 align-middle">
                <div class="d-flex align-items-center justify-content-between">
                  <font-awesome-icon v-if="commission.showDetails" icon="chevron-down" fixed-width />
                  <font-awesome-icon v-else icon="chevron-right" fixed-width />
                  <a
                    v-if="commission.model"
                    href="#"
                    class="ml-1 small text-muted"
                    v-text="commission.model.id"
                    @click.stop="filterById(commission.model.id)"
                  />
                </div>
              </td>
              <td :rowspan="commission.showDetails ? 2 : 1" class="pr-0 pb-0 border-left-0">
                <div class="d-flex">
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

              <td colspan="2">
                <div class="d-flex align-items-center">
                  <a :href="commission.user.links.self" target="_blank" class="mr-1" @click.stop style="line-height: 0">
                    <font-awesome-icon icon="share-square" size="xs" />
                  </a>
                  <a href="#" class="text-muted small mr-2" :title="displayNameUser(commission.user)" @click.prevent="filter.user = `${commission.user.id}`">
                    {{ commission.user.id }}
                  </a>
                  <div v-if="commission.model && commission.model.project !== undefined" class="flex-fill">
                    <schema :value="commission.model.project.schema" :commission="commission" />
                  </div>
                  <div v-else class="row flex-fill">
                    <div class="col" v-text="displayNameUser(commission.user)" />
                    <div v-if="commission.model" class="col" v-text="displayNameUser(commission.model)" />
                  </div>
                  <div v-if="commission.childUser !== undefined" title="Overhead" class="mx-1">
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
                </div>
              </td>

              <td v-if="commission.showDetails" class="text-right" rowspan="2">
                <strong>Netto</strong>: {{ formatEuro(commission.net) }}<br>
                <strong>Brutto</strong>: {{ formatEuro(commission.gross) }}
              </td>

              <td v-else class="text-right">
                {{ formatEuro(commission.vatIncluded ? commission.net : commission.gross) }}
              </td>
            </tr>

            <!-- Commission Details -->
            <tr
              v-if="commission.showDetails"
              :key="`${commission.id}-details`"
            >
              <td colspan="4" class="small border-right pl-3" :class="$style.infoBox">
                <div v-if="commission.childUser !== undefined" class="py-2 row align-items-center">
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

                <div v-if="commission.type === 'investment' && commission.model" class="py-2 row align-items-center">
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

                <div v-if="commission.type === 'investment' && commission.model" class="py-1 row align-items-center">
                  <div class="col-sm-2"><strong>Investor:</strong></div>
                  <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext"
                           :value="`#${commission.model.investor.id} ${displayNameUser(commission.model.investor)}`">
                  </div>
                </div>

                <div class="my-1 row align-items-center">
                  <div class="col-sm-2">
                    <strong>Notiz:</strong>
                  </div>
                  <div class="col-sm-10">
                    <input
                      :value="commission.note.public"
                      @change="e => updateValue(commission, 'note.public', e.target.value.trim())"
                      class="form-control form-control-sm"
                      placeholder="Steht auf der Rechnung"
                    />
                  </div>
                </div>

                <div class="my-1 row align-items-center">
                  <div class="col-sm-2 pr-1">
                    <strong>Notiz (Intern):</strong>
                  </div>
                  <div class="col-sm-10">
                    <input
                      :value="commission.note.private"
                      @change="e => updateValue(commission, 'note.private', e.target.value.trim())"
                      class="form-control form-control-sm"
                      placeholder="Privat, nur für die Buchhaltung"
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
            {{ formatEuro(filteredTotal || 0) }}
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

      <div class="p-1 bg-white shadow-sm">
        <strong class="ml-1 mr-2">Filter:</strong>
        <button
          class="btn btn-sm btn-outline-secondary"
          @click="clearFilters"
        >Alle löschen</button>
      </div>

      <a class="btn btn-primary" href="/bills/create">Rechnungen Erstellen</a>
    </div>

    <div class="shadow-sm bg-white accent-primary my-4">
      <div class="card-body">
        <h5 class="card-title">Korrekturzahlung anlegen</h5>
        <form @submit.prevent="createCustomEntry">
          <div class="my-1 row align-items-center">
            <div class="col-sm-2">
              <strong>Partner (ID):</strong>
            </div>
            <div class="col-sm-10">
              <input
                v-model.number="newEntry.userId"
                type="text"
                class="form-control form-control-sm mr-2"
                placeholder="Partner-ID"
              >
            </div>
          </div>

          <div class="my-1 row align-items-center">
            <div class="col-sm-2 pr-1">
              <strong>Betrag in EUR:</strong>
            </div>
            <div class="col-sm-10 d-flex">
              <input
                v-model.number="newEntry.amount"
                type="number"
                step="0.01"
                class="form-control form-control-sm mr-2"
                placeholder="Betrag in EUR"
              >
              <strong class="text-danger text-nowrap align-self-center">
                Mehrwertsteuer ist partnerabhängig!
              </strong>
            </div>
          </div>

          <div class="my-1 row align-items-center">
            <div class="col-sm-2">
              <strong>Notiz/Titel:</strong>
            </div>
            <div class="col-sm-10">
              <input
                v-model="newEntry.note.public"
                class="form-control form-control-sm"
                placeholder="Steht auf der Rechnung"
              />
            </div>
          </div>

          <div class="my-1 row align-items-center">
            <div class="col-sm-2 pr-1">
              <strong>Notiz (Intern):</strong>
            </div>
            <div class="col-sm-10">
              <input
                v-model="newEntry.note.private"
                class="form-control form-control-sm"
                placeholder="Privat, nur für die Buchhaltung"
              />
            </div>
          </div>

          <div class="text-right mt-3">
            <button class="btn btn-primary">Korrekturzahlung Anlegen</button>
          </div>
        </form>
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

import { confirm } from '../../alert';

import FilterButton from './FilterButton';
import RadioSwitch from '../RadioSwitch';
import Schema from './Schema.vue';

const createNewEntry = () => ({
  userId: null,
  amount: 0,
  note: {
    public: '',
    private: '',
  },
});

export default {
  name: 'CommissionApproval',

  components: {
    FilterButton,
    RadioSwitch,
    Schema,
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

      filter: {
        id: '',
        model: '',
        type: '',
        user: '',
        overhead: '',
        reviewed: false,
        onHold: false,
        rejected: false,
        money: '',
      },

      sort: {
        name: 'money',
        order: '',
      },

      newEntry: createNewEntry(),
    };
  },

  computed: {
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
    confirm,

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

        throw e;
      } finally {
        this.isLoading = false;
      }
    },

    displayNameUser({ firstName, lastName }) {
      return `${lastName}, ${firstName}`;
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
      const prev = mapValues(props, (val, key) => {
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
        throw e;
      }
    },

    async updateOrRollBack(commission, props, rollbackCallback) {
      try {
        await axios.put(`${this.api}/${commission.key}`, props);
        this.$notify('Änderungen gespeichert');

      } catch (e) {
        this.$notify({
          title: 'Fehler beim Speichern',
          text: e.message + '. Wert wird wieder zurückgesetzt.',
          type: 'error',
        });

        this.$nextTick(rollbackCallback);
        throw e;
      }
    },

    clearFilters() {
      this.filter = mapValues(this.filter, () => '');
    },

    filterById(id) {
      this.clearFilters();
      this.filter.id = `${id}`;
    },

    async createCustomEntry() {
      try {
        await axios.post(this.api, this.newEntry);
      } catch (e) {
        this.$notify({
          title: 'Fehler beim Anlegen des Eintrags',
          text: e.message,
          type: 'error',
        });

        throw e;
      }

      // Clear the data and update our contents
      this.newEntry = createNewEntry();
      await this.getPage();
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
    }
  }

  .yellow :global .custom-control-input:checked ~ .custom-control-label::before {
    background-color: $yellow !important;
  }

  .red :global .custom-control-input:checked ~ .custom-control-label::before {
    background-color: $red !important;
  }
</style>
