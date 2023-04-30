import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AdvertiseService } from 'src/app/core/services';

@Component({
  selector: 'app-my-adds',
  templateUrl: './my-adds.component.html',
  styleUrls: ['./my-adds.component.css']
})
export class MyAddsComponent implements OnInit {

  userId:any='';
  trips:any=[];
  constructor(private adService: AdvertiseService,private router:Router) { }
  ngOnInit(): void {
    this.userId = localStorage.getItem('userId');
    this.getMyAds()
  }

  ngOnDestroy(){
    // if(this.userSubscription){this.userSubscription.unsubscribe()}
  }

  getMyAds(){
    let data = new FormData();
    data.append('student_id', this.userId)
    this.adService.getMyAds(data).subscribe((res:any)=>{
      this.trips = res?.data?res?.data:[];
    })
  }

}
