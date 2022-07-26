<template>
  <div v-if="links.length > 0" class="rounded shadow-sm bg-white p-3">
    <div class="form-group d-flex font-weight-bold">
      <div
        class="mr-3 align-self-start align-self-lg-center mt-2 mt-lg-0"
        :class="$style.circle"
      >1</div>

      <div class="row align-items-center flex-fill">
        <div class="col-lg-6 col-form-label">
          Wählen Sie ein passendes Iframe-Set:
        </div>

        <div class="col-lg-6">
          <select
            v-model="currentSet"
            class="custom-select"
            @change="updateLink()"
          >
            <option
              v-for="set in sets"
              :value="set.value"
              v-text="set.title"
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
              v-for="link in linksForType"
              :value="link.url"
              v-text="link.title"
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
          Im Code-Snippet für die jeweilige Iframe-Größe ist Ihr persönlicher Partner-Link direkt hinterlegt.
        </p>

        <div class="mb-5" v-for="banner in banners">
          <div class="d-flex">
            {{ banner.width }}x{{ banner.height }}
          </div>

          <div class="d-flex">
            <iframe
              :src="bannerSrc(banner)"
              :width="banner.width"
              :height="banner.height"
              frameborder="0"
              style="border:0;"
              allowfullscreen=""
              class="rounded shadow-sm"
            ></iframe>
          </div>

          <div class="d-flex w-75 mt-2">
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
              <font-awesome-icon icon="paste"/>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div v-else class="lead text-center text-muted">
    Keine Embeds zur Auswahl
  </div>
</template>

<script>
import { writeText } from 'clipboard-polyfill';
import filter from 'lodash/filter';

export default {
  props: {
    links: {
      type: Array,
      required: true,
    },
  },

  data() {
    return {
      sets: [
        {
          title: 'Exporo Bestand Projekte',
          value: 'equity',
        },
        {
          title: 'Exporo Finanzierung Projekte',
          value: 'finance',
        },
        {
          title: 'Alle Projekte',
          value: null,
        },
      ],
      banners: [
        {
          height: 530,
          width: 770,
        },
        {
          height: 530,
          width: 345,
        },
      ],
      currentSet: null,
      currentUrl: this.links[0].url,
    };
  },

  computed: {
    linksForType() {
      let set = this.currentSet;
      return filter(this.links, function(link) {
        return link.type === set || link.type === null;
      });
    },
  },

  methods: {
    bannerSnippet(banner) {
      return `<iframe src="${this.bannerSrc(banner)}" width="${banner.width}" height="${banner.height}" frameborder="0" style="border:0;" allowfullscreen=""></iframe>`;
    },

    bannerSrc(banner) {
      const query = [
        `height=${banner.height}`,
        `width=${banner.width}`,
      ];

      if (this.currentSet !== null) {
          query.push(`type=${this.currentSet}`);
      }

      query.push(`link=${this.currentUrl}`);

      return `${window.location.protocol}//${window.location.hostname}/affiliate/iframe?${query.join('&')}`;
    },

    copy(text) {
      writeText(text);
      this.$notify('Text wurde in die Zwischenablage kopiert');
    },

    updateLink() {
      this.currentUrl = this.linksForType[0].url;
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
