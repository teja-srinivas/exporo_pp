<template>
  <div>
    <div
      v-for="(entry, idx) in entries"
      :key="idx"
      class="d-flex mb-2"
    >
      <div class="flex-fill mr-2 p-2 rounded shadow-sm border">
        <url-field
          v-model="entry.key"
          :errors="errors"
          :index="idx"
          :name="name"
          property="key"
          label="Titel"
        />

        <url-field
          v-model="entry.value"
          :errors="errors"
          :index="idx"
          :name="name"
          property="value"
          label="URL"
          class="mb-0"
        />
      </div>

      <button
        type="button"
        class="btn btn-outline-secondary btn-sm align-self-center"
        @click="remove(idx)"
      >
        <font-awesome-icon icon="trash" />
      </button>
    </div>

    <button
      type="button"
      class="btn btn-outline-primary btn-sm"
      @click="insert"
    >
      <font-awesome-icon icon="plus" />
      Eintrag hinzufügen
    </button>
  </div>
</template>

<script>
import UrlField from './field.vue';

const emptyArray = () => [];

export default {
  components: {
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

  data() {
    return {
      entries: this.values,
    };
  },

  methods: {
    remove(index) {
      this.entries.splice(index, 1);
    },

    insert() {
      this.entries.push({
        key: '',
        value: '',
      });
    },
  },
};
</script>
