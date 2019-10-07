<template>
  <div class="form-group row">
    <label
      :for="id"
      class="col-xl-1 col-sm-2 col-form-label"
    >
      {{ label }}:
    </label>

    <div class="col-xl-11 col-sm-10">
      <input
        v-model.lazy="model"
        :id="id"
        :name="`${name}[${index}][${property}]`"
        :class="{ 'is-invalid': error }"
        class="form-control"
        placeholder="Titel"
        type="text"
      >

      <span
        v-for="entry in error"
        :key="entry"
        class="invalid-feedback"
      >
        <strong v-text="entry.replace(errorKey, label)" />
      </span>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    name: {
      type: String,
      required: true,
    },

    index: {
      type: Number,
      required: true,
    },

    value: {
      required: true,
      validator: val => (val === null || typeof val === 'string'),
    },

    property: {
      type: String,
      required: true,
    },

    label: {
      type: String,
      required: true,
    },

    errors: {
      type: [Array, Object],
      default: () => {},
    },
  },

  computed: {
    id() {
      return `input_${this.name}_${this.index}_${this.property}`;
    },

    model: {
      get() {
        return this.value;
      },

      set(val) {
        this.$emit('input', val);
      },
    },

    errorKey() {
      return `${this.name}.${this.index}.${this.property}`;
    },

    error() {
      return this.errors[this.errorKey];
    },
  },
};
</script>
