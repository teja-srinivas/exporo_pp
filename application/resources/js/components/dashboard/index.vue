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
            <apexchart
              type="bar"
              :options="investmentsByPeriodOptions"
              :series="investmentsByPeriodSeries"
            ></apexchart>
          </div>
          <div>
            <apexchart
              type="bar"
              :options="investmentsByProjectOptions"
              :series="investmentsByProjectSeries"
              :height="320"
            ></apexchart>
          </div>
        </div>
        <div class="rounded shadow-sm bg-white p-3 w-50 ml-2">
          <data-table v-bind="tableData" with-details minimal-foot minimal-styling page-size="15">
            <template v-slot="{ row }">
              <div class="ml-4">
                <div class="pl-2 border-bottom">
                  <div>
                    <strong>{{ row.investor }}</strong>
                  </div>
                  <div>
                    <small>Investor</small>
                  </div>
                </div>
                <div class="pl-2 border-bottom">
                  <div>
                    <strong>{{ row.provision_type }}</strong>
                  </div>
                  <div>
                    <small>Provisionstyp</small>
                  </div>
                </div>
                <div class="pl-2 pt-2">
                  <h4><span class="badge badge-primary" :style="{ backgroundColor: row.is_first_investment ? colors.firstInvestment : colors.subsequentInvestment }">{{ row.is_first_investment ? 'Erstinvestment' : 'Folgeinvestment' }}</span></h4>
                </div>
              </div>
            </template>
          </data-table>
        </div>
      </div>
      <div class="rounded shadow-sm bg-white p-3 w-100 mr-2 mt-3">
        <apexchart
          type="bar"
          :options="twelveMonthsOptions"
          :series="twelveMonthsData"
          :height="280"
        ></apexchart>
      </div>
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
    investmentsTwelveMonths: {
      type: Array,
      required: true,
    },
  },

  data() {
    return {
      colors: {
        firstInvestment: '#86ac48',
        subsequentInvestment: '#3968af',
      },
      draw: 0,
      investments: [],
      investmentsByPeriodSeries: [],
      investmentsByProjectSeries: [],
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
      tableColumns: [
        {
          name: 'project_name',
          label: 'Projekt',
          width: '40%',
        },
        {
          name: 'amount',
          label: 'Betrag',
          format: 'currency',
          width: '25%',
        },
        {
          name: 'created_at',
          label: 'Datum',
          format: 'date',
          width: '25%',
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
      let categories = map(
        sortBy(
          map(
            groupBy(
              this.investments, 
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

      return {
        type: 'datetime',
        min: new Date(categories[categories.length-6]).getTime(),
        categories: categories,
      };
    },

    investmentsByProject() {
      let dataFirstInvestments = map(
        groupBy(
          groupBy(this.investments, 'investment_type').first, 'project_name'),
        (value, key) => ({
          project_name: key,
          amount: sumBy(value, 'amount'),
        })
      );

      let dataSubsequentInvestments = map(
        groupBy(
          groupBy(this.investments, 'investment_type').subsequent, 'project_name'),
        (value, key) => ({
          project_name: key,
          amount: sumBy(value, 'amount'),
        })
      );

      forEach(this.investmentsProjects.categories, function(value) {
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

    investmentsProjects() {
      let categories = map(
        sortBy(
          map(
            groupBy(
              this.investments, 
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

    tableData() {
      return {
        columns: this.tableColumns,
        rows: this.investments,
      };
    },

    twelveMonths() {
      let categories = map(
        sortBy(
          map(
            groupBy(
              this.investmentsTwelveMonths, 
              obj => obj.created_at.slice(0, 7)
            ),
            (value, key) => ({
              created_at: key,
              value: value,
            })
          ),
          'created_at'),
        'created_at'
      );

      return {
        type: 'datetime',
        categories: categories,
      };
    },

    twelveMonthsData() {
      let dataFirstInvestments = map(
        groupBy(
          groupBy(this.investmentsTwelveMonths, 'investment_type').first, o => o.created_at.slice(0, 7)),
        (value, key) => ({
          created_at: key,
          amount: sumBy(value, 'amount'),
        })
      );

      let dataSubsequentInvestments = map(
        groupBy(
          groupBy(this.investmentsTwelveMonths, 'investment_type').subsequent, o => o.created_at.slice(0, 7)),
        (value, key) => ({
          created_at: key,
          amount: sumBy(value, 'amount'),
        })
      );

      forEach(this.twelveMonths.categories, function(value) {
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

    investmentsByPeriodOptions() {
      return {
        colors: [this.colors.firstInvestment, this.colors.subsequentInvestment],
        chart: {
          id: 'investments-by-period',
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

    investmentsByProjectOptions() {
      return {
        colors: [this.colors.firstInvestment, this.colors.subsequentInvestment],
        chart: {
          id: 'investments-by-project',
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
      };
    },

    twelveMonthsOptions() {
        return {
          colors: [this.colors.firstInvestment, this.colors.subsequentInvestment],
          chart: {
            id: 'investments-twelve-months',
            stacked: true,
            toolbar: {
              show: false,
            },
          },
          dataLabels: {
            enabled: false,
          },
          xaxis: this.twelveMonths,
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
          this.investmentsByPeriodSeries = this.investmentsByPeriod;
          this.investmentsByPeriodOptions.xaxis = this.investmentsPeriods;
          this.investmentsByProjectSeries = this.investmentsByProject;
          this.investmentsByProjectOptions.xaxis = this.investmentsProjects;
        } else {
          ApexCharts.exec("investments-by-period", "updateOptions", {
            xaxis: this.investmentsPeriods,
            series: this.investmentsByPeriod,
          });
          ApexCharts.exec("investments-by-project", "updateOptions", {
            xaxis: this.investmentsProjects,
            series: this.investmentsByProject,
          });
        }
        this.draw++;
      });       
    },
  },
};
</script>

<style lang="scss">
  table {
    width: 100%;
  }

  thead, tbody, tr, td, th { display: block; }

  tr:after {
    content: ' ';
    display: block;
    visibility: hidden;
    clear: both;
  }

  tbody {
    height: 490px;
    overflow-y: auto;
  }

  tbody td, thead th {
    float: left;
  }

  tbody td.bg-white {
    width: 100%;
  }
</style>
