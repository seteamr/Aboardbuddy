import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { BuddiesDetailComponent } from './buddies-detail/buddies-detail.component';
import { BuddiesListComponent } from './buddies-list/buddies-list.component';
import { FindBuddiesComponent } from './find-buddies/find-buddies.component';
import { AdDetailsComponent } from './ad-details/ad-details.component';
import { HomeComponent } from './home.component';

const routes: Routes = [
  // {path:'', redirectTo:'/home', pathMatch:'full'},
  {path:'', component:HomeComponent},
  {path:'select-university', component:FindBuddiesComponent},
  {path:'buddies/list', component:BuddiesListComponent},
  {path:'buddies/detail/:studentId', component:BuddiesDetailComponent},
  {path:'buddies/ad-details/:adId', component:AdDetailsComponent},

];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class HomeRoutingModule { }
