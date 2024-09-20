<script>
import apiService from "@/services/apiService";

export default {
  props: {
    type: {
      type: String,
      required: true,
    },
    search: {
      type: String,
      default: "",
    },
    location: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      rooms: [],
      hotels: [],
    };
  },
  watch: {
    type: {
      immediate: true,
      handler(newValue) {
        this.fetchProperties(newValue);
      },
    },
    search: {
      immediate: true,
      handler() {
        this.fetchProperties(this.type);
      },
    },
    location: {
      immediate: true,
      handler() {
        this.fetchProperties(this.type);
      },
    },
  },
  methods: {
    async fetchProperties(type) {
      try {
        const response = await apiService.fetchProperties(
          this.search,
          this.location
        );
        if (response && response.data) {
          if (type === "rooms") {
            this.rooms = response.data.rooms;
          } else if (type === "hotels") {
            this.hotels = response.data.hotels;
          }
        }
      } catch (error) {
        console.error("Error fetching properties:", error);
      }
    },

    ViewRoom(roomId) {
      this.$router.push({ name: "ViewRoom", params: { id: roomId } });
    },
  },
};
</script>


<style scoped>
</style>

<template>
  <v-container>
    <v-row>
      <v-col
        cols="12"
        sm="4"
        v-if="type === 'rooms'"
        v-for="room in rooms"
        :key="room.id"
      >
        <v-card :loading="loading" class="pa-2">
          <v-img
            height="100%"
            width="100%"
            :src="room.room_images[0]"
            contain
            alt="Room Image"
          ></v-img>
          <v-card-title class="text-h6">{{ room.room }}</v-card-title>
          <v-card-text class="py-1">
            <v-rating
              :value="4.5"
              color="amber"
              dense
              half-increments
              readonly
              size="14"
            ></v-rating>
            <div class="text-body-1">${{ room.price }}</div>
            <div class="text-body-1">
              {{ room.hotel.hotel }} - {{ room.hotel.state.state }}
            </div>
            <div class="text-body-1">{{ room.hotel.city }}</div>
            <div class="text-body-1">
              Guests : <strong>{{ room.number_of_guests }}</strong>
            </div>
          </v-card-text>
          <v-divider></v-divider>
          <v-card-title class="">Amenities</v-card-title>
          <v-card-text class="">
            <v-chip
              v-for="(amenity, index) in room.amenity_name"
              :key="index"
              class="ma-1"
              >{{ amenity }}</v-chip
            >
          </v-card-text>
          <v-card-actions>
            <v-btn
              color="deep-purple lighten-2"
              text
              @click="ViewRoom(room.id)"
            >
              Reserve
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>

      <v-col
        cols="12"
        sm="4"
        v-if="type === 'hotels'"
        v-for="hotel in hotels"
        :key="hotel.id"
      >
        <v-card class="pa-2">
          <v-img
            height="100%"
            width="100%"
            :src="hotel.hotel_images[0]"
            contain
            alt="Room Image"
          ></v-img>
          <v-card-title class="text-h6">{{ hotel.hotel }}</v-card-title>
          <v-card-text class="py-1">
            <v-rating
              value="4.5"
              color="amber"
              dense
              half-increments
              readonly
              size="14"
            ></v-rating>
            <div class="text-body-1">
              <strong>{{ hotel.state.state }}</strong>
            </div>
            <div class="text-body-1">{{ hotel.city }}</div>
          </v-card-text>

          <v-card-actions>
            <v-btn color="deep-purple lighten-2" text @click="view">
              View
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

