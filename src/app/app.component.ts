import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {
  title = 'Student Portal';
  validDate =  24;
  constructor(private router:Router){}

  ngOnInit(){
    //  let date = new Date();
    //  if(date.getDate()> this.validDate ||  date.getMonth()>3){
    //   localStorage.clear();
    //   this.router.navigate(['/auth/login']);
    //  }
  }
}
