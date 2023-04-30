import { Component } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-auth-header',
  templateUrl: './auth-header.component.html',
  styleUrls: ['./auth-header.component.css']
})
export class AuthHeaderComponent {
  user:any='';
  username:any='';

  constructor(private router:Router){}
  ngOnInit(){
   this.user = localStorage.getItem('userId');
   this.username = localStorage.getItem('username');

   console.log('this.user', this.user)


    $(document).ready(function(){
      $("#nav-toggle1").click(function(e) {
        e.preventDefault();
        $("#navigation1").toggleClass("navigation-portrait");
    });});
  }

  logout(){
    this.user ='';
    localStorage.clear();
    this.router.navigate(['/auth/login'])
  }
}
