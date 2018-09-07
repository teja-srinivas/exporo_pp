<template>
  <div
    :is="element"
    @click="updateFilter"
    :class="$style.cursor"
  >
    <div class="d-flex justify-content-between align-items-center">
      <slot />

      <font-awesome-icon
        :icon="isActive ? icons[this.value.order] : 'sort'"
        :class="{[$style.icon]: !isActive }"
        fixed-width
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

      this.$emit('input', {
        name: this.name,
        order: !this.isActive ? list[1] : list[(list.indexOf(this.value.order) + 1) % list.length],
      });
    },
  },
};
</script>

<style lang="scss" module>
  .cursor {
    cursor: ns-resize;
  }

  .icon {
    color: #bbb;
  }
</style>
