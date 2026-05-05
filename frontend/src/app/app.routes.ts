import { Routes } from '@angular/router';
import { authGuard } from './guards/auth.guard';
import { backofficeGuard } from './guards/backoffice.guard';

export const routes: Routes = [
  {
    path: 'login',
    loadComponent: () => import('./pages/core/login/login.page').then((m) => m.LoginPage),
  },
  {
    path: 'backoffice',
    loadComponent: () => import('./pages/backoffice/backoffice.page').then((m) => m.BackofficePage),
    canActivate: [authGuard, backofficeGuard],
    canActivateChild: [authGuard, backofficeGuard],
    children: [
      {
        path: '',
        redirectTo: 'families',
        pathMatch: 'full',
      },
      {
        path: 'families',
        loadComponent: () => import('./pages/backoffice/families/families.page').then((m) => m.FamiliesPage),
      },
      {
        path: 'taxes',
        loadComponent: () => import('./pages/backoffice/taxes/taxes.page').then((m) => m.TaxesPage),
      },
      {
        path: 'products',
        loadComponent: () => import('./pages/backoffice/products/products.page').then((m) => m.ProductsPage),
      },
      {
        path: 'zones',
        loadComponent: () => import('./pages/backoffice/zones/zones.page').then((m) => m.ZonesPage),
      },
      {
        path: 'tables',
        loadComponent: () => import('./pages/backoffice/tables/tables.page').then((m) => m.TablesPage),
      },
    ],
  },
  {
    path: '',
    redirectTo: 'login',
    pathMatch: 'full',
  },
];
