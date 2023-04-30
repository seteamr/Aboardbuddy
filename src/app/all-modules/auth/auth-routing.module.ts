import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthComponent } from './auth.component';
import { ForgotPasswordComponent } from './forgot-password/forgot-password.component';
import { LoginComponent } from './login/login.component';
import { RegisterComponent } from './register/register.component';
import { SellerRegisterationComponent } from './seller-registeration/seller-registeration.component';
const routes: Routes = [
  {path: '', component: AuthComponent, children: [
    { path: '', redirectTo: '/auth/login', pathMatch: 'full' },
    { path: 'login', component:LoginComponent },
    { path: 'register', component:RegisterComponent },
    { path: 'forgot-password', component:ForgotPasswordComponent },
    { path: 'seller-registeration', component:SellerRegisterationComponent },
  ]}
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AuthRoutingModule { }
