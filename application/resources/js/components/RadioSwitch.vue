<template>
  <div
    class="radio-switch"
    :class="{'active': value === right}"
    @click.stop="$emit('input', value === left ? right : left)"
  >
    <div class="box" />
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: [String],
      required: true,
    },
    left: {
      type: String,
      required: true,
    },
    right: {
      type: String,
      required: true,
    },
  },
};
</script>

<style lang="scss">
  .radio-switch {
    display: inline-block;
    vertical-align: middle;
    cursor: pointer;
    background-color: #e8e8e8;
    box-shadow: inset 0 0 2px rgba(black, 0.25);
    padding: 2px;
    border-radius: 5rem;
    width: 25px;

    > .box {
      height: 10px;
      width: 10px;
      background-color: #1e73be;
      border-radius: 5rem;
      transition: margin-left 50ms ease;
    }

    &.active .box {
      margin-left: 10px;
    }
  }
</style>
