<template>
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">{{title}}</div>

      <div class="card-body">
        <form @submit.prevent="submit">
          <div class="form-group row">
            <label for="identification" class="col-md-4 col-form-label text-md-right">
              <span v-if="role === 'doctor'">Doctor Permit ID</span>
              <span v-else-if="role === 'nurse'">Nurse Access Code</span>
              <span v-else>Email Address</span>
            </label>

            <div class="col-md-6">
              <input
                v-if="role === 'patient'"
                id="identification"
                class="form-control"
                name="identification"
                v-model="fields.email"
                required
              >
              <input
                v-if="role === 'doctor'"
                id="identification"
                class="form-control"
                name="identification"
                v-model="fields.permit_id"
                required
              >
              <input
                v-if="role === 'nurse'"
                id="identification"
                class="form-control"
                name="identification"
                v-model="fields.access_id"
                required
              >
              <!-- TODO: change access_id with whatever user logs in with -->
              <div v-if="errors &&  errors.email" class="text-danger">{{ errors.email[0] }}</div>
              <div v-if="errors &&  errors.permit_id" class="text-danger">{{ errors.permit_id[0] }}</div>
              <div v-if="errors &&  errors.access_id" class="text-danger">{{ errors.access_id[0] }}</div>
              <!-- TODO: add error for user -->
            </div>
          </div>

          <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

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
  props: ["title", "role"],

  data() {
    return {
      fields: {},
      errors: {}
    };
  },
  methods: {
    submit() {
      this.errors = {};
      axios.post('./login', this.fields)
        .then(response => {
          window.location.href = location.origin + "/home";
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
