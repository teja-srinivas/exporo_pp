<template>
  <div
    :is="element"
    @click="updateFilter"
    :class="$style.cursor"
    v-bind="$attrs"
  >
    <div class="d-flex align-items-center">
      <div class="flex-fill">
        <slot />
      </div>

      <slot name="action" />

      <font-awesome-icon
        :icon="isActive ? icons[this.value.order] : 'sort'"
        :class="{[$style.icon]: !isActive }"
      />
    </div>

    <slot name="below" />
  </div>
</template>

<script>
export default {
  props: {
    element: {
      type: String,
      default: 'div',
    },
    value: {
      type: Object,
      required: true,
    },
    name: {
      type: String,
      required: true,
    },
  },

  inheritAttrs: true,

  computed: {
    isActive() {
      return this.value.name === this.name && this.value.order !== '';
    },
    icons() {
      return {
        '': 'sort',
        'asc': 'sort-up',
        'desc': 'sort-down',
      };
    },
  },

  methods: {
    updateFilter() {
      const list = ['', 'desc', 'asc'];
      const order = !this.isActive ? list[1] : list[(list.indexOf(this.value.order) + 1) % list.length];

      this.$emit('input', {
        name: order.length > 0 ? this.name : '',
        order,
      });
    },
  },
};
</script>

<style lang="scss" module>
  @import '../../sass/variables';

  .cursor {
    cursor: ns-resize;
  }

  .icon {
    color: rgba($black, 0.2);
  }
</style>
