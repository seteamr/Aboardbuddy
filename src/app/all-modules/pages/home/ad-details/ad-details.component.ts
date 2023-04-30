import { Component } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { AdvertiseService, ChatService, UserService } from 'src/app/core/services';

@Component({
  selector: 'app-ad-details',
  templateUrl: './ad-details.component.html',
  styleUrls: ['./ad-details.component.css']
})
export class AdDetailsComponent {
  adDetails:any = {};
  adId:any='';
  userId:any='';
  defaultProfilePic = 'http://dairysystem.in/Buddy/admin/uploads/student/no_image.png';
  spamloading:boolean=false;
  constructor(private advertiseService: AdvertiseService, private router:Router, private route:ActivatedRoute, private chatService:ChatService) {
    this.route.params.subscribe(param=>{
      this.adId = param?.adId;
      if(this.adId){
        // this.getAdDetails();
      }
    })
  }
  
  ngOnInit(): void {
    this.userId = localStorage.getItem('userId');
    this.getAdDetails();
  }

  getAdDetails(){
    let data = new FormData();
    data.append('post_id', this.adId)
    data.append('student_id', this.userId)
    
    this.advertiseService.getAdDetails(data).subscribe((res:any)=>{
     this.adDetails = res?.data?res?.data:{};
     this.spamloading = false;
    }, error=>{
     this.spamloading = false;
    })
  }

  reportAdAsSpam(){
    this.spamloading = true;
    let data = new FormData();
    data.append('post_id', this.adId);
    data.append('student_id', this.userId);
    
    this.advertiseService.reportAdAsSpam(data).subscribe((res:any)=>{
     this.getAdDetails();
    }, error=>{
      this.spamloading = false;
    })
  }
  
}
