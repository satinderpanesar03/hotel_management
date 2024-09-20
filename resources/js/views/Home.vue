<script setup>
import NavBar from "@/components/NavBar.vue";
import Product from "@/components/Product.vue";
import Footer from "@/components/Footer.vue";
import { ref } from "vue";

const isAuthenticated = ref(!!localStorage.getItem("token"));
const searchQuery = ref(''); 
const locationQuery = ref('');
const tab = ref(1); 

const handleSearch = () => {
};
</script>

<style scoped>
.mar-top {
  margin-top: 250px;
}
</style>

<template>
  <v-app>
    <NavBar :isAuthenticated="isAuthenticated" @logout="handleLogout" />
    <v-main>
      <v-container>
        <v-card>
          <v-row>
            <v-col cols="12" sm="12" class="d-flex justify-center align-center">
              <h1 class="top text-dark"><strong>BOOKING MADE EASY</strong></h1>
            </v-col>
            <v-col cols="12" sm="4" class="d-flex justify-center align-center">
              <v-text-field
                v-model="searchQuery"
                label="Search by room/hotel"
                clearable
                @keyup="handleSearch"
              />
            </v-col>
            <v-col cols="12" sm="4" class="d-flex justify-center align-center">
              <v-text-field
                v-model="locationQuery"
                label="Location"
                clearable
                @keyup="handleSearch"
              />
            </v-col>
          </v-row>
          <v-tabs
            v-model="tab"
            color="deep-purple-accent-4"
            align-tabs="center"
          >
            <v-tab :value="1">Rooms</v-tab>
            <v-tab :value="2">Hotels</v-tab>
          </v-tabs>
          <v-window v-model="tab">
            <v-window-item v-for="n in 2" :key="n" :value="n">
              <v-container fluid>
                <v-row>
                  <v-col cols="12" sm="9">
                    <Product :type="tab === 1 ? 'rooms' : 'hotels'" :search="searchQuery" :location="locationQuery" />
                  </v-col>
                </v-row>
              </v-container>
            </v-window-item>
          </v-window>
        </v-card>
      </v-container>
    </v-main>
    <Footer />
  </v-app>
</template>
