<template>
  <div>
    <div class="d-flex flex-row-reverse align-items-end mt-0 mb-3">
      <div class="col-lg-2 pr-0 pl-2">
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
      <div class="col-lg-2 pl-2">
        <label class="m-0">bis:</label>
        <vuejs-datepicker
          input-class="form-control"
          v-model="date.second"
        ></vuejs-datepicker>
      </div>
      <div class="col-lg-2 pl-2">
        <label class="m-0">von:</label>
        <vuejs-datepicker
          input-class="form-control"
          v-model="date.first"
        ></vuejs-datepicker>
      </div>
    </div>
    <div 
      v-if="commissions.length > 0 && !loading"
      class="rounded shadow-sm bg-white p-3 w-100 mr-2 mt-3 mb-3"
    >
      <div>
        <span class="h3">
          Umsatz (Provisionen)
        </span>
      </div>
      <apexchart
        type="line"
        :options="commissionsOptions"
        :series="commissionsData"
        :height="280"
      ></apexchart>
    </div>
    <div v-if="investments.length > 0 && !loading">
      <div class="d-flex mt-3">
        <div class="rounded shadow-sm bg-white p-3 w-50 mr-2">
          <div class="d-flex justify-content-center h3 mb-0">
            Exporo {{ types.first }}
          </div>
        </div>
        <div class="rounded shadow-sm bg-white p-3 w-50 ml-2">
          <div class="d-flex justify-content-center h3 mb-0">
            Exporo {{ types.second }}
          </div>
        </div>
      </div>

      <div class="d-flex mt-3">
        <div class="rounded shadow-sm bg-white p-3 w-25 mr-2">
          <div class="h4 mb-0">
            <div class="d-flex justify-content-center">
              {{ getInvestmentsSum('first', true) }}
            </div>
            <div class="d-flex justify-content-center">
              Erstinvestments
            </div>
          </div>
        </div>
        <div class="rounded shadow-sm bg-white p-3 w-25 ml-2 mr-2">
          <div class="h4 mb-0">
            <div class="d-flex justify-content-center">
              {{ getInvestmentsSum('first', false) }}
            </div>
            <div class="d-flex justify-content-center">
              Folgeinvestments
            </div>
          </div>
        </div>
        <div class="rounded shadow-sm bg-white p-3 w-25 ml-2 mr-2">
          <div class="h4 mb-0">
            <div class="d-flex justify-content-center">
              {{ getInvestmentsSum('second', true) }}
            </div>
            <div class="d-flex justify-content-center">
              Erstinvestments
            </div>
          </div>
        </div>
        <div class="rounded shadow-sm bg-white p-3 w-25 ml-2">
          <div class="h4 mb-0">
            <div class="d-flex justify-content-center">
              {{ getInvestmentsSum('second', false) }}
            </div>
            <div class="d-flex justify-content-center">
              Folgeinvestments
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex mt-3">
        <div class="rounded shadow-sm bg-white p-3 w-50 mr-2">
          <div>
            <apexchart
              type="bar"
              :options="investmentsByPeriodOptionsFirst"
              :series="investmentsByPeriodSeriesFirst"
            ></apexchart>
          </div>
          <div>
            <apexchart
              type="bar"
              :options="investmentsByProjectOptionsFirst"
              :series="investmentsByProjectSeriesFirst"
              :height="320"
            ></apexchart>
          </div>
        </div>
        
        <div class="rounded shadow-sm bg-white p-3 w-50 ml-2">
          <div>
            <apexchart
              type="bar"
              :options="investmentsByPeriodOptionsSecond"
              :series="investmentsByPeriodSeriesSecond"
            ></apexchart>
          </div>
          <div>
            <apexchart
              type="bar"
              :options="investmentsByProjectOptionsSecond"
              :series="investmentsByProjectSeriesSecond"
              :height="320"
            ></apexchart>
          </div>
        </div>
        
      </div>
    </div>

    <div v-else-if="loading" class="rounded shadow-sm bg-white p-3 w-100 mr-2 mt-3 lead text-center text-muted">
      <vue-simple-spinner
        size="large"
        :line-fg-color="colors.subsequentInvestment"
      ></vue-simple-spinner>
      Daten werden geladen
    </div>

    <div v-else class="rounded shadow-sm bg-white p-3 w-100 mr-2 mt-3 lead text-center text-muted">
      Keine Daten verfügbar
    </div>

    <div v-if="investments.length > 0 && !loading" class="rounded shadow-sm bg-white p-3 w-100 mr-2 mt-3">
      <div>
        <span class="h3">
          Letzte Investments
        </span>
      </div>
      <data-table
        v-bind="investmentTableData"
        minimal-styling
        :page-size="15"
      ></data-table>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import filter from 'lodash/filter';
import find from 'lodash/find';
import sumBy from 'lodash/sumBy';
import groupBy from 'lodash/groupBy';
import sortBy from 'lodash/sortBy';
import map from 'lodash/map';
import forEach from 'lodash/forEach';
import orderBy from 'lodash/orderBy';
import Spinner from 'vue-simple-spinner';
import Datepicker from 'vuejs-datepicker';

