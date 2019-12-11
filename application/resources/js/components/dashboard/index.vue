<template>
  <div>
    <div class="form-group d-flex font-weight-bold">
      <div class="flex-fill">
        <div class="col-lg-3">
          <select
            v-model="periodSelected"
            class="custom-select"
          >
            <option
              v-for="period in periods"
              :value="period.value"
              v-text="period.title"
            />
          </select>
        </div>
      </div>
    </div>
    <div v-if="investments.length > 0" class="rounded shadow-sm bg-white p-3">
      {{ investments }}
    </div>

    <div v-else class="lead text-center text-muted">
      Keine Daten verfügbar
    </div>
  </div>
</template>

<script>
//import ApexCharts from 'apexcharts';
import axios from 'axios';

export default {

  props: {
    api: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      investments: {},
      periodSelected: 'default',
      periods: [
        {
          title: 'Gesamt',
          value: null,
        },
        {
          title: 'Letzter Monat',
          value: 'last_month',
        },
        {
          title: 'Dieser Monat',
          value: 'this_month',
        },
        {
          title: '30 Tage',
          value: 'default',
        },
      ],
    };
  },

  created() {
    this.getInvestments();
  },

  methods: {
    getInvestments() {
      let params = {
        period: this.periodSelected,
      };
      axios.get(this.api, {
        params,
      }).then(({ data }) => {
        this.investments = data;
      });       
    },
  },
};
</script>

<style lang="scss" module>
  @import "../../../sass/variables";

  .circle {
    $size: 29px;
    min-width: $size;
    min-height: $size;
    width: $size;
    height: $size;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    background-color: rgba($primary-light, 0.5);
    color: rgba($primary-dark, 0.75);
    border-radius: 5px;

    font-family: $headings-font-family;
  }
</style>
