<template>
  <div>
    <div class="d-flex flex-row-reverse ">
      <div class="col-lg-2 pr-0 mb-3">
        <select
          v-model="periodSelected"
          class="custom-select"
          @change="getInvestments()"
        >
          <option
            v-for="period in periods"
            :value="period.value"
            v-text="period.title"
          />
        </select>
      </div>
    </div>
    <div v-if="investments.length > 0">
      <div class="d-flex">
        <div class="rounded shadow-sm bg-white p-3 w-50 mr-2">
          <div class="d-flex justify-content-start h3">
            <span class="badge badge-secondary">
              {{ firstInvestmentsCount }} Stück
            </span>
          </div>
          <div class="d-flex justify-content-center h3">
            <div>
              <div class="d-flex justify-content-center">
                {{ firstInvestmentsSum }}
              </div>
              <div class="d-flex justify-content-center">
                Umsatz Erstinvestments
              </div>
            </div>
          </div>
        </div>
        <div class="rounded shadow-sm bg-white p-3 w-50 ml-2">
          <div class="d-flex justify-content-start h3">
            <span class="badge badge-secondary">
              {{ investmentsCount }} Stück
            </span>
          </div>
          <div class="d-flex justify-content-center h3">
            <div>
              <div class="d-flex justify-content-center">
                {{ investmentsSum }}
              </div>
              <div class="d-flex justify-content-center">
                Umsatz Investments
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex mt-3">
        <div class="rounded shadow-sm bg-white p-3 w-50 mr-2">
          <div>
            <apexchart type="bar" :options="chartOptions" :series="series"></apexchart>
          </div>
          <div>
            
          </div>
        </div>
        <div class="rounded shadow-sm bg-white p-3 w-50 ml-2">
          
        </div>
      </div>
      
      {{ investmentsByPeriod }} {{ investmentsPeriods }} 
    </div>

    <div v-else class="lead text-center text-muted">
      Keine Daten verfügbar
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import filter from 'lodash/filter';
import find from 'lodash/find';
import sumBy from 'lodash/sumBy';
import countBy from 'lodash/countBy';
import groupBy from 'lodash/groupBy';
import sortBy from 'lodash/sortBy';
import chain from 'lodash/chain';
import map from 'lodash/map';
import mapKeys from 'lodash/mapKeys';
import values from 'lodash/values';
import toArray from 'lodash/toArray';
import forEach from 'lodash/forEach';
import orderBy from 'lodash/orderBy';

export default {

  props: {
    api: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      draw: 0,
      investments: {},
      chartOptions: {
          chart: {
            id: 'investments-by-period',
            stacked: true,
            toolbar: {
              show: true,
            },
            zoom: {
              enabled: true,
            },
          },
          dataLabels: {
            enabled: false,
          },
          xaxis: {},
        },
      series: [],
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

  computed: {
    firstInvestmentsSum() {
      let sum = sumBy(
        filter(this.investments, ['is_first_investment', true]),
        'amount'
      );
      return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(sum);
    },

    investmentsSum() {
      let sum = sumBy(this.investments, 'amount');
      return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(sum);
    },

    firstInvestmentsCount() {
      return filter(this.investments, ['is_first_investment', true]).length;
    },

    investmentsCount() {
      return this.investments.length;
    },

    investmentsByPeriod() {
      let characters = this.periodSelected === null ? 7 : 10;

      let dataFirstInvestments = map(
        groupBy(
          groupBy(this.investments, 'investment_type').first, o => o.created_at.slice(0, characters)),
        (value, key) => ({
          created_at: key,
          amount: sumBy(value, 'amount'),
        })
      );

      let dataSubsequentInvestments = map(
        groupBy(
          groupBy(this.investments, 'investment_type').subsequent, o => o.created_at.slice(0, characters)),
        (value, key) => ({
          created_at: key,
          amount: sumBy(value, 'amount'),
        })
      );

      forEach(this.investmentsPeriods.categories, function(value) {
        if (find(dataFirstInvestments, ['created_at', value]) === undefined) {
          dataFirstInvestments.push(
            {
              created_at: value,
              amount: 0,
            }
          );
        }
        if (find(dataSubsequentInvestments, ['created_at', value]) === undefined) {
          dataSubsequentInvestments.push(
            {
              created_at: value,
              amount: 0,
            }
          );
        }
      });

      return [
        {
          name: 'Erstinvestments',
          data: map(
            orderBy(dataFirstInvestments, 'created_at'),
            'amount'
          ),
        },
        {
          name: 'Folgeinvestments',
          data: map(
            orderBy(dataSubsequentInvestments, 'created_at'),
            'amount'
          ),
        },
      ];
    },

    investmentsPeriods() {
      let characters = this.periodSelected === null ? 7 : 10;
      return {
        type: 'datetime',
        categories: map(
          sortBy(
            map(
              groupBy(
                this.investments, 
                obj => obj.created_at.slice(0, characters)
              ),
              (value, key) => ({
                created_at: this.periodSelected === null ? key : key,
                value: value,
              })
            ),
            'created_at'),
          'created_at')
      };
    },
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
        if (this.draw === 0) {
          this.series = this.investmentsByPeriod;
          this.chartOptions.xaxis = this.investmentsPeriods;
        } else {
          ApexCharts.exec("investments-by-period", "updateOptions", {
            xaxis: this.investmentsPeriods,
            series: this.investmentsByPeriod,
          });
        }
        this.draw++;
      });       
    },
  },
};
</script>

<style lang="scss" module>
  
</style>
