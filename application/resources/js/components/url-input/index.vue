<template>
  <DynamicList
    :values="values"
    :model-factory="() => ({ key: '', value: '' })"
    v-slot="{entry, index}"
  >
    <input
      v-if="entry.id"
      type="hidden"
      :name="`${name}[${index}][id]`"
      :value="entry.id"
    />

    <UrlField
      v-model="entry.key"
      :errors="errors"
      :index="index"
      :name="name"
      property="key"
      label="Titel"
    />

    <UrlField
      v-model="entry.value"
      :errors="errors"
      :index="index"
      :name="name"
      property="value"
      label="URL"
      class="mb-0"
    />
  </DynamicList>
</template>

<script>
import DynamicList from '../DynamicList';
import UrlField from './field.vue';

const emptyArray = () => [];

export default {
  components: {
    DynamicList,
    UrlField,
  },

  props: {
    values: {
      type: Array,
      default: emptyArray,
    },

    errors: {
      type: [Array, Object],
      default: emptyArray,
    },

    name: {
      type: String,
      default: 'urls',
    },
  },
};
</script>
