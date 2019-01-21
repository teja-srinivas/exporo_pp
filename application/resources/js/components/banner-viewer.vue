<template>
  <div v-if="sets.length > 0" class="rounded shadow-sm bg-white p-3">
    <div class="form-group d-flex font-weight-bold">
      <div
        class="mr-3 align-self-start align-self-lg-center mt-2 mt-lg-0"
        :class="$style.circle"
      >1</div>

      <div class="row align-items-center flex-fill">
        <div class="col-lg-6 col-form-label">
          Wählen Sie ein passendes Bannerset:
        </div>

        <div class="col-lg-6">
          <select
            v-model="currentSet"
            class="custom-select"
          >
            <option
              v-for="(set, idx) in sets"
              :key="idx"
              :value="idx"
              v-text="set.name"
            />
          </select>
        </div>
      </div>
    </div>

    <hr class="mb-2 mb-lg-3">

    <div class="form-group d-flex font-weight-bold">
      <div
        class="mr-3 align-self-start align-self-lg-center mt-2 mt-lg-0"
        :class="$style.circle"
      >2</div>

      <div class="row align-items-center flex-fill">
        <div class="col-lg-6 col-form-label">
          Legen Sie fest, auf welche Seite verlinkt werden soll:
        </div>

        <div class="col-lg-6">
          <select
            v-model="currentUrl"
            class="custom-select"
          >
            <option
              v-for="(url, idx) in sets[currentSet].urls"
              :key="idx"
              :value="idx"
              v-text="url.key"
            />
          </select>
        </div>
      </div>
    </div>

    <hr class="mb-2 mb-lg-3">

    <div class="form-group d-flex font-weight-bold mb-0">
      <div
        class="mr-3"
        :class="$style.circle"
      >3</div>

      <div class="align-items-center flex-fill">
        <p class="pt-1">
          Im Code-Snippet für die jeweilige Bannergröße ist Ihr persönlicher Partner-Link direkt hinterlegt.
        </p>

        <table class="table-striped table m-0">
          <tbody>
            <tr v-for="banner in sets[currentSet].banners">
              <td>
                {{ banner.width }}x{{ banner.height }}
              </td>
              <td>
                <img :src="banner.url" class="img-fluid mb-1">

                <div class="d-flex">
                  <input
                    :value="bannerSnippet(banner)"
                    class="small overflow-auto form-control form-control-sm border-0 shadow-none"
                    type="text"
                    readonly
                  >

                  <button
                    type="button"
                    class="btn btn-primary btn-sm ml-1"
                    @click="copy(bannerSnippet(banner))"
                  >
                    <font-awesome-icon icon="paste" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div v-else class="lead text-center text-muted">
    Keine Banner zur Auswahl
  </div>
</template>

<script>
import { writeText } from 'clipboard-polyfill';

export default {
  props: {
    sets: {
      type: Array,
      required: true,
    },
  },

  data() {
    return {
      selection: {
        set: 0,
        url: 0,
      },
    };
  },

  computed: {
    currentSet: {
      get() {
        return this.selection.set;
      },

      set(index) {
        this.selection.set = Math.max(0, Math.min(this.sets.length - 1, index));
        this.currentUrl = 0;
      },
    },

    currentUrl: {
      get() {
        return this.selection.url;
      },

      set(index) {
        this.selection.url = Math.max(0, Math.min(this.sets[this.currentSet].urls.length - 1, index));
      },
    },
  },

  methods: {
    bannerSnippet(banner) {
      return `<a href="${this.sets[this.currentSet].urls[this.currentUrl].value}" target="_blank"><img src="${banner.url}"></a>`;
    },

    copy(text) {
      writeText(text);
      this.$notify('Text wurde in die Zwischenablage kopiert');
    },
  },
};
</script>

<style lang="scss" module>
  @import "../../sass/variables";

  .circle {
    $size: 29px;
    min-width: $size;
    min-height: $size;
    width: $size;
    height: $size;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    background-color: rgba($primary-light, 0.5);
    color: rgba($primary-dark, 0.75);
    border-radius: 5px;

    font-family: $headings-font-family;
  }
</style>
