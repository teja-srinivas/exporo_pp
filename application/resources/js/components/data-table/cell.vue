<template functional>
  <td
    :width="props.column.width"
    :class="props.column.formatter ? {
      'text-right': props.column.formatter.align === 'right',
      'text-center': props.column.formatter.align === 'center',
    } : []"
    class="text-nowrap text-truncate"
    v-html="props.value"
  />
</template>

<script>
export default {
  props: {
    column: {
      type: Object,
      required: true,
    },

    value: {
      type: String,
      required: true,
    },
  },
};
</script>
