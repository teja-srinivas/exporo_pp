<template>
  <div>
    <div
      class="rounded shadow-sm bg-white p-3 w-100 mr-2 mt-3 mb-3 accent-primary"
    >
      <div class="row">
        <div class="col-lg-4 mb-3 mb-lg-0 small">
          <h5 class="card-title">
            Allgemeine Informationen
          </h5>
          Allgemeine Informationen über die Kampagne.
        </div>
        <div class="col-lg-8">
          <div class="form-group row">
            <label for="inputTitle" class="col-xl-4 col-sm-5 col-form-label">Titel:</label>
            <div class="col-xl-8 col-sm-7 ">
              <input
                id="inputTitle"
                type="text"
                class="form-control"
                v-model="campaign.title"
              >
            </div>
          </div>
          <div class="form-group row">
            <label for="inputDescription" class="col-xl-4 col-sm-5 col-form-label">Beschreibung:</label>
            <div class="col-xl-8 col-sm-7 ">
              <textarea
                id="inputDescription"
                class="form-control"
                rows="5"
                v-model="campaign.description"
              ></textarea>
            </div>
          </div>
          <div class="form-group row">
            <label for="activeTrue" class="col-xl-4 col-sm-5 col-form-label">Aktiv:</label>
            <div class="col-xl-8 col-sm-7 col-form-label">
              <div class="custom-control custom-control-inline custom-radio">
                <input
                  type="radio"
                  id="activeTrue"
                  class="custom-control-input"
                  value="1"
                  v-model="campaign.is_active"
                >
                <label
                  for="activeTrue"
                  class="custom-control-label"
                >
                  Ja
                </label>
              </div>
              <div class="custom-control custom-control-inline custom-radio">
                <input
                  type="radio"
                  id="activeFalse"
                  class="custom-control-input"
                  value="0"
                  v-model="campaign.is_active"
                >
                <label 
                  for="activeFalse"
                  class="custom-control-label"
                >
                  Nein
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      class="rounded shadow-sm bg-white p-3 w-100 mr-2 mt-3 mb-3 accent-primary"
    >
      <div class="row">
        <div class="col-lg-4 mb-3 mb-lg-0 small">
          <h5 class="card-title">
            Laufzeit
          </h5>
          Optionale Angabe der Laufzeit.
        </div>
        <div class="col-lg-8">
          <div class="form-group row">
            <label for="inputStart" class="col-xl-4 col-sm-5 col-form-label">Beginn:</label>
            <div class="col-xl-8 col-sm-7 ">
              <flat-pickr
                id="inputStart"
                v-model="campaign.started_at"
                class="form-control"
              />
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEnd" class="col-xl-4 col-sm-5 col-form-label">Ende:</label>
            <div class="col-xl-8 col-sm-7 ">
              <flat-pickr
                id="inputEnd"
                v-model="campaign.ended_at"
                class="form-control"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      class="rounded shadow-sm bg-white p-3 w-100 mr-2 mt-3 mb-3 accent-primary"
    >
      <div class="row">
        <div class="col-lg-4 mb-3 mb-lg-0 small">
          <h5 class="card-title">
            Uploads
          </h5>
          Upload von Bild und Dokument.<br>
          Bild-Format: {{ imageDimensions.width }} px x {{ imageDimensions.height }} px
        </div>
        <div class="col-lg-8">
          <div class="form-group row">
            <label for="inputDocument" class="col-xl-4 col-sm-5 col-form-label">Dokument:</label>
            <div class="col-xl-8 col-sm-7 ">
              <input
                type="file"
                id="inputDocument"
                @change="onDocumentUpload"
              />
              <div
                class="mt-2"
                v-if="campaign.document_url && showDocument"
              >
                {{ campaign.document_name }}
                <button
                  class="close"
                  @click="deleteFile('document')"
                >
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputImage" class="col-xl-4 col-sm-5 col-form-label">Bild:</label>
            <div class="col-xl-8 col-sm-7 ">
              <input
                type="file"
                id="inputImage"
                @change="onImageUpload"
              />
              <div
                class="mt-2"
                v-if="campaign.image_url && showImage"
              >
                {{ campaign.image_name }}
                <button
                  class="close"
                  @click="deleteFile('image')"
                >
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div
                class="text-warning"
                v-if="imageError"
              >
                {{ imageError }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="imagePreview">
      <img
        :src="imagePreview.src"
        class="img-preview"
      />
    </div>
    <div v-if="!imagePreview && campaign.image_url && showImage">
      <img
        :src="campaign.image_url"
        class="img-preview"
      />
    </div>

    <div
      class="rounded shadow-sm bg-white p-3 w-100 mr-2 mt-3 mb-3 accent-primary"
    >
      <div class="row">
        <div class="col-lg-4 mb-3 mb-lg-0 small">
          <h5 class="card-title">
            Benutzer
          </h5>
          Auswahl der Nutzer, für die diese Kampagne angezeigt werden soll.
        </div>
        <div class="col-lg-8">
          <div class="form-group row">
            <label for="allUsersTrue" class="col-xl-4 col-sm-5 col-form-label">Alle:</label>
            <div class="col-xl-8 col-sm-7 col-form-label">
              <div class="custom-control custom-control-inline custom-radio">
                <input
                  type="radio"
                  id="allUsersTrue"
                  class="custom-control-input"
                  value="1"
                  v-model="campaign.all_users"
                >
                <label
                  for="allUsersTrue"
                  class="custom-control-label"
                >
                  Ja
                </label>
              </div>
              <div class="custom-control custom-control-inline custom-radio">
                <input
                  type="radio"
                  id="allUsersFalse"
                  class="custom-control-input"
                  value="0"
                  v-model="campaign.all_users"
                >
                <label 
                  for="allUsersFalse"
                  class="custom-control-label"
                >
                  Nein
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div>
      <div class="text-right">
        <button
          class="btn btn-primary"
          @click="submitForm"
        >
          Speichern
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import FlatPickr from 'vue-flatpickr-component';
import { German } from 'flatpickr/dist/l10n/de';
import 'flatpickr/dist/flatpickr.css';
import forEach from 'lodash/forEach';

export default {
  components: {
    'flat-pickr': FlatPickr,
  },

  props: {
    api: {
      type: String|Object,
      required: true,
    },
    action: {
      type: String,
      required: true,
    },
    redirect: {
      type: String,
      required: true,
    },
    editedCampaign: {
      type: Object,
      required: false,
    },
  },

  data() {
    return {
      defaultCampaign: {
        title: 'test',
        description: 'test123',
        is_active: 0,
        all_users: 1,
      },
      document: null,
      imagePreview: null,
      imageDimensions: {
        height: 300,
        width: 987,
      },
      imageError: null,
      image: null,
      document: null,
      showImage: true,
      showDocument: true,
      
      test: '123456',
    };
  },

  computed: {
    campaign() {
      return this.editedCampaign || this.defaultCampaign;
    },
  },

  methods: {
    onDocumentUpload(e) {
      let files = e.target.files || e.dataTransfer.files;

      if (!files.length) {
        return;
      }

      this.document = files[0];
    },

    onImageUpload(e) {
      let files = e.target.files || e.dataTransfer.files;

      if (!files.length) {
        return;
      }

      this.image = files[0];
      this.createPreview(files[0]);
    },

    createPreview(file) {
      let reader = new FileReader();
      let _this = this;
      let img = new Image();
      img.name = file.name;

      img.onload = () => {
        this.validateImage({
          height: img.height,
          width: img.width,
        });
      }

      reader.onload = (e) => {
        img.src = e.target.result;
        _this.imagePreview = img;
        this.showImage = true;
      };
      reader.readAsDataURL(file);
    },

    validateImage(dimensions) {
      if (dimensions.height !== this.imageDimensions.height || dimensions.height !== this.imageDimensions.height) {
        this.imageError = `Warnung: Die Abmessungen des hochgeladenen Bildes weichen von den empfohlenen Abmessungen ab:
          Höhe: ${dimensions.height} px, Breite ${dimensions.width} px.`;
      } else {
        this.imageError = null;
      }
    },

    submitForm() {
      let formData = new FormData();
      const redirect = this.redirect;

      if (this.document !== null) {
        formData.append('document', this.document);
      }

      if (this.image !== null) {
        formData.append('image', this.image);
      }

      forEach(this.campaign, function(value, key) {
        formData.append(key, value);
      });

      if (this.action === 'create') {
        axios.post(this.api, formData).then(
          () => { 
            window.location.href = redirect;
        }).catch(() => {
          //
        });
      } else if (this.action === 'create') {
        axios.put(this.api.put, formData).then(
          () => { 
            window.location.href = redirect;
        }).catch(() => {
          //
        });
      }
    },

    deleteFile(type) {
      axios.post(this.api.delete_file, {
        type: type,
        campaign: this.campaign.id,
      }).then(
        () => {
          if (type === 'image') {
            this.showImage = false;
            this.campaign.image_url = null;
          } else if (type === 'document') {
            this.showDocument = false;
            this.campaign.document_url = null;
          }
      }).catch(() => {
        //
      });
    },
  },
};
</script>

<style lang="scss">
  .img-preview {
        width: 100%;
        height: 300px;
        object-fit: cover;
    }
</style>
