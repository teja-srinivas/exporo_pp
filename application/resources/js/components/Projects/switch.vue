<template>
  <div>
    <div  class="custom-control custom-switch">
      <input
        type="checkbox"
        class="custom-control-input"
        id="toggle"
        v-model="visibility"
      >
      <label class="custom-control-label" for="toggle">
        In Iframe anzeigen
      </label>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    project: {
      type: Object,
      required: true,
    },

    api: {
      type: String,
      required: true,
    },
  },

  computed: {
    visibility: {
      get () { return this.project.in_iframe },
      set (value) { this.updateProject(value) },
    },
  },

  methods: {
    updateProject(visibility) {
      axios.put(this.api, {
        in_iframe: visibility,
      });
    },
  },
};
</script>

<style lang="scss" module>
  
</style>
