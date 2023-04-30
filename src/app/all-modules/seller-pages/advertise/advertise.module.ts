import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { AdvertiseRoutingModule } from './advertise-routing.module';
import { PostAddComponent } from './post-add/post-add.component';
import { MyAddsComponent } from './my-adds/my-adds.component';
import { ReactiveFormsModule } from '@angular/forms';


@NgModule({
  declarations: [
    PostAddComponent,
    MyAddsComponent
  ],
  imports: [
    CommonModule,
    ReactiveFormsModule,
    AdvertiseRoutingModule
  ]
})
export class AdvertiseModule { }
