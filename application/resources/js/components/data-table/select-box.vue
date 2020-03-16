<template>
  <div
    class="leading-sm py-0 align-middle"
    width="32"
    :is="element"
    v-bind="$attrs"
  >
    <div class="d-flex align-items-center">
      <div class="custom-control custom-checkbox custom-control-inline m-0">
        <input
          type="checkbox"
          :id="id"
          class="custom-control-input"
          :indeterminate.prop="count > 0 && count < size"
          :checked="checked"
          v-on="$listeners"
        >
        <label class="custom-control-label" :for="id" />
      </div>
    </div>
  </div>
</template>

<script>
let uuid = 1;

export default {
  props: {
    element: {
      type: String,
      default: 'td',
    },

    size: {
      type: Number,
      required: true,
    },

    count: {
      type: Number,
      required: true,
    },

    selection: {
      type: Array,
      default: () => [],
    },

    row: {
      type: Object,
      default: () => ({
          user: {},
      }),
    },
  },

  inheritAttrs: true,

  data: () => ({
    id: `dt-select-${uuid++}`,
  }),

  computed: {
    checked() {
      return this.count === this.size ||
        (this.row.user && this.selection.includes(this.row.user.id)) ||
        this.selection.includes(this.row.id);
    }
  },
};
</script>
