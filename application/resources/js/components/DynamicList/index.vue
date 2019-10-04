<template>
  <div>
    <div
      v-for="(entry, idx) in entries"
      :key="idx"
      class="d-flex mb-2"
    >
      <div class="flex-fill mr-2 p-2 rounded shadow-sm border">
        <slot
          :entry="entry"
          :index="idx"
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
const emptyArray = () => [];

export default {
  props: {
    values: {
      type: Array,
      default: emptyArray,
    },

    modelFactory: {
      type: Function,
      required: true,
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
      this.entries.push(this.modelFactory());
    },
  },
};
</script>
