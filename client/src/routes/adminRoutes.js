import Dashboard from '../pages/admin/Dashboard.svelte';
import CompaniesPage from '../pages/admin/companies/CompaniesPage.svelte';
import CompanyPage from '../pages/admin/companies/CompanyPage.svelte'
import City from '../pages/admin/cities/City.svelte';
import CitiesPage from '../pages/admin/cities/CitiesPage.svelte';
import TerminalPage from '../pages/admin/terminals/TerminalPage.svelte'


const adminRoutes = [
	{ name: 'index', component: Dashboard },
	{ name: 'cities', component: CitiesPage },
	{ name: 'cities/:id', component: '',
		nestedRoutes: [
			{ name: 'index', component: City },
			{ name: 'parks', component: City }
		],
	},
	{ name: 'companies', component: CompaniesPage },
	{ name: 'companies/create', component: CompaniesPage },
	{ name: 'companies/:id', component: '',
		nestedRoutes: [
			{ name: 'index', component: CompanyPage },
			{ name: 'edit', component: CompaniesPage },
			{ name: 'terminals', component: CompanyPage },
			{ name: 'terminals/create', component: CompanyPage },
			{ name: 'terminals/:terminalId', component: '',
				nestedRoutes: [
					{ name: 'index', component: TerminalPage },
					{ name: 'create', component: TerminalPage },
					{ name: 'edit', component: CompanyPage },
					{ name: 'routes', component: TerminalPage },
					{ name: 'routes/:routeId', component: TerminalPage,
						nestedRoutes: [
							{ name: 'edit', component: TerminalPage },
						]
					}
				],
			},
		],
	},
];

export default adminRoutes;
