import { RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
  {
    path: '/order',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('src/pages/Order.vue') }],
  },

  {
    path: '/order2',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('src/pages/Order2.vue') }],
  },

  {
    path: '/about',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/Content.vue') }],
  },

  {
    path: '/login',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/Login.vue') }],
  },

  {
    path: '/register',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/Signup.vue') }],
  },

  {
    path: '/unassigned_orders',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/Accountant.vue') }],
  },

  {
    path: '/open_orders',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/SentOrders.vue') }],
  },
  {
    path: '/completed',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/Completed.vue') }],
  },


  {
    path: '/view_order',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/AllOrders.vue') }],
  },

  {
    path: '/contractor',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/Contractor.vue') }],
  },

  {
    path: '/pending_order',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/PendingOrder.vue') }],
  },

  {
    path: '/pending_orders',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/PendingOrders.vue') }],
  },

  {
    path: '/my_contracts',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/MyContract.vue') }],
  },

  {
    path: '/success',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/MyContract.vue') }],

  },


  {
    path: '/rejected_orders',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/Rejected.vue') }],
  },

  {
    path: '/all_orders',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/Finance.vue') }],
  },

  {
    path: '/notifications',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/Notifications.vue') }],
  },

  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/Home.vue') }],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/Error404.vue'),
  },
];

export default routes;
