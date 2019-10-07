<template>
  <div>
    <div
      v-for="(entry, idx) in entries"
      :key="idx"
      class="d-flex mb-2"
    >
      <div class="flex-fill mr-2 p-2 rounded shadow-sm border">
        <text-field
          v-model="entry.placeholder"
          :errors="errors"
          :index="idx"
          :name="name"
          property="placeholder"
          label="Variable"
        />

        <select-field
          v-model="entry.type"
          :errors="errors"
          :index="idx"
          :name="name"
          property="type"
          label="Typ"
          :options="options"
        />

        <text-field
          v-if="showField(entry, 'url')"
          v-model="entry.url"
          :errors="errors"
          :index="idx"
          :name="name"
          property="url"
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
import TextField from '../fields/text.vue';
import SelectField from '../fields/select.vue';

const emptyArray = () => [];

const variableTypes = {
  link: {
    label: 'Link',
    fields: [ 'url' ]
  }
};

export default {
  components: {
    TextField,
    SelectField,
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

  computed: {
    options: function() {
      return variableTypes;
    },
  },

  methods: {
    showField(entry, fieldName) {
      const currentType = variableTypes[entry.type];
      if( !currentType ) return false;
      return currentType.fields.includes(fieldName);
    },

    remove(index) {
      this.entries.splice(index, 1);
    },

    insert() {
      this.entries.push({
        placeholder: '',
        url: '',
        type: 'link',
      });
    },
  },
};
</script>
