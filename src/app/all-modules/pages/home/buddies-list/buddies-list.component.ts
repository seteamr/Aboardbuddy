import { Component, OnInit } from '@angular/core';
import { BuddiesService,AdvertiseService } from 'src/app/core/services';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-buddies-list',
  templateUrl: './buddies-list.component.html',
  styleUrls: ['./buddies-list.component.css']
})
export class BuddiesListComponent  implements OnInit  {
  queryParams:any = {}; 
  error = "";
  buddies:any = [];
  defaultProfileImg = "https://bootstrapious.com/i/snippets/sn-team/teacher-4.jpg"
  userId:any='';
  adsList:any=[];
  constructor(private buddiesService: BuddiesService, private router:Router, private route:ActivatedRoute, private adService: AdvertiseService,) { }
  ngOnInit(): void {
    this.userId = localStorage.getItem('userId');
    this.route.queryParams.subscribe(params=>{
      if(params?.student_id && params?.country_id && params?.city_id && params?.university_id){
        this.queryParams = params;
        this.getBuddies();
      }else{
        this.error = "Invalid filter data";
      }
    })
    this.getAds();
  }

  getBuddies(){
    let value = this.queryParams;
    let data = new FormData();
    data.append('student_id', value?.student_id);
    data.append('country_id', value?.country_id);
    data.append('city_id', value?.city_id);
    data.append('university_id', value?.university_id);
   
    this.buddiesService.findBuddies(data).subscribe((res:any)=>{
      this.buddies = res?.data?res?.data:[];
      this.error = res?.message?res?.message:(this.buddies?.length)?'':'No buddies found';
    }, errro=>{
        this.error = "Something went wrong";
    })
  }

  getAds(){
    let data = new FormData();
    data.append('student_id', this.userId)
    this.adService.getAdByCity(data).subscribe((res:any)=>{
      this.adsList = res?.data?res?.data:[];
    })
  }
}
