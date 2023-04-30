import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
const routes: Routes = [
  { path: '', redirectTo:'home',pathMatch:'full' }, 
  { path: 'auth', loadChildren: () => import('./all-modules/auth/auth.module').then(m => m.AuthModule)},
  { path: '', loadChildren: () => import('./all-modules/pages/pages.module').then(m => m.PagesModule)},
  { path: 'seller', loadChildren: () => import('./all-modules/seller-pages/seller-pages.module').then(m => m.SellerPagesModule)},
  
]

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})

export class AppRoutingModule { }
