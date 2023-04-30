import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { MyAddsComponent } from './my-adds/my-adds.component';
import { PostAddComponent } from './post-add/post-add.component';

const routes: Routes = [
  {path:'', redirectTo:'/sellet/advertise/post-add', pathMatch:'full'},
  {path:'post-add', component:PostAddComponent},
  {path:'my-adds', component:MyAddsComponent},
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AdvertiseRoutingModule { }
