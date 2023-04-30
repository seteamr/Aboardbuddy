import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { PostTripComponent } from './post-trip/post-trip.component';
import { TripListComponent } from './trip-list/trip-list.component';
import { PostCarpoolComponent } from './post-carpool/post-carpool.component';
import { CarpoolListComponent } from './carpool-list/carpool-list.component';

const routes: Routes = [
  {path:'', redirectTo:'/trips/post-trip', pathMatch:'full'},
  {path:'post-trip', component:PostTripComponent},
  {path:'listing', component:TripListComponent},
  {path:'post-party-car-pool', component:PostCarpoolComponent},
  {path:'party-car-pool-list', component:CarpoolListComponent},
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class TripsRoutingModule { }
