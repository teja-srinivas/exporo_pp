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
import clone from 'lodash/clone';

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
      projectsBackup: [],
      ignore: true,
    };
  },

  created: function () {
    this.projects = clone(this.selection);
    this.projectsBackup = clone(this.selection);
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
      if (!this.ignore) {
        axios.put(this.api, {
          projects: this.projects,
        }).then(() => {
          this.projectsBackup = clone(this.projects);
        }).catch((error) => {
          if (this.projectsBackup !== this.projects) {
            this.$notify({
              title: 'Fehler',
              text: error.response.data.message,
              type: 'error',
              duration: 5000,
            });
            this.ignore = true;
            this.projects = clone(this.projectsBackup);
          }
        });
      } else {
        this.ignore = false;
      }
    },
  },
};
</script>

<style lang="scss" module>
  
</style>
