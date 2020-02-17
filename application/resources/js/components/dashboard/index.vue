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
        <flat-pickr
          v-model="date.second"
          class="form-control"
        />
      </div>
      <div class="col-lg-2 pl-2">
        <label class="m-0">von:</label>
        <flat-pickr
          v-model="date.first"
          class="form-control"
        />
      </div>
    </div>
    <div 
      v-if="commissions.length > 0 && !loadingCommissions"
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
    
    <div v-else-if="!loadingCommissions" class="rounded shadow-sm bg-white p-3 w-100 mr-2 mt-3 lead text-center text-muted">
      Keine Provisionsdaten verfügbar
    </div>

    <div v-else-if="loadingCommissions" class="rounded shadow-sm bg-white p-3 w-100 mr-2 mt-3 lead text-center text-muted">
      <vue-simple-spinner
        size="large"
      ></vue-simple-spinner>
      Provisionsdaten werden geladen
    </div>

    <div v-if="investments.length > 0 && !loading">
      <div class="d-flex mt-3">
        <div class="rounded shadow-sm p-3 w-50 mr-2" style="background:#3968af">
          <div class="d-flex justify-content-center h3 mb-0 text-white">
            {{ types.first }}
          </div>
        </div>
        <div class="rounded shadow-sm p-3 w-50 ml-2" style="background:#86ac48">
          <div class="d-flex justify-content-center h3 mb-0 text-white">
            {{ types.second }}
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
        <div
          class="rounded shadow-sm bg-white py-3 w-50 mr-2"
          style="max-width: 485.33px;"
        >
          <div>
            <apexchart
              type="bar"
              :options="investmentsByPeriodOptionsFirst"
              :series="investmentsByPeriodSeriesFirst"
              class="mr-3"
              height="285"
            ></apexchart>
          </div>
          <div class="project-container">
            <apexchart
              type="bar"
              :options="investmentsByProjectOptionsFirst"
              :series="investmentsByProjectSeriesFirst"
              :height="getChartHeightFirst"
            ></apexchart>
          </div>
        </div>
        
        <div
          class="rounded shadow-sm bg-white py-3 w-50 ml-2"
          style="max-width: 485.33px;"
        >
          <div>
            <apexchart
              type="bar"
              :options="investmentsByPeriodOptionsSecond"
              :series="investmentsByPeriodSeriesSecond"
              class="mr-3"
              height="285"
            ></apexchart>
          </div>
          <div class="project-container">
            <apexchart
              type="bar"
              :options="investmentsByProjectOptionsSecond"
              :series="investmentsByProjectSeriesSecond"
              :height="getChartHeightSecond"
            ></apexchart>
          </div>
        </div>
        
      </div>
    </div>

    <div v-else-if="loading" class="rounded shadow-sm bg-white p-3 w-100 mr-2 mt-3 lead text-center text-muted">
      <vue-simple-spinner
        size="large"
      ></vue-simple-spinner>
      Investmentdaten werden geladen
    </div>

    <div v-else class="rounded shadow-sm bg-white p-3 w-100 mr-2 mt-3 lead text-center text-muted">
      Keine Investmentdaten verfügbar
    </div>

    <div v-if="investments.length > 0 && !loading" class="rounded shadow-sm bg-white p-3 w-100 mr-2 mt-3">
      <div>
        <span class="h3">
          Investments
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
import FlatPickr from 'vue-flatpickr-component';
import { German } from 'flatpickr/dist/l10n/de';
import 'flatpickr/dist/flatpickr.css';


