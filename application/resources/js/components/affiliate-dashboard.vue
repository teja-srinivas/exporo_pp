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

    <div v-if="clicks.length > 0 && !loading">
      <div class="d-flex mt-3">
        <div class="rounded shadow-sm bg-white p-3 w-25 mr-2">
          <div class="h4 mb-0">
            <div class="d-flex justify-content-center">
              {{ countClicks }}
            </div>
            <div class="d-flex justify-content-center">
              Klicks
            </div>
          </div>
        </div>
        <div class="rounded shadow-sm bg-white p-3 w-25 ml-2 mr-2">
          <div class="h4 mb-0">
            <div class="d-flex justify-content-center">
              {{ countInvestors }}
            </div>
            <div class="d-flex justify-content-center">
              Kunden
            </div>
          </div>
        </div>
        <div class="rounded shadow-sm bg-white p-3 w-25 ml-2 mr-2">
          <div class="h4 mb-0">
            <div class="d-flex justify-content-center">
              {{ countFirstInvestments }}
            </div>
            <div class="d-flex justify-content-center">
              Neue Investoren
            </div>
          </div>
        </div>
        <div class="rounded shadow-sm bg-white p-3 w-25 ml-2">
          <div class="h4 mb-0">
            <div class="d-flex justify-content-center">
              {{ countTotalInvestments }}
            </div>
            <div class="d-flex justify-content-center">
              Investoren
            </div>
          </div>
        </div>
      </div>
      <div class="rounded shadow-sm bg-white p-3 w-100 mr-2 mt-3 text-muted">
        Klicks nach:
        <button
          v-for="group in groups"
          class="btn ml-3"
          :class="type === group.name ? 'btn-secondary' : 'btn-primary'"
          type="button"
          @click="selectedType = group.name"
        >
          {{ group.displayName }}
        </button>

        <div class="row">
          <div class="col-9">
            <apexchart
              type="area"
              :options="chartOptions"
              :series="filteredData"
            ></apexchart>
          </div>

          <div class="col-3">
            <div class="" v-for="group in groups">
              <div
                v-if="groupedClicks[group.name] && groupedClicks[group.name].length > 0"
                class=""
              >
                <div class="h6 text-muted text-uppercase">
                  <!--<input
                    type="checkbox"
                    :id="group.name"
                    @change="toggleGroup(group.name)"
                    :checked="groupedClicks[group.name].length == selections[group.name].length"
                  />
                  <label :for="group.name">{{ group.displayName }}</label>-->
                  {{ group.displayName }}
                  <div class="dropdown-divider"></div>
                </div>
                <div v-for="(child, index) in groupedClicks[group.name]" class="d-flex align-items-center mb-2"><!--class=" ml-4"-->
                  <input
                    type="checkbox"
                    :id="child"
                    v-model="selections[group.name]"
                    :value="child"
                  />
                  <label :for="child" class="text-truncate mb-0 ml-2" style="max-width: 180px;">
                    {{ translate(child) }}
                    
                  </label>
                  <span
                    v-if="group.name === type"
                    class="square rounded ml-2"
                    :style="getBackground(index)"
                  ></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else-if="loading" class="rounded shadow-sm bg-white p-3 w-100 mr-2 mt-3 lead text-center text-muted">
      <vue-simple-spinner
        size="large"
      ></vue-simple-spinner>
      Daten werden geladen
    </div>

    <div v-else class="rounded shadow-sm bg-white p-3 w-100 mr-2 mt-3 lead text-center text-muted">
      Keine Daten verfügbar
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import countBy from 'lodash/countBy';
import forEach from 'lodash/forEach';
import groupBy from 'lodash/groupBy';
import keys from 'lodash/keys';
import pull from 'lodash/pull';
import map from 'lodash/map';
import orderBy from 'lodash/orderBy';
import find from 'lodash/find';
import sortBy from 'lodash/sortBy';
import includes from 'lodash/includes';
import filter from 'lodash/filter';
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
    api: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      loading: true,
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
      colors: [
        '#ec7063',
        '#5dade2',
        '#58d68d',
        '#f7dc6f',
        '#eb984e',
        '#af7ac5',
        '#b2babb',
        '#641e16',
        '#0b5345',
        '#1b4f72',
        '#7d6608',
        '#784212',
        '#4a235a',
      ],
      draw: 0,
      date: {
        first: '',
        second: '',
      },
      clicks: [],
      categories: [],
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
      groups: [
        /*{
          name: 'project_type',
          displayName: 'Projekttyp',
        },*/
        {
          name: 'affiliate_type',
          displayName: 'Werbemittel',
        },
        {
          name: 'link_title',
          displayName: 'Links',
        },
        /*{
          name: 'device',
          displayName: 'Gerätetyp',
        },*/
      ],
      selections: {
        link_title: [],
        affiliate_type: [],
        //project_type: [],
        //device: [],
      },
      selectedType: null,
      translation: {
        desktop: 'Desktop',
        phone: 'Mobil',
        null: 'kein Investment',
        link: 'Link',
        banner_link: 'Banner',
        embed: 'Iframe',
      },
    };
  },

  watch: {
    periodSelected: function (value) {
      if (value != 'custom') {
        this.date.first = '';
        this.date.second = '';
        this.getClicks();
      }
    },
    date: {
      handler: function (value) {
        if (value.first != '' || value.second != '') {
          this.periodSelected = 'custom';
          this.getClicks();
        }
      },
      deep: true,
    },
  },

  computed: {
    type() {
      return this.selectedType || this.groups[0].name;
    },

    groupedClicks() {
      return {
        link_title: keys(groupBy(this.clicks, 'link_title')),
        affiliate_type: keys(groupBy(this.clicks, 'affiliate_type')),
        project_type: keys(groupBy(this.clicks, 'project_type')),
        device: keys(groupBy(this.clicks, 'device')),
      };
    },

    countClicks() {
      return this.clicks.length;
    },

    countInvestors() {
      return keys(
        groupBy(
          filter(this.clicks, function(o) { return o.investor_id === "null" ? false : true; }),
        'investor_id')
      ).length;
    },

    countFirstInvestments() {
      let groupedInvestments = groupBy(
          filter(this.clicks, function(o) { return o.investor_id === "null" ? false : true; }),
        'investment_type'
       );

       return groupedInvestments.first ? groupedInvestments.first.length : 0;
    },

    countTotalInvestments() {
      return countBy(this.clicks, function(o) { return o.investor_id === "null" ? false : true; }).true || 0;
    },

    filteredData() {
      const selectedType = this.type;
      const characters = this.periodSelected === null ? 7 : 10;
      let filtered = this.clicks.map(a => Object.assign({}, a));
      const selections = this.selections;
      const groupedClicks = this.groupedClicks;
      let series = [];
      let clickPeriods = this.clickPeriods;
      let _this = this;

      for(let prop in selections) {
        forEach(filtered, function(filteredValue, filteredKey){
          if (filtered[filteredKey] && !selections[prop].includes(filteredValue[prop])) {
            delete filtered[filteredKey];
          }
        });
      };

      filtered = filter(filtered, function(o) {return o != null});

      groupedClicks[selectedType].forEach(function(element) {
        if (selections[selectedType].includes(element)) {
          var data = map(
            groupBy(
              groupBy(filtered, obj => obj[selectedType])[element], o => o.created_at.slice(0, characters)),
            (value, key) => ({
              created_at: key,
              amount: value.length,
            })
          );

          forEach(clickPeriods.categories, function(value) {
            if (find(data, ['created_at', value]) === undefined) {
              data.push(
                {
                  created_at: value,
                  amount: 0,
                }
              );
            }
          });

          series.push({
            name: _this.translate(element),
            data: map(
              orderBy(data, 'created_at'),
              'amount'
            ),
          });
        }
      });

      return series;
    },

    clickPeriods() {
      let characters = this.periodSelected === null ? 7 : 10;

      let categories = map(
        sortBy(
          map(
            groupBy(
              this.clicks, 
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
        categories.push(date.setDate(date.getDate() - 1));
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

    chartOptions() {
      return {
        chart: {
          id: 'clicks-by-period',
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
          enabled: true,
          formatter: function (val, opts) {
            return val > 0 ? val : '';
          },
        },
        xaxis: this.clickPeriods,
        yaxis: {
          labels: {
            formatter: function (value) {
              return Math.round(value);
            }
          },
        },
        noData: this.noData,
        stroke: {
          curve: 'straight',
        },
        legend: {
          show: false,
        },
        colors: this.selectedColors,
        fill: {
          type: 'gradient',
          gradient: {
            opacityFrom: 0.6,
            opacityTo: 0.8,
          }
        },
      };
    },

    selectedColors() {
      let colors = [];

      for (let key in this.groupedClicks[this.type]) {
        if (this.selections[this.type].includes(this.groupedClicks[this.type][key])) {
          colors.push(this.colors[key]);
        }
      }

      return colors.length > 0 ? colors : this.colors;
    },
  },

  created() {
    this.getClicks();
  },

  methods: {
    getClicks() {
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
          this.clicks = data.clicks;
          this.loading = false;
          this.selectAll();
        }
      }).catch(() => {
          this.loading = false;
      });
    },

    toggleGroup(group) {
      if (this.selections[group].length === this.groupedClicks[group].length) {
        this.selections[group] = [];
      } else {
        this.selections[group] = this.groupedClicks[group];
      }
    },

    selectAll() {
      for(let prop in this.selections) {
        this.selections[prop] = this.groupedClicks[prop];
      }
    },

    translate(value) {
      return this.translation[value] || value;
    },

    getBackground(index) {
      return `background: ${this.colors[index]}`;
    }
  },
};
</script>

<style lang="scss">
  .square {
    height: 13px;
    width: 13px;
    display: inline-block;
  }
</style>