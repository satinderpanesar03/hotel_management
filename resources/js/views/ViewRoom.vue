<script setup>
import { ref, onMounted, computed } from "vue";
import { useRoute } from "vue-router";
import NavBar from "@/components/NavBar.vue";
import Footer from "@/components/Footer.vue";
import AuthModal from "@/components/Auth.vue";

const valid = ref(false);
const fromDate = ref("");
const toDate = ref("");
const numberOfGuests = ref(null);
const guestsOptions = ref([]);
const images = ref([]);
const room = ref(null);
const dateError = ref("");
const showDialog = ref(false);
const showAuthModal = ref(false);
const showConfirmationDialog = ref(false);
const showSuccessAlert = ref(false);
const successMessage = ref("");
const authenticated = ref(false);
const isAuthenticated = ref(!!localStorage.getItem("token"));

const fetchRoom = async (roomId) => {
  try {
    const response = await fetch(
      `http://127.0.0.1:8000/api/show-room/${roomId}`
    );
    const data = await response.json();

    if (data.status) {
      room.value = data.data;
      images.value = data.data.room_images;
      const maxGuests = data.data.number_of_guests;
      guestsOptions.value = Array.from({ length: maxGuests }, (_, i) => i + 1);
    } else {
      console.error(data.message);
    }
  } catch (error) {
    console.error("Error fetching room details:", error);
  }
};

const handleReservation = async () => {
  if (!isAuthenticated.value) return;

  const userId = localStorage.getItem("user_id");
  const bookingData = {
    room_id: room.value.id,
    user_id: userId,
    check_in: fromDate.value,
    check_out: toDate.value,
    price: totalPrice.value,
  };

  try {
    const response = await fetch(
      `http://127.0.0.1:8000/api/store-booking/${room.value.id}`,
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        body: JSON.stringify(bookingData),
      }
    );
    const data = await response.json();

    if (data.status) {
      successMessage.value = data.message;
      showSuccessAlert.value = true;
      showConfirmationDialog.value = false;

      fromDate.value = "";
      toDate.value = "";
      numberOfGuests.value = null;
    } else {
      if (data.message.includes("already booked")) {
        dateError.value = data.message;
        showDialog.value = true;
      } else {
        console.error(data.message);
      }
    }
  } catch (error) {
    console.error("Error booking the room:", error);
  }
};

const checkLogin = () => {
  const token = localStorage.getItem("token");
  if (token) {
    showConfirmationDialog.value = true;
  } else {
    showAuthModal.value = true;
  }
};

const route = useRoute();
const roomId = route.params.id;

onMounted(() => {
  if (roomId) {
    fetchRoom(roomId);
  } else {
    console.error("Room ID is missing in the query parameters.");
  }
});

const totalPrice = computed(() => {
  if (fromDate.value && toDate.value && room.value) {
    const from = new Date(fromDate.value);
    const to = new Date(toDate.value);
    const timeDiff = to - from;
    const days = Math.ceil(timeDiff / (1000 * 3600 * 24));
    return days > 0 ? days * room.value.price : 0;
  }
  return 0;
});

const validateDates = () => {
  if (toDate.value && fromDate.value) {
    const from = new Date(fromDate.value);
    const to = new Date(toDate.value);
    if (to <= from) {
      dateError.value = "To Date must be greater than From Date.";
      showDialog.value = true;
      toDate.value = "";
      return false;
    } else {
      dateError.value = "";
    }
  }
  return true;
};

const onAuthComplete = () => {
  authenticated.value = true;
  showAuthModal.value = false;
  isAuthenticated.value = true;
};
</script>

<template>
  <NavBar :isAuthenticated="isAuthenticated" @logout="handleLogout" />
  <v-container>
    <v-row align="center" justify="center">
      <v-col cols="12" md="6">
        <div class="carousel-container">
          <v-carousel
            v-if="images.length > 0"
            hide-delimiter-background
            show-arrows
            class="my-carousel"
          >
            <v-carousel-item v-for="(image, index) in images" :key="index">
              <v-img :src="image" height="400px" class="rounded"></v-img>
            </v-carousel-item>
          </v-carousel>
          <div v-else>
            <p>No images available.</p>
          </div>
        </div>
      </v-col>

      <v-col cols="8" md="6">
        <v-card class="mx-auto" max-width="400">
          <v-card-title>
            <span class="headline">{{ room?.room }}</span>
          </v-card-title>
          <v-card-title>
            <span class="text-primary">${{ room?.price }} /Night</span>
          </v-card-title>
          <v-card-title>
            <span class="text-success"
              >{{ room?.hotel.hotel }} - {{ room?.hotel.city }}</span
            >
          </v-card-title>
          <v-card-text>
            <v-form
              ref="form"
              v-model="valid"
              @submit.prevent="valid && validateDates()"
            >
              <v-row>
                <v-col>
                  <v-text-field
                    v-model="fromDate"
                    label="From Date"
                    type="date"
                    :rules="[(v) => !!v || 'From date is required']"
                    required
                  ></v-text-field>
                </v-col>
                <v-col>
                  <v-text-field
                    v-model="toDate"
                    label="To Date"
                    type="date"
                    :rules="[(v) => !!v || 'To date is required']"
                    @change="validateDates"
                    required
                  ></v-text-field>
                  <div v-if="dateError" class="error">{{ dateError }}</div>
                </v-col>
              </v-row>

              <v-row>
                <v-col>
                  <v-select
                    v-model="numberOfGuests"
                    :items="guestsOptions"
                    label="Number of Guests"
                    :rules="[(v) => !!v || 'Number of guests is required']"
                    required
                  ></v-select>
                </v-col>
              </v-row>

              <v-row>
                <v-col>
                  <span class="text-body-1">
                    Total Price: <strong>${{ totalPrice.toFixed(2) }}</strong>
                  </span>
                </v-col>
              </v-row>

              <v-card-text>
                <v-chip
                  v-for="(amenity, index) in room?.amenity_name"
                  :key="index"
                  class="ma-1"
                >
                  {{ amenity }}
                </v-chip>
              </v-card-text>

              <v-btn
                color="primary"
                @click="checkLogin"
                :disabled="!valid || !!dateError"
                class="mt-4"
              >
                Reserve
              </v-btn>
              <AuthModal
                v-model="showAuthModal"
                @auth-success="handleReservation"
                @auth-complete="onAuthComplete"
              />
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <v-dialog v-model="showDialog" max-width="290">
      <v-card>
        <v-card-title class="headline">Error</v-card-title>
        <v-card-text>{{ dateError }}</v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="showDialog = false">Okay</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="showConfirmationDialog" max-width="500">
      <v-card>
        <v-card-title class="headline">Confirm Booking</v-card-title>
        <v-card-text>
          Are you sure you want to book this room from {{ fromDate }} to
          {{ toDate }} for {{ numberOfGuests }} guests?
        </v-card-text>
        <v-card-actions>
          <v-btn color="green darken-1" text @click="handleReservation"
            >Confirm</v-btn
          >
          <v-btn
            color="red darken-1"
            text
            @click="showConfirmationDialog = false"
            >Cancel</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="showDialog" max-width="290">
      <v-card>
        <v-card-title class="headline">Error</v-card-title>
        <v-card-text>{{ dateError }}</v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="showDialog = false">Okay</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-alert v-model="showSuccessAlert" type="success" dismissible>
      {{ successMessage }}
    </v-alert>
  </v-container>
  <Footer />
</template>
