<template>
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">{{title}}</div>

      <div class="card-body">
        <form @submit.prevent="submit">
          <div class="form-group row">
            <label for="identification" class="col-md-4 col-form-label text-md-right">{{id}}</label>

            <div class="col-md-6">
              <input
                id="identification"
                class="form-control"
                name="identification"
                v-model="fields.permit_id"
                required
                autofocus
              >
              <div v-if="errors &&  errors.permit_id" class="text-danger">{{ errors.permit_id[0] }}</div>
            </div>
          </div>

          <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label text-md-right">password</label>

            <div class="col-md-6">
              <input
                id="password"
                type="password"
                class="form-control"
                name="password"
                v-model="fields.password"
                required
              >
              <div v-if="errors &&  errors.password" class="text-danger">{{ errors.password[0] }}</div>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-md-6 offset-md-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">remember me</label>
              </div>
            </div>
          </div>

          <div class="form-group row mb-0">
            <div class="col-md-8 offset-md-4">
              <button type="submit" class="btn btn-primary">Login</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["title", "id"],
  mounted() {
    console.log("Component mounted.");
    console.log(this.$route);
  },

  data() {
    return {
      fields: {},
      errors: {}
    };
  },
  methods: {
    submit() {
      this.errors = {};
      axios
        .post("./doctor/login", this.fields)
        .then(response => {
          alert("Message sent!");
        })
        .catch(error => {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors || {};
          }
        });
    }
  }
};
</script>