export default {
  components: {
    'vue-simple-spinner': Spinner,
    'vuejs-datepicker': Datepicker,
  },

  props: {
    api: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      loading: true,
      colors: {
        firstInvestment: '#86ac48',
        subsequentInvestment: '#3968af',
      },
      types: {
        first: 'Finanzierung',
        second: 'Bestand',
      },
      draw: 0,
      date: {
        first: '',
        second: '',
      },
      investments: [],
      commissions: [],
      investmentsByPeriodSeriesFirst: [],
      investmentsByProjectSeriesFirst: [],
      investmentsByPeriodSeriesSecond: [],
      investmentsByProjectSeriesSecond: [],
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
        {
          title: 'Individuell',
          value: 'custom',
        },
      ],
      investmentTableColumns: [
        {
          name: 'investor_id',
          label: 'ID',
          align: 'right',
          small: true,
          width: 80,
        },
        {
          name: 'investor',
          label: 'Investor',
          width: 150,
        },
        {
          name: 'project_name',
          label: 'Projekt',
          width: 200,
        },
        {
          name: 'provision_type',
          label: 'Provisionstyp',
          width: 200,
        },
        {
          name: 'amount',
          label: 'Betrag',
          format: 'currency',
        },
        {
          name: 'created_at',
          label: 'Datum',
          format: 'date',
          width: 80,
        },
      ],
    };
  },

  watch: {
    periodSelected: function (value) {
      if (value != 'custom') {
        this.date.first = '';
        this.date.second = '';
        this.getInvestments();
      }
    },
    date: {
      handler: function (value) {
        if (value.first != '' || value.second != '') {
          this.periodSelected = 'custom';
          this.getInvestments();
        }
      },
      deep: true,
    },
  },

  computed: {
    investmentTableData() {
      return {
        columns: this.investmentTableColumns,
        rows: this.investments,
      };
    },

    commissionsOptions() {
      return {
        chart: {
          id: 'commissions',
          toolbar: {
            show: false,
          },
          zoom: {
            enabled: false,
          },
        },
        dataLabels: {
          enabled: false,
        },
        xaxis: {
          type: "datetime",
        },
        stroke: {
          curve: 'smooth'
        },
        colors: [this.colors.subsequentInvestment],
        markers: {
          size: 5
        },
      }
    },

    investmentsByPeriodOptionsFirst() {
      return {
        colors: [this.colors.firstInvestment, this.colors.subsequentInvestment],
        chart: {
          id: 'investments-by-period-first',
          stacked: true,
          toolbar: {
            show: true,
            tools: {
              download: false,
              selection: false,
              reset: false,
              zoom: false,
              pan: true,
            },
            autoSelected: 'pan',
          },
          zoom: {
            enabled: true,
          },
        },
        dataLabels: {
          enabled: false,
        },
        xaxis: {},
      };
    },

    investmentsByPeriodOptionsSecond() {
      return {
        colors: [this.colors.firstInvestment, this.colors.subsequentInvestment],
        chart: {
          id: 'investments-by-period-second',
          stacked: true,
          toolbar: {
            show: true,
            tools: {
              download: false,
              selection: false,
              reset: false,
              zoom: false,
              pan: true,
            },
            autoSelected: 'pan',
          },
          zoom: {
            enabled: true,
          },
        },
        dataLabels: {
          enabled: false,
        },
        xaxis: {},
      };
    },

    investmentsByProjectOptionsFirst() {
      return {
        colors: [this.colors.firstInvestment, this.colors.subsequentInvestment],
        chart: {
          id: 'investments-by-project-first',
          stacked: true,
          toolbar: {
            show: false,
          },
          zoom: {
            enabled: true,
          },
        },
        dataLabels: {
          enabled: false,
        },
        xaxis: {},
        plotOptions: {
          bar: {
            horizontal: true,
          },
        },
      };
    },

    investmentsByProjectOptionsSecond() {
      return {
        colors: [this.colors.firstInvestment, this.colors.subsequentInvestment],
        chart: {
          id: 'investments-by-project-second',
          stacked: true,
          toolbar: {
            show: false,
          },
          zoom: {
            enabled: true,
          },
        },
        dataLabels: {
          enabled: false,
        },
        xaxis: {},
        plotOptions: {
          bar: {
            horizontal: true,
          },
        },
      };
    },

    commissionsData() {
      let characters = this.periodSelected === null ? 7 : 10;

      let groupedData = map(
        groupBy(
          this.commissions, o => o.created_at.slice(0, characters)),
        (value, key) => ({
          x: key,
          y: sumBy(value, 'amount'),
        })
      );

      return [{
        name: 'Provision',
        data: orderBy(groupedData, 'x'),
      }];
    },
  },

  created() {
    this.getInvestments();
  },

  methods: {
    getInvestmentsByProject(type) {
      let investments = filter(this.investments, function(o) {return o.provision_type.indexOf(type) !== -1});
      let projects = this.getInvestmentsProjects(type);

      let dataFirstInvestments = map(
        groupBy(
          groupBy(investments, 'investment_type').first, 'project_name'),
        (value, key) => ({
          project_name: key,
          amount: sumBy(value, 'amount'),
        })
      );

      let dataSubsequentInvestments = map(
        groupBy(
          groupBy(investments, 'investment_type').subsequent, 'project_name'),
        (value, key) => ({
          project_name: key,
          amount: sumBy(value, 'amount'),
        })
      );

      forEach(projects.categories, function(value) {
        if (find(dataFirstInvestments, ['project_name', value]) === undefined) {
          dataFirstInvestments.push(
            {
              project_name: value,
              amount: 0,
            }
          );
        }
        if (find(dataSubsequentInvestments, ['project_name', value]) === undefined) {
          dataSubsequentInvestments.push(
            {
              project_name: value,
              amount: 0,
            }
          );
        }
      });

      return [
        {
          name: 'Erstinvestments',
          data: map(
            orderBy(dataFirstInvestments, 'project_name'),
            'amount'
          ),
        },
        {
          name: 'Folgeinvestments',
          data: map(
            orderBy(dataSubsequentInvestments, 'project_name'),
            'amount'
          ),
        },
      ];
    },

    getInvestmentsProjects(type) {
      let investments = filter(this.investments, function(o) {return o.provision_type.indexOf(type) !== -1});

      let categories = map(
        sortBy(
          map(
            groupBy(
              investments, 
              obj => obj.project_name
            ),
            (value, key) => ({
              project_name: key,
              value: value,
            })
          ),
          'project_name'),
        'project_name'
      );

      return {
        type: 'category',
        labels: {
          maxHeight: 70,
          style: {
            fontSize: '10px',
          },
        },
        categories: categories,
      };
    },

    getInvestmentsPeriods(type) {
      let characters = this.periodSelected === null ? 7 : 10;
      let investments = filter(this.investments, function(o) {return o.provision_type.indexOf(type) !== -1});

      let categories = map(
        sortBy(
          map(
            groupBy(
              investments, 
              obj => obj.created_at.slice(0, characters)
            ),
            (value, key) => ({
              created_at: key,
              value: value,
            })
          ),
          'created_at'),
        'created_at'
      );
      let firstBar = categories.length > 6 ? new Date(categories[categories.length-6]).getTime() : null;

      return {
        type: 'datetime',
        min: firstBar,
        categories: categories,
      };
    },
    
    getInvestmentsByPeriod(type) {
      let characters = this.periodSelected === null ? 7 : 10;
      let investments = filter(this.investments, function(o) {return o.provision_type.indexOf(type) !== -1});
      let periods = this.getInvestmentsPeriods(type);

      let dataFirstInvestments = map(
        groupBy(
          groupBy(investments, 'investment_type').first, o => o.created_at.slice(0, characters)),
        (value, key) => ({
          created_at: key,
          amount: sumBy(value, 'amount'),
        })
      );

      let dataSubsequentInvestments = map(
        groupBy(
          groupBy(investments, 'investment_type').subsequent, o => o.created_at.slice(0, characters)),
        (value, key) => ({
          created_at: key,
          amount: sumBy(value, 'amount'),
        })
      );

      forEach(periods.categories, function(value) {
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

    formatSum(sum) {
      return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(sum);
    },

    getInvestmentsSum(investmentType, isFirstInvestment) {
      let type = this.types[investmentType];
      let investments = filter(this.investments, function(o) {return o.provision_type.indexOf(type) !== -1});

      let sum = sumBy(
        filter(investments, ['is_first_investment', isFirstInvestment]),
        'amount'
      );
      return this.formatSum(sum);
    },

    getInvestments() {
      this.draw++;
      let params = {
        period: this.periodSelected,
        draw: this.draw,
        ... this.date,
      };
      this.loading = true;

      axios.get(this.api, {
        params,
      }).then(({ data }) => {
        if (data.draw == this.draw) {
          this.investments = data.investments;
          this.commissions = data.commissions;
          this.loading = false;

          //first column
          this.investmentsByPeriodSeriesFirst = this.getInvestmentsByPeriod(this.types.first);
          this.investmentsByPeriodOptionsFirst.xaxis = this.getInvestmentsPeriods(this.types.first);
          this.investmentsByProjectSeriesFirst = this.getInvestmentsByProject(this.types.first);
          this.investmentsByProjectOptionsFirst.xaxis = this.getInvestmentsProjects(this.types.first);

          //second column
          this.investmentsByPeriodSeriesSecond = this.getInvestmentsByPeriod(this.types.second);
          this.investmentsByPeriodOptionsSecond.xaxis = this.getInvestmentsPeriods(this.types.second);
          this.investmentsByProjectSeriesSecond = this.getInvestmentsByProject(this.types.second);
          this.investmentsByProjectOptionsSecond.xaxis = this.getInvestmentsProjects(this.types.second);
        }
      }).catch(() => {
          this.loading = false;
      });
    },
  },
};
</script>
