<script setup>
import NavBar from "@/components/NavBar.vue";
import { ref, onMounted } from "vue";
import axios from "@/plugins/axios";

const isAuthenticated = ref(!!localStorage.getItem("token"));
const items = ref([]);

const fetchBookings = async () => {
    try {
        const response = await axios.get("/view-bookings");
        if (response.data.status) {
            items.value = response.data.data.map(booking => ({
                Hotel: booking.hotel,
                Room: booking.room,
                Location: booking.city,
                Type: booking.room_type,
                CheckIn: booking.check_in,
                CheckOut: booking.check_out,
                Price: booking.price,
            }));
            console.log(items.value);
        } else {
            console.error("Error fetching bookings:", response.data.message);
        }
    } catch (error) {
        console.error("API error:", error);
    }
};

onMounted(fetchBookings);
</script>

<template>
    <v-container fluid fill-height>
        <v-row align="center" justify="center">
            <v-col cols="12" md="10" lg="8">
                <NavBar :isAuthenticated="isAuthenticated" @logout="handleLogout" />

                <v-card>
                    <v-card-text>
                        <v-data-table 
                            :headers="[
                                { text: 'Hotel', value: 'Hotel' },
                                { text: 'Room', value: 'Room' },
                                { text: 'Location', value: 'Location' },
                                { text: 'Type', value: 'Type' },
                                { text: 'Check In', value: 'CheckIn' },
                                { text: 'Check Out', value: 'CheckOut' },
                                { text: 'Price', value: 'Price' }
                            ]" 
                            :items="items"
                            class="elevation-1"
                        >
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
