import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/core/guard';
import { SellerPagesComponent } from './seller-pages.component';

const routes: Routes = [
  {
    path: '', component: SellerPagesComponent, children: [
      { path: '', redirectTo: 'auth/login', pathMatch: 'full' },
      { path: 'accounts', loadChildren: () => import('./accounts/accounts.module').then((m) => m.AccountsModule), canActivateChild: [AuthGuard], },
      { path: 'advertise', loadChildren: () => import('./advertise/advertise.module').then((m) => m.AdvertiseModule), canActivateChild: [AuthGuard], },
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class SellerPagesRoutingModule { }
 