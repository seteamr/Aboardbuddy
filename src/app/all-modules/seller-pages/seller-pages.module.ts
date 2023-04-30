import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { SellerPagesRoutingModule } from './seller-pages-routing.module';
import { SellerPagesComponent } from './seller-pages.component';
import { LayoutModule } from '../layout/layout.module';


@NgModule({
  declarations: [
    SellerPagesComponent
  ],
  imports: [
    CommonModule,
    LayoutModule,
    SellerPagesRoutingModule
  ]
})
export class SellerPagesModule { }
