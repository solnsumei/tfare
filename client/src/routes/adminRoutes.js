import Dashboard from '../pages/admin/Dashboard.svelte';
import CompanyList from '../pages/admin/CompanyList.svelte';


const adminRoutes = [
  { name: 'index', component: Dashboard },
  { name: 'companies', component: CompanyList },
];

export default adminRoutes;
