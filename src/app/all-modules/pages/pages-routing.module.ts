import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/core/guard';
import { PagesComponent } from './pages.component';

const routes: Routes = [
  {
    path: '', component: PagesComponent, children: [
      { path: '', redirectTo: 'auth/login', pathMatch: 'full' },
      { path: 'home', loadChildren: () => import('./home/home.module').then((m) => m.HomeModule), canActivateChild: [AuthGuard], },
      { path: 'trips', loadChildren: () => import('./trips/trips.module').then((m) => m.TripsModule), canActivateChild: [AuthGuard], },
      { path: 'accounts', loadChildren: () => import('./accounts/accounts.module').then((m) => m.AccountsModule), canActivateChild: [AuthGuard], },
      { path: 'about-us', loadChildren: () => import('./about-us/about-us.module').then((m) => m.AboutUsModule) },
      { path: 'support', loadChildren: () => import('./support/support.module').then((m) => m.SupportModule) },
      { path: 'chating', loadChildren: () => import('./chating/chating.module').then((m) => m.ChatingModule) },
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class PagesRoutingModule { }
