<template>
  <div class="shadow-sm bg-white accent-primary my-4">
    <div class="card-body">
      <h5 class="card-title">Sonderbuchung anlegen</h5>
      <form @submit.prevent="submit">
        <div class="my-1 row align-items-center">
          <div class="col-sm-2">
            <strong>Partner:</strong>
          </div>
          <div v-if="userDetails === null" class="text-muted col-sm-10 col-form-label">
            Partner werden geladen...
          </div>
          <div v-else class="col-sm-10">
            <v-select
              :options="userDetails"
              label="displayText"
              v-model.number="user"
            >
            </v-select>
          </div>
        </div>

        <div class="my-1 row align-items-center">
          <div class="col-sm-2 pr-1">
            <strong>Betrag in EUR:</strong>
          </div>
          <div class="col-sm-10 d-flex">
            <input
              v-model.number="entry.amount"
              type="number"
              step="0.01"
              class="form-control form-control-sm mr-2"
            >
            <strong class="text-nowrap align-self-center w-50">
              Netto:
              {{ netGross.net }}
            </strong>
            <strong class="text-nowrap align-self-center w-50">
              Brutto:
              {{ netGross.gross }}
            </strong>
          </div>
        </div>

        <div class="my-1 row align-items-center">
          <div class="col-sm-2">
            <strong>Notiz/Titel:</strong>
          </div>
          <div class="col-sm-10">
            <input
              v-model="entry.note.public"
              class="form-control form-control-sm"
              placeholder="Steht auf der Rechnung"
              maxlength="191"
            />
          </div>
        </div>

        <div class="my-1 row align-items-center">
          <div class="col-sm-2 pr-1">
            <strong>Notiz (Intern):</strong>
          </div>
          <div class="col-sm-10">
            <input
              v-model="entry.note.private"
              class="form-control form-control-sm"
              placeholder="Privat, nur für die Buchhaltung"
              maxlength="191"
            />
          </div>
        </div>

        <div class="text-right mt-3">
          <button class="btn btn-primary">Sonderbuchung Anlegen</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import vSelect from 'vue-select';
import map from 'lodash/map';
import find from 'lodash/find';

import { formatMoney } from "../../utils/formatters";
import 'vue-select/dist/vue-select.css';

export default {
  components: {
    vSelect,
  },

  props: {
    api: {
      type: String,
      required: true,
    },
  },

  data: () => ({
    entry: {},
    userDetails: [],
    user: {},
  }),

  computed: {
    netGross() {
      if (!this.entry.userId) {
        return { net: '', gross: '' };
      }

      const details = this.entry.userId;
      const { amount } = this.entry;
      const factor = 1 + (details.vatAmount / 100);
      let net = amount;
      let gross = amount;

      if (details.vatIncluded) {
        net = amount / factor;
      } else {
        gross = amount * factor;
      }

      return {
        net: formatMoney(net),
        gross: formatMoney(gross),
      };
    },
  },

  created() {
    this.getUserDetails();
    this.createNewEntry();
  },

  watch: {
    user: function (val) {
      this.entry.userId = parseInt(val.id);
    },
  },

  methods: {
    async getUserDetails() {
      const { data } = await axios.get(this.api);
      this.userDetails = this.mapDetails(data);
    },

    submit() {
      this.$emit('submit', this.entry);
      this.createNewEntry();
    },

    createNewEntry() {
      this.entry = {
        userId: null,
        amount: 0,
        note: {
          public: '',
          private: '',
        },
      };
    },

    mapDetails(data) {
      return map(data, function(obj, key) {
        obj.id = key;
        obj.displayText = `${obj.id} - ${obj.displayName}`;
        return obj;
      });
    },
  },
};
</script>