export default {
  components: {
    'vue-simple-spinner': Spinner,
    'flat-pickr': FlatPickr,
  },

  props: {
    apiInvestments: {
      type: String,
      required: true,
    },
    apiCommissions: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      loading: true,
      loadingCommissions: true,
      noData: {
        text: 'keine Daten verfügbar',
        align: 'center',
        verticalAlign: 'middle',
        offsetX: 0,
        offsetY: 0,
        style: {
          color: undefined,
          fontSize: '14px',
          fontFamily: undefined
        }
      },
      colors: {
        first: {
          firstInvestment: '#3968af',
          subsequentInvestment: '#7ba0d9',
        },
        second: {
          firstInvestment: '#86ac48',
          subsequentInvestment: '#c4e292',
        },
      },
      types: {
        first: 'Exporo Finanzierung',
        second: 'Exporo Bestand',
      },
      drawInvestments: 0,
      drawCommissions: 0,
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
        },
        {
          name: 'project_type',
          label: 'Projekttyp',
        },
        {
          name: 'amount',
          label: 'Betrag',
          format: 'currency',
          width: 80,
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
        this.getCommissions();
        this.getInvestments();
      }
    },
    date: {
      handler: function (value) {
        if (value.first != '' || value.second != '') {
          this.periodSelected = 'custom';
          this.getCommissions();
          this.getInvestments();
        }
      },
      deep: true,
    },
  },

  computed: {
    getChartHeightFirst() {
      let number = this.investmentsByProjectSeriesFirst[0] ? this.investmentsByProjectSeriesFirst[0].data.length : 0;
      return this.getChartHeight(number);
    },

    getChartHeightSecond() {
      let number = this.investmentsByProjectSeriesSecond[0] ? this.investmentsByProjectSeriesSecond[0].data.length : 0;
      return this.getChartHeight(number);
    },

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
        yaxis: {
          min: 0,
          forceNiceScale: true,
          labels: {
            formatter: function (value) {
              return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(value);
            }
          },
        },
        stroke: {
          curve: 'smooth'
        },
        colors: [this.colors.first.subsequentInvestment],
        markers: {
          size: 5
        },
        noData: this.noData,
      }
    },

    investmentsByPeriodOptionsFirst() {
      return {
        colors: [this.colors.first.firstInvestment, this.colors.first.subsequentInvestment],
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
        yaxis: {
          labels: {
            formatter: function (value) {
              return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(value);
            }
          },
        },
        noData: this.noData,
      };
    },

    investmentsByPeriodOptionsSecond() {
      return {
        colors: [this.colors.second.firstInvestment, this.colors.second.subsequentInvestment],
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
        yaxis: {
          labels: {
            formatter: function (value) {
              return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(value);
            }
          },
        },
        noData: this.noData,
      };
    },

    investmentsByProjectOptionsFirst() {
      return {
        legend: {
          show: false,
        },
        colors: [this.colors.first.firstInvestment, this.colors.first.subsequentInvestment],
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
        yaxis: {
          labels: {
            formatter: function (value) {
              return isNaN(value) ? value : new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(value);
            }
          },
        },
        plotOptions: {
          bar: {
            horizontal: true,
          },
        },
        noData: this.noData,
      };
    },

    investmentsByProjectOptionsSecond() {
      return {
        legend: {
          show: false,
        },
        colors: [this.colors.second.firstInvestment, this.colors.second.subsequentInvestment],
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
        yaxis: {
          labels: {
            formatter: function (value) {
              return isNaN(value) ? value : new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(value);
            }
          },
        },
        plotOptions: {
          bar: {
            horizontal: true,
          },
        },
        noData: this.noData,
      };
    },

    commissionsData() {
      let characters =  7;

      if (this.commissions.length === 0) {
        return null;
      }

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
    this.getCommissions();
    this.getInvestments();
  },

  methods: {
    getInvestmentsByProject(type) {
      let investments = filter(this.investments, function(o) {return o.project_type.indexOf(type) !== -1});
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

      if (dataFirstInvestments.length === 0 && dataSubsequentInvestments.length === 0) {
        return [];
      }

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
      let investments = filter(this.investments, function(o) {return o.project_type.indexOf(type) !== -1});

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
          //rotate: 0,
          //rotateAlways: true,
          maxHeight: 70,
          style: {
            fontSize: '10px',
          },
          formatter: function (value) {
            return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(value);
          },
        },
        categories: categories,
      };
    },

    getInvestmentsPeriods(type) {
      let characters = this.periodSelected === null ? 7 : 10;
      let investments = filter(this.investments, function(o) {return o.project_type.indexOf(type) !== -1});

      let categories = map(
        sortBy(
          map(
            groupBy(
              investments, 
              obj => obj.created_at.slice(0, characters)
            ),
            (value, key) => ({
              created_at: characters === 7 ? key + '-01' : key,
              value: value,
            })
          ),
          'created_at'),
        'created_at'
      );
      let firstBar = categories.length > 30 ? new Date(categories[categories.length-30]).getTime() : null;

      //prevent display bug
      if (categories.length === 1) {
        let date = new Date(categories[0]);
        categories.push(date.setDate(date.getDate() + 1));
      }

      return {
        type: 'datetime',
        min: firstBar,
        categories: categories,
        labels: {
          formatter: function(value) {
            return new Date(value).toLocaleDateString("de-DE");
          },
        },
      };
    },
    
    getInvestmentsByPeriod(type) {
      let characters = this.periodSelected === null ? 7 : 10;
      let investments = filter(this.investments, function(o) {return o.project_type.indexOf(type) !== -1});
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

      let mappedFirstInvestments = map(
        orderBy(dataFirstInvestments, 'created_at'),
        'amount'
      );
      let mappedSubsequentInvestments = map(
        orderBy(dataSubsequentInvestments, 'created_at'),
        'amount'
      );

      if (mappedFirstInvestments.length === 0 && mappedSubsequentInvestments.length === 0) {
        return [];
      }

      //prevent display bug
      if (mappedFirstInvestments.length === 1 && mappedSubsequentInvestments.length === 1) {
        mappedFirstInvestments.push(0);
        mappedSubsequentInvestments.push(0);
      }

      return [
        {
          name: 'Erstinvestments',
          data: mappedFirstInvestments,
        },
        {
          name: 'Folgeinvestments',
          data: mappedSubsequentInvestments,
        },
      ];
    },

    formatSum(sum) {
      return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(sum);
    },

    getInvestmentsSum(investmentType, isFirstInvestment) {
      let type = this.types[investmentType];
      let investments = filter(this.investments, function(o) {return o.project_type.indexOf(type) !== -1});

      let sum = sumBy(
        filter(investments, ['is_first_investment', isFirstInvestment]),
        'amount'
      );
      return this.formatSum(sum);
    },

    getChartHeight(number) {
      const minHeight = 280;
      const axisHeight = 60;
      const barHeight = 30;
      let height = barHeight * number + axisHeight;

      return Math.max(height, minHeight);
    },

    getInvestments() {
      this.drawInvestments++;
      let params = {
        period: this.periodSelected,
        draw: this.drawInvestments,
        ... this.date,
      };
      this.loading = true;

      axios.get(this.apiInvestments, {
        params,
      }).then(({ data }) => {
        if (data.draw == this.drawInvestments) {
          this.investments = data.investments;
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

    getCommissions() {
      this.drawCommissions++;
      let params = {
        period: this.periodSelected,
        draw: this.drawCommissions,
        ... this.date,
      };
      this.loadingCommissions = true;

      axios.get(this.apiCommissions, {
        params,
      }).then(({ data }) => {
        if (data.draw == this.drawCommissions) {
          this.commissions = data.commissions;
          this.loadingCommissions = false;
        }
      }).catch(() => {
        this.loadingCommissions = false;
      });
    },
  },
};
</script>

<style lang="scss">
  .project-container {
    max-height: 350px;
    overflow-y: auto;
    overflow-x: hidden;
  }
</style>