// resources/js/router.js
import { createRouter, createWebHistory } from 'vue-router';

const routes = [
  {
    path: '/',
    component: () => import('@/layouts/default/Default.vue'),
    children: [
      {
        path: '',
        name: 'Home',
        component: () => import('@/views/Home.vue'),
      },
      {
        path: '/room/:id',
        name: 'ViewRoom',
        component: () => import('@/views/ViewRoom.vue'),
      },
      {
        path: '/bookings',
        name: 'Booking',
        component: () => import('@/views/Booking.vue'),
      }
    ],
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
