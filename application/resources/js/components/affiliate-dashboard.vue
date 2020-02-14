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
              Total Investoren
            </div>
          </div>
        </div>
      </div>
      <div  class="rounded shadow-sm bg-white p-3 w-100 mr-2 mt-3 text-muted">
        
        {{ selections }}----------{{ groupedClicks }}-------------{{ filteredData }}
        <div class="d-flex">
          <div>
            
          </div>
          <div>
            <div class="" v-for="group in groups">
              <div
                v-if="groupedClicks[group.name] && groupedClicks[group.name].length > 0"
                class=""
              >
                <div class="">
                  <input
                    type="checkbox"
                    :id="group.name"
                    @change="toggleGroup(group.name)"
                    :checked="groupedClicks[group.name].length == selections[group.name].length"
                  />
                  <label :for="group.name">{{ group.displayName }}</label>
                </div>
                <div v-for="child in groupedClicks[group.name]" class=" ml-4">
                  <input
                    type="checkbox"
                    :id="child"
                    v-model="selections[group.name]"
                    :value="child"
                  />
                  <label :for="child">{{ child }}</label>
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
        :line-fg-color="colors.subsequentInvestment"
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
import includes from 'lodash/includes';
import filter from 'lodash/filter';
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
        {
          name: 'link_title',
          displayName: 'Links',
        },
        {
          name: 'affiliate_type',
          displayName: 'Werbemittel',
        },
        {
          name: 'project_type',
          displayName: 'Produkttyp',
        },
        {
          name: 'device',
          displayName: 'Gerätetyp',
        },
      ],
      selections: {
        link_title: [],
        affiliate_type: [],
        project_type: [],
        device: [],
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
          filter(this.clicks, function(o) { return o.investor_id === null ? false : true; }),
        'investor_id')
      ).length;
    },

    countFirstInvestments() {
      let groupedInvestments = groupBy(
          filter(this.clicks, function(o) { return o.investor_id === null ? false : true; }),
        'investment_type'
       );

       return groupedInvestments.first ? groupedInvestments.first.length : 0;
    },

    countTotalInvestments() {
      return countBy(this.clicks, function(o) { return o.investor_id === null ? false : true; }).true || 0;
    },

    filteredData() {
      var filtered = this.clicks.map(a => Object.assign({}, a));
      var selections = this.selections;

        for(let prop in selections) {
          forEach(filtered, function(filteredValue, filteredKey){
            if (filtered[filteredKey] && !selections[prop].includes(filteredValue[prop])) {
              delete filtered[filteredKey];
            }
          });
        };

        return filtered;
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
          if (this.draw === 1 ) {
            this.selectAll();
          }
        }
      }).catch(() => {
          this.loading = false;
      });
    },
//TODO: Darstellung als stacked Column Chart
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
  },
};
</script>

<style lang="scss">
  
</style>