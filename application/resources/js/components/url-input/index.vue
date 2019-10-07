<template>
  <DynamicList
    :values="values"
    :model-factory="() => ({ title: '', url: '' })"
    v-slot="{entry, index}"
  >
    <input
      v-if="entry.id"
      type="hidden"
      :name="`${name}[${index}][id]`"
      :value="entry.id"
    />

    <UrlField
      v-model="entry.title"
      :errors="errors"
      :index="index"
      :name="name"
      property="title"
      label="Titel"
    />

    <UrlField
      v-model="entry.url"
      :errors="errors"
      :index="index"
      :name="name"
      property="url"
      label="URL"
      class="mb-0"
    />
  </DynamicList>
</template>

<script>
import DynamicList from '../DynamicList';
import UrlField from '../fields/text.vue';

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
