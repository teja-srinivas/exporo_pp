<template>
  <div
    :class="$style.wrapper"
    v-html="tokenize(value)"
  />
</template>

<script>
import { formatMoney, formatNumber } from '../../utils/formatters';

export default {
  props: {
    value: {
      type: String,
      required: true,
    },
    commission: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      variables: {
        bonus: {
          value: this.commission.bonus,
          formatter: formatNumber,
        },
        investment: {
          value: this.commission.model.investment,
          formatter: formatMoney,
        },
        laufzeit: {
          value: this.commission.model.project.runtimeFactor,
          formatter: formatNumber,
        },
        marge: {
          value: this.commission.model.project.margin,
          formatter: formatNumber,
        },
      },
    };
  },

  methods: {
    tokenize(str) {
      return str
        // Replace tokens with fancy boxes that contain their value
        .replace(/[a-z]+/ig, (substring) => {
          const name = substring[0].toUpperCase() + substring.substr(1);
          const variable = this.variables[substring] || { };
          const formatted = variable.formatter(variable.value);
          const classes = [
            this.$style.label,
            variable.value <= 0 ? this.$style.warning : '',
            variable.value === undefined ? this.$style.danger : '',
          ];

          return `<span class="${classes.join(' ')}">${name}<span>${formatted}</span></span>`;
        })

        // Replace certain mathematical characters
        .replace(/\*/g, '<b>&times;</b>');
    },
  }
};
</script>

<style lang="scss" module>
  @import '../../../sass/variables';

  .wrapper {
    margin-bottom: -1px;
    margin-top: -1px;
  }

  .label {
    background-color: $primary-light;
    color: $primary;
    border-radius: $border-radius;
    padding: 2px 3px 2px 5px;
    margin: 1px;
    font-weight: $font-weight-bold;
    line-height: 1;
    font-size: 90%;
    display: inline-flex;
    align-items: baseline;

    span {
      font-size: 10pt;
      display: inline-block;
      background-color: rgba(white, 0.75);
      padding: 2px 4px;
      margin: 1px 0 1px 4px;
      border-radius: ($border-radius / 2);
    }

    &.danger {
      color: white;
      background-color: $danger;

      span {
        color: $danger;
      }
    }

    &.warning {
      color: mix(black, $warning);
      background-color: mix(white, $warning);
    }

    &:first-child {
      margin-left: 0;
    }
  }
</style>
