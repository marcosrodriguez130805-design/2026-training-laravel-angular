import { Routes } from '@angular/router';
import { authGuard } from './guards/auth.guard';
import { backofficeGuard } from './guards/backoffice.guard';

export const routes: Routes = [
  {
    path: 'login',
    loadComponent: () => import('./pages/core/login/login.page').then((m) => m.LoginPage),
  },
  {
    path: 'selector-entorno',
    loadComponent: () => import('./pages/selector-entorno/selector-entorno.page').then((m) => m.SelectorEntornoPage),
    canActivate: [authGuard],
  },
  {
    path: 'tpv',
    loadComponent: () => import('./pages/tpv-camarero/tpv-camarero.page').then((m) => m.TpvCamareroPage),
    canActivate: [authGuard],
  },
  {
    path: 'tpv-mesas',
    loadComponent: () => import('./pages/tpv-mesas/tpv-mesas.page').then((m) => m.TpvMesasPage),
    canActivate: [authGuard],
  },
  {
    path: 'seleccion-mesa',
    loadComponent: () => import('./pages/seleccion-mesa/seleccion-mesa.page').then((m) => m.SeleccionMesaPage),
    canActivate: [authGuard],
  },
  {
    path: 'tpv-comanda',
    loadComponent: () => import('./pages/tpv-comanda/tpv-comanda.page').then((m) => m.TpvComandaPage),
    canActivate: [authGuard],
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
      {
        path: 'users',
        loadComponent: () => import('./pages/backoffice/users/users.page').then(m => m.UsersPage)
      },
    ],
  },
  {
    path: '',
    redirectTo: 'login',
    pathMatch: 'full',
  },
];
