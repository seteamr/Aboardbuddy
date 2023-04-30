import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { UserService } from 'src/app/core/services';

@Injectable({
  providedIn: 'root'
})
export class DataService {
  public loginUser = new BehaviorSubject('');

  constructor(private userService:UserService) { }
  getUserDetails() {
    return this.loginUser.asObservable();
  }

  setUserDetails(data: any={}) {
    this.loginUser.next(data);
  }

  getUpdatedUserInfo(){
    let userId:any = localStorage.getItem('userId')
    let data = new FormData();
    data.append('student_id', userId)
    this.userService.getProfile(data).subscribe((res:any)=>{
     let details = res?.data?res?.data:{};
     this.setUserDetails(details);
    })
  }
}
