import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { SupportRoutingModule } from './support-routing.module';
import { SupportComponent } from './support.component';
import { ReactiveFormsModule } from '@angular/forms';


@NgModule({
  declarations: [
    SupportComponent
  ],
  imports: [
    CommonModule,
    ReactiveFormsModule,
    SupportRoutingModule
  ]
})
export class SupportModule { }
