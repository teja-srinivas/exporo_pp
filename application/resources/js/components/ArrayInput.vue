<template>
  <div>
    <div
      v-for="(entry, idx) in entries"
      :key="entry"
      class="d-flex mb-2"
    >
      <div
        class="flex-fill mr-2"
        v-html="innerHTML"
      />

      <button
        v-if="idx < entries.length - 1"
        type="button"
        class="btn btn-outline-secondary btn-sm"
        @click="remove(idx)"
      >
        <font-awesome-icon icon="trash" />
      </button>

      <button
        v-else
        type="button"
        class="btn btn-outline-primary btn-sm"
        @click="insert"
      >
        <font-awesome-icon icon="plus" />
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    innerHTML: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      entries: [0],
      counter: 1,
    };
  },

  methods: {
    remove(index) {
      this.entries.splice(index, 1);
    },

    insert() {
      this.entries.push(this.counter++);
    },
  },
};
</script>
