<template functional>
  <div
    class="leading-sm py-0 align-middle"
    width="32"
    :is="props.element"
  >
    <div class="d-flex align-items-center">
      <b-form-checkbox class="m-0" />
    </div>
  </div>
</template>

<script>
export default {
  props: {
    element: {
      type: String,
      default: 'td',
    },
  },
};
</script>
