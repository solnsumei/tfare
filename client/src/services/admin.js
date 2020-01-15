import { setAuthHeader } from '../utils/helpers';
import AdminApiClient from './AdminApiClient';
import AdminService from './AdminService';
import { cities, companies, parks, terminals, busRoutes } from '../stores/admin';

// Admin routes
setAuthHeader();

const urlPrefix = '/admin';

const citiesApiClient = new AdminApiClient(`${urlPrefix}/cities`);
const companiesApiClient = new AdminApiClient(`${urlPrefix}/companies`);
const parksApiClient = new AdminApiClient(`${urlPrefix}/parks`);
const terminalsApiClient = new AdminApiClient(`${urlPrefix}/terminals`);
const routesApiClient = new AdminApiClient(`${urlPrefix}/routes`);

export const cityService = new AdminService({ name: 'city', client: citiesApiClient, store: cities });
export const companyService = new AdminService({ name: 'company', client: companiesApiClient, store: companies });
export const parkService = new AdminService({ name: 'park', client: parksApiClient, store: parks });
export const terminalService = new AdminService({ name: 'terminal', client: terminalsApiClient, store: terminals });
export const routeService = new AdminService({ name: 'route', client: routesApiClient, store: busRoutes });
