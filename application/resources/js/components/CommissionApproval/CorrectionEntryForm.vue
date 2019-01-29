<template>
  <div class="shadow-sm bg-white accent-primary my-4">
    <div class="card-body">
      <h5 class="card-title">Sonderbuchung anlegen</h5>
      <form @submit.prevent="submit">
        <div class="my-1 row align-items-center">
          <div class="col-sm-2">
            <strong>Partner (ID):</strong>
          </div>
          <div class="col-sm-10">
            <input
              v-model.number="entry.userId"
              type="text"
              class="form-control form-control-sm mr-2"
              placeholder="Partner-ID"
            >
          </div>
        </div>

        <div class="my-1 row align-items-center">
          <div class="col-sm-2 pr-1">
            <strong>Betrag in EUR:</strong>
          </div>
          <div class="col-sm-10 d-flex">
            <input
              v-model.number="entry.amount"
              type="number"
              step="0.01"
              class="form-control form-control-sm mr-2"
              placeholder="Betrag in EUR"
            >
            <strong class="text-danger text-nowrap align-self-center">
              MwSt. ist partnerabhängig!
            </strong>
          </div>
        </div>

        <div class="my-1 row align-items-center">
          <div class="col-sm-2">
            <strong>Notiz/Titel:</strong>
          </div>
          <div class="col-sm-10">
            <input
              v-model="entry.note.public"
              class="form-control form-control-sm"
              placeholder="Steht auf der Rechnung"
            />
          </div>
        </div>

        <div class="my-1 row align-items-center">
          <div class="col-sm-2 pr-1">
            <strong>Notiz (Intern):</strong>
          </div>
          <div class="col-sm-10">
            <input
              v-model="entry.note.private"
              class="form-control form-control-sm"
              placeholder="Privat, nur für die Buchhaltung"
            />
          </div>
        </div>

        <div class="text-right mt-3">
          <button class="btn btn-primary">Sonderbuchung Anlegen</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    entry: {},
  }),

  created() {
    this.createNewEntry();
  },

  methods: {
    submit() {
      this.$emit('submit', this.entry);
      this.createNewEntry();
    },

    createNewEntry() {
      this.entry = {
        userId: null,
        amount: 0,
        note: {
          public: '',
          private: '',
        },
      };
    },
  },
};
</script>
