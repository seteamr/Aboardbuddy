import { Component } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { ChatService, UserService } from 'src/app/core/services';

@Component({
  selector: 'app-buddies-detail',
  templateUrl: './buddies-detail.component.html',
  styleUrls: ['./buddies-detail.component.css']
})
export class BuddiesDetailComponent {
  userDetails:any = {};
  studentId:any='';
  userId:any='';
  defaultProfilePic = 'http://dairysystem.in/Buddy/admin/uploads/student/no_image.png';
  isSeller:boolean=false;
  constructor(private userService: UserService, private router:Router, private route:ActivatedRoute, private chatService:ChatService) {
    this.route.params.subscribe(param=>{
      this.studentId = param?.studentId;
      if(this.studentId){
        // this.getProfile();
      }
    })
  }
  
  ngOnInit(): void {
    this.userId = localStorage.getItem('userId');
    this.getProfile();
  }

  getProfile(){
    let data = new FormData();
    data.append('student_id', this.studentId)
    data.append('my_id', this.userId)
    
    this.userService.getProfile(data).subscribe((res:any)=>{
     this.userDetails = res?.data?res?.data:{};
     this.isSeller = this.userDetails?.role=='seller'?true:false;
    })
  }

  chat(){
    if(this.userDetails?.is_connect ==3){
      let data = new FormData();
      data.append('to_id', this.studentId)
      data.append('from_id', this.userId)
      this.chatService.sendChatRequest(data).subscribe((res:any)=>{
        this.getProfile();
      })
    }else if(this.userDetails?.is_connect ==1 ){
       this.router.navigate(['/chating/chat'], {queryParams:{selectedUserId:this.studentId}});
    }

  }

}
