<template>
  <data-table v-bind="$props" with-details>
    <template v-slot="{ row }">
      {{ row.bonuses }}
    </template>
  </data-table>
</template>

<script>
export default {
  props: ['columns', 'rows'],
};
</script>
