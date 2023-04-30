import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { UserService } from 'src/app/core/services';
import {Subscription} from 'rxjs'
@Component({
  selector: 'app-carpool-list',
  templateUrl: './carpool-list.component.html',
  styleUrls: ['./carpool-list.component.css']
})
export class CarpoolListComponent {
  userId:any='';
  trips:any=[];
  searchTimeOut:any;
  listApiSubscription:Subscription;
  searchValue:any="";
  joinedPeople:any[];
  constructor(private userService: UserService, private router:Router) { }
  ngOnInit(): void {
    this.userId = localStorage.getItem('userId');
    this.getCarPoolList()
  }

  ngOnDestroy(){
    if(this.listApiSubscription){this.listApiSubscription.unsubscribe()}
    if(this.searchTimeOut){clearInterval(this.searchTimeOut)}
  }

  getCarPoolList(){
    let data = new FormData();
    data.append('student_id', this.userId);
    if(this.searchValue){
      data.append('search', this.searchValue);
    }
    if(this.listApiSubscription){    this.listApiSubscription.unsubscribe();}
    this.listApiSubscription =  this.userService.getPartyCarPoolList(data).subscribe((res:any)=>{
      let data = res?.data?res?.data:[];
      data.forEach((ele:any)=>{
        ele.members = ele.members?ele.members:[];
        let isJoined = ele.members.find((member:any)=>member?.student_id == this.userId);
        ele.isJoined =  isJoined?.student_id?true:false;
      })
      this.trips = data; 
    })
  }

  joinCarPool(index:number, item:any){
    let data = new FormData();
    data.append('student_id', this.userId)
    data.append('carpool_id', item.carpool_id)

    this.userService.joinPartyCarPool(data).subscribe((res:any)=>{
      if(res?.status=='200'){
        this.getCarPoolList();
        alert(res?.message)
      }else{
        alert(res?.message)
      }

    })
  }

  deleteCarPoolPost(index:number, item:any){
    let data = new FormData();
    data.append('student_id', this.userId)
    data.append('carpool_id', item.carpool_id)

    this.userService.deletePartyCarPool(data).subscribe((res:any)=>{
      let msg = res?.message?res?.message:"Post deleted successfully"
      if(res?.status=='200'){
        this.getCarPoolList();
        alert(msg)
      }else{
        alert(msg)
      }

    })
  }

  onSearch(event:any){
   let value = event?.target?.value;
   this.searchValue = value?value:"";
   if(this.searchTimeOut){clearInterval(this.searchTimeOut)}
   setTimeout(()=>{
    this.joinedPeople = [];
    this.getCarPoolList();
   }, 500)
  }

  viewJoinedPeople(index:any={}){
    this.trips.forEach((ele:any)=>{
      ele.showChild = false;
    })

    this.trips[index].showChild = true;

  }
}
