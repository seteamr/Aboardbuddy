import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HeaderComponent } from './header/header.component';
import { FooterComponent } from './footer/footer.component';
import { RouterModule } from '@angular/router';
import { AuthHeaderComponent } from './auth-header/auth-header.component';

const comp = [HeaderComponent, FooterComponent, AuthHeaderComponent]


@NgModule({
  declarations: [...comp ],
  imports: [
    CommonModule,
    RouterModule
  ],
  exports: [...comp]
})
export class LayoutModule { }
