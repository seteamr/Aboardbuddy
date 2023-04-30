import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { AuthRoutingModule } from './auth-routing.module';
import { LoginComponent } from './login/login.component';
import { RegisterComponent } from './register/register.component';
import { AuthComponent } from './auth.component';
import { ForgotPasswordComponent } from './forgot-password/forgot-password.component';
import { LayoutModule } from '../layout/layout.module';
import {ReactiveFormsModule, FormsModule} from "@angular/forms";
import { SellerRegisterationComponent } from './seller-registeration/seller-registeration.component'


@NgModule({
  declarations: [
    LoginComponent,
    RegisterComponent,
    AuthComponent,
    ForgotPasswordComponent,
    SellerRegisterationComponent
  ],
  imports: [
    CommonModule,
    LayoutModule,
    FormsModule,
    ReactiveFormsModule,
    AuthRoutingModule
  ]
})
export class AuthModule { }
