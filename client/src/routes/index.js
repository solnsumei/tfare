import adminRoutes from './adminRoutes';
import { isAuthenticated, isGuest } from '../utils/helpers';

import SiteLayout from '../layout/SiteLayout.svelte';
import AdminLayout from '../layout/AdminLayout.svelte';
import Home from '../pages/Home.svelte';
import About from '../pages/About.svelte';
import Login from '../pages/Login.svelte';


const routes = [
  {
    name: 'admin',
    component: AdminLayout,
    onlyIf: { guard: isAuthenticated, redirect: '/login' },
    nestedRoutes: adminRoutes,
  },
  { name: 'about', component: About, layout: SiteLayout },
  {
    name: 'login',
    component: Login,
    layout: SiteLayout,
    onlyIf: { guard: isGuest, redirect: '/admin' }
  },
  {
    name: '/',
    layout: SiteLayout,
    component: Home,
  },
];

export default routes;
