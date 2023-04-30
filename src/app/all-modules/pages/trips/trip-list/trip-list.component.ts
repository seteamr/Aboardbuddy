import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { UserService } from 'src/app/core/services';

@Component({
  selector: 'app-trip-list',
  templateUrl: './trip-list.component.html',
  styleUrls: ['./trip-list.component.css']
})
export class TripListComponent {
  userId:any='';
  trips:any=[];
  constructor(private userService: UserService, private router:Router) { }
  ngOnInit(): void {
    this.userId = localStorage.getItem('userId');
    this.getTrips()
  }

  ngOnDestroy(){
    // if(this.userSubscription){this.userSubscription.unsubscribe()}
  }

  getTrips(){
    let data = new FormData();
    data.append('my_id', this.userId)
    this.userService.getTrips(data).subscribe((res:any)=>{
      this.trips = res?.data?res?.data:[];
    })
  }
}
