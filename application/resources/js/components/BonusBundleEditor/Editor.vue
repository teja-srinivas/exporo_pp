<template>
  <b-popover
    v-if="item !== null"
    :show="true"
    :target="target"
    placement="bottomleft"
    triggers=""
  >
    <b-form-group label="Typ" horizontal label-cols="2">
      <select v-model="item.type" class="custom-select">
        <option v-for="(name, id) in commissionTypes" :value="id" v-text="name" />
      </select>
    </b-form-group>

    <b-form-group label="Für" horizontal label-cols="2">
      <div class="d-flex align-items-center">
        <select v-model="item.calculationType" class="custom-select mr-3 pr-4">
          <option v-for="(name, id) in calculationTypes" :value="id" v-text="name" />
        </select>

        <b-form-checkbox v-model="item.overhead" class="mr-0">
          Overhead
        </b-form-checkbox>
      </div>
    </b-form-group>

    <b-form-group label="Wert" horizontal label-cols="2" class="mb-2">
      <div class="input-group mr-3">
        <input
          type="number"
          step="0.01"
          class="form-control text-right"
          v-model.number="item.value"
        >
        <div class="input-group-append">
          <span class="input-group-text">
            <b-form-radio-group v-model="item.isPercentage" class="d-flex flex-nowrap">
              <b-form-radio :value="true">%</b-form-radio>
              <b-form-radio :value="false" class="mr-0">EUR</b-form-radio>
            </b-form-radio-group>
          </span>
        </div>
      </div>
    </b-form-group>

    <div class="d-flex justify-content-between pt-2 border-top">
      <button
        class="btn btn-sm btn-outline-secondary"
        type="button"
        @click="close"
      >
        Abbrechen
      </button>

      <button
        :disabled="!item.calculationType || !item.type"
        class="btn btn-sm btn-primary"
        type="button"
        @click="updateAndClose"
      >
        Speichern
      </button>
    </div>
  </b-popover>
</template>

<script>
export default {
  props: {
    value: {
      type: Object,
    },

    target: {
      type: [String, Object, HTMLElement, Function],
    },

    commissionTypes: {
      type: Object,
      required: true,
    },

    calculationTypes: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      item: this.value !== null ? {...this.value} : null,
    };
  },

  methods: {
    close() {
      this.$emit('close');
    },

    updateAndClose() {
      this.$emit('input', this.item);
      this.close();
    },
  },

  watch: {
    value(val) {
      this.item = val !== null ? {...val} : null;
    },
  },
};
</script>
