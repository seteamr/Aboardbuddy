import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ChatingRoutingModule } from './chating-routing.module';
import { ChatComponent } from './chat/chat.component';
import { FormsModule } from '@angular/forms';


@NgModule({
  declarations: [
    ChatComponent
  ],
  imports: [
    CommonModule,
    FormsModule,
    ChatingRoutingModule
  ]
})
export class ChatingModule { }
