<template>
  <div>
    <data-table
      v-bind="tableData"
      v-model="projects"
    ></data-table>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    tableData: {
      type: Object,
      required: true,
    },

    selection: {
      type: Array,
      default: () => [],
    },

    api: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      projects: [],
    };
  },

  created: function () {
    this.projects = this.selection;
  },

  computed: {
    
  },

  watch: {
    projects: function () {
      this.updateProjects();
    },
  },

  methods: {
    updateProjects() {
      axios.put(this.api, {
        projects: this.projects,
      });
    },
  },
};
</script>

<style lang="scss" module>
  
</style>
