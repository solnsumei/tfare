import { setAuthHeader } from '../utils/helpers';
import AdminApiClient from './AdminApiClient';

// Admin routes
setAuthHeader();

const urlPrefix = '/admin';
const citiesUrl = `${urlPrefix}/cities`;
const companiesUrl = `${urlPrefix}/companies`;

export const citiesApiClient = new AdminApiClient(citiesUrl);
export const companiesApiClient = new AdminApiClient(companiesUrl);
