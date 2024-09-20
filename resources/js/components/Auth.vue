<script setup>
import { ref } from "vue";
import axios from "axios";

const props = defineProps({
  show: Boolean,
  onClose: Function,
});
const emit = defineEmits();

const isLogin = ref(true);
const name = ref("");
const email = ref("");
const password = ref("");
const password_confirmation = ref("");
const valid = ref(false);
const errorMessage = ref("");
const successMessage = ref("");
const snackbar = ref(false);

const toggleMode = () => {
  isLogin.value = !isLogin.value;
  name.value = "";
  email.value = "";
  password.value = "";
  password_confirmation.value = "";
  errorMessage.value = "";
};

const handleSubmit = async () => {
  const endpoint = isLogin.value ? "/api/login" : "/api/register";
  const payload = {
    email: email.value,
    password: password.value,
  };

  if (!isLogin.value) {
    payload.name = name.value;
    payload.password_confirmation = password_confirmation.value;
  }

  try {
    const response = await axios.post(endpoint, payload);
    if (response.data.status) {
      localStorage.setItem("token", response.data.data.token);
      emit("auth-success", response.data);
      successMessage.value = isLogin.value ? "Successfully logged in!" : "Successfully registered!";
      snackbar.value = true;

      emit("auth-complete");

      if (typeof props.onClose === 'function') {
        props.onClose();
      }
    } else {
      errorMessage.value = response.data.message;
    }
  } catch (error) {
    if (error.response) {
      errorMessage.value = error.response.data.message || "An error occurred";
    } else {
      errorMessage.value = "Network error. Please try again.";
    }
  }
};

</script>


<style scoped>
.error {
  color: red;
}
.actions {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}
</style>

<template>
  <v-dialog v-model="props.show" max-width="500px">
    <v-card>
      <v-card-title>
        <span>{{ isLogin ? "Login" : "Register" }}</span>
      </v-card-title>
      <v-card-text>
        <v-form ref="form" v-model="valid" @submit.prevent="handleSubmit">
          <v-text-field
            v-if="!isLogin"
            v-model="name"
            label="Name"
            :rules="[(v) => !!v || 'Name is required']"
            required
          ></v-text-field>
          <v-text-field
            v-model="email"
            label="Email"
            :rules="[(v) => !!v || 'Email is required']"
            required
          ></v-text-field>
          <v-text-field
            v-model="password"
            label="Password"
            type="password"
            :rules="[(v) => !!v || 'Password is required']"
            required
          ></v-text-field>
          <v-text-field
            v-if="!isLogin"
            v-model="password_confirmation"
            label="Confirm Password"
            type="password"
            :rules="[
              (v) => !!v || 'Confirm Password is required',
              (v) => v === password || 'Passwords must match',
            ]"
            required
          ></v-text-field>
          <div v-if="errorMessage" class="error">{{ errorMessage }}</div>
        </v-form>
      </v-card-text>
      <v-card-actions class="actions">
        <v-btn color="primary" @click="toggleMode">
          {{ isLogin ? "Don't have an account? Register" : "Already have an account? Login" }}
        </v-btn>
        <v-btn color="secondary" @click="handleSubmit">
          {{ isLogin ? "Login" : "Register" }}
        </v-btn>
      </v-card-actions>
    </v-card>
    <v-snackbar v-model="snackbar" timeout="3000">
      {{ successMessage }}
      <template #action="{ attrs }">
        <v-btn text v-bind="attrs" @click="snackbar = false">Close</v-btn>
      </template>
    </v-snackbar>
  </v-dialog>
</template>
