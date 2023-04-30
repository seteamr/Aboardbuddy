import { Component, OnInit } from '@angular/core';
import { Route, Router } from '@angular/router';
import * as $ from 'jquery'
import { DataService } from 'src/app/shared/services';
import { Subscription } from 'rxjs';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit{
  userSubscription:Subscription;  
  username:any='';
  userDetails:any={};
  constructor(private router:Router, private dataService:DataService){}
  ngOnInit(){
   
   this.username = localStorage.getItem('username');
    $(document).ready(function(){
      $("#nav-toggle1").click(function(e) {
        e.preventDefault();
        $("#navigation1").toggleClass("navigation-portrait");
    });});

    this.dataService.getUpdatedUserInfo();
    this.userDetails = this.dataService.getUserDetails().subscribe((res:any)=>{
      if(res){

        this.userDetails = res;
        this.userDetails.isStudent = res?.role=='seller'?false:true;
      }
    })
  }
  ngOnDestroy(){
    if(this.userSubscription){this.userSubscription.unsubscribe()}
  }

  logout(){
    this.dataService.setUserDetails("");
    localStorage.clear();
    this.router.navigate(['/auth/login'])
  }



}
