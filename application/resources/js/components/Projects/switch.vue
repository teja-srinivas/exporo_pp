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

  data() {
    return {
      visibility: '',
      ignore: true,
    };
  },

  created: function () {
    this.visibility = this.project.in_iframe;
  },

  watch: {
    visibility: function (val) {
      this.updateProject(val);
    },
  },

  methods: {
    updateProject(visibility) {
      if (!this.ignore) {
        axios.put(this.api, {
          in_iframe: visibility,
        }).catch((error) => {
          if (visibility === true) {
            this.$notify({
              title: 'Fehler',
              text: error.response.data.message,
              type: 'error',
              duration: 5000,
            });
            this.ignore = true;
            this.visibility = this.project.in_iframe;
          }
        });
      } else {
        this.ignore = false;
      }
    },
  },
};
</script>
