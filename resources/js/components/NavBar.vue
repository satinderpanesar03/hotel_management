<script>
import { defineProps, ref } from 'vue';
import { useRouter } from 'vue-router';

export default {
  props: {
    isAuthenticated: {
      type: Boolean,
      default: false,
    },
  },
  setup(props) {
    const router = useRouter();
    const showAlert = ref(false);
    const showConfirmDialog = ref(false);

    const goHome = () => {
      router.push({ name: 'Home' });
    };

    const goToBooking = () => {
      router.push({ name: 'Booking' });
    };

    const logout = () => {
      localStorage.removeItem("token");
      showAlert.value = true;
      setTimeout(() => {
        showAlert.value = false; 
      }, 3000);
      router.push({ name: 'Home' });
    };

    const confirmLogout = () => {
      showConfirmDialog.value = true;
    };

    const confirmLogoutAction = () => {
      logout();
      showConfirmDialog.value = false;
    };

    const cancelLogout = () => {
      showConfirmDialog.value = false;
    };

    return {
      goHome,
      goToBooking, // Expose the method for navigation
      confirmLogout,
      showAlert,
      showConfirmDialog,
      confirmLogoutAction,
      cancelLogout,
    };
  },
};
</script>

<template>
  <v-app-bar color="transparent" class="text-danger" app>
    <v-app-bar-title>Hotel.in</v-app-bar-title>

    <v-spacer></v-spacer>

    <v-btn @click="goHome" class="mx-auto">Home</v-btn>

    <v-btn v-if="isAuthenticated" @click="goToBooking">Bookings</v-btn>

    <v-btn v-if="isAuthenticated" @click="confirmLogout">Logout</v-btn>

    <v-alert v-if="showAlert" type="success" dismissible>
      Successfully logged out
    </v-alert>

    <v-dialog v-model="showConfirmDialog" max-width="290">
      <v-card>
        <v-card-title class="headline">Confirm Logout</v-card-title>
        <v-card-text>Are you sure you want to log out?</v-card-text>
        <v-card-actions>
          <v-btn color="green darken-1" text @click="confirmLogoutAction">Yes</v-btn>
          <v-btn color="red darken-1" text @click="cancelLogout">No</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-app-bar>
</template>
