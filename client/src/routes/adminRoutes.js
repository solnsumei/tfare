import Dashboard from '../pages/admin/Dashboard.svelte';
import CompanyPage from '../pages/admin/companies/CompanyPage.svelte';
import CityPage from '../pages/admin/cities/CityPage.svelte';


const adminRoutes = [
  { name: 'index', component: Dashboard },
  { name: 'cities', component: CityPage },
  { name: 'companies', component: CompanyPage },
];

export default adminRoutes;
