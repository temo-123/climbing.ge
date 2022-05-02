<template>
  <div class="login mt-5">
    <div class="card">
      <div class="card-header">
        Register
      </div>
      <div class="card-body">
        <form>
          <div class="form-group">
            <label for="email">Name</label>
            <input
              type="text"
              class="form-control"
              :class="{ 'is-invalid': errors.name }"
              id="name"
              v-model="details.name"
              placeholder="Enter name"
            />
            <div class="invalid-feedback" v-if="errors.name">
              {{ errors.name[0] }}
            </div>
          </div>
          <div class="form-group">
            <label for="email">surname</label>
            <input
              type="text"
              class="form-control"
              :class="{ 'is-invalid': errors.surname }"
              id="surname"
              v-model="details.surname"
              placeholder="Enter surname"
            />
            <div class="invalid-feedback" v-if="errors.surname">
              {{ errors.surname[0] }}
            </div>
          </div>
          <div class="form-group">
            <label for="email">Email address</label>
            <input
              type="email"
              class="form-control"
              :class="{ 'is-invalid': errors.email }"
              id="email"
              v-model="details.email"
              placeholder="Enter email"
            />
            <div class="invalid-feedback" v-if="errors.email">
              {{ errors.email[0] }}
            </div>
          </div>
          <div class="form-group">
            <label for="country">country</label>
            <input
              type="country"
              class="form-control"
              :class="{ 'is-invalid': errors.country }"
              id="country"
              v-model="details.country"
              placeholder="Enter country"
            />
            <div class="invalid-feedback" v-if="errors.country">
              {{ errors.country[0] }}
            </div>
          </div>
          <div class="form-group">
            <label for="city">city</label>
            <input
              type="city"
              class="form-control"
              :class="{ 'is-invalid': errors.city }"
              id="city"
              v-model="details.city"
              placeholder="Enter city"
            />
            <div class="invalid-feedback" v-if="errors.city">
              {{ errors.city[0] }}
            </div>
          </div>
          <div class="form-group">
            <label for="phone_number">phone_number</label>
            <input
              type="phone_number"
              class="form-control"
              :class="{ 'is-invalid': errors.phone_number }"
              id="phone_number"
              v-model="details.phone_number"
              placeholder="Enter phone_number"
            />
            <div class="invalid-feedback" v-if="errors.phone_number">
              {{ errors.phone_number[0] }}
            </div>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input
              type="password"
              class="form-control"
              :class="{ 'is-invalid': errors.password }"
              id="password"
              v-model="details.password"
              placeholder="Password"
            />
            <div class="invalid-feedback" v-if="errors.password">
              {{ errors.password[0] }}
            </div>
          </div>
          <div class="form-group">
            <label for="password_confirmation">Confirm password</label>
            <input
              type="password"
              class="form-control"
              id="password_confirmation"
              v-model="details.password_confirmation"
              placeholder="Confirm password"
            />
          </div>
          <button type="button" @click="register" class="btn btn-primary">
            Register
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
export default {
  name: "Home",
  data: function() {
    return {
      details: {
        name: null,
        surname: null,
        email: null,
        phone_number: null,
        country: null,
        city: null,
        password: null,
        password_confirmation: null
      }
    };
  },
  computed: {
    ...mapGetters(["errors"])
  },
  mounted() {
    this.$store.commit("setErrors", {});
  },
  methods: {
    ...mapActions("auth", ["sendRegisterRequest"]),
    register: function() {
      this.sendRegisterRequest(this.details).then(() => {
        this.$router.push({ path: "/" });
      });
    }
  }
};
</script>