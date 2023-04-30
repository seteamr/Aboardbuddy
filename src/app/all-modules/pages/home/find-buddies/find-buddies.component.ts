import { Component, OnInit } from '@angular/core';
import { FormGroup,FormBuilder, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { UserService } from 'src/app/core/services';

@Component({
  selector: 'app-find-buddies',
  templateUrl: './find-buddies.component.html',
  styleUrls: ['./find-buddies.component.css']
})
export class FindBuddiesComponent implements OnInit {
  submited: boolean =false; 
  buddieFilterform:FormGroup;
  countries:any = [[{country_id:1, name:'test'}]];
  cities:any = [];
  universities:any = [];
  userId:any;
  constructor(private fb: FormBuilder, private userService: UserService, private router:Router) { }
  ngOnInit(): void {
    this.userId = localStorage.getItem('userId')
    this.buddieFilterform = this.fb.group({
          student_id:[this.userId],
          country_id:['', Validators.required],
          city_id:['', Validators.required],
          university_id:['', Validators.required],
    });

    this.getCountries();
  }

  get getControls(){
    return this.buddieFilterform.controls;
  }
  getCountries(){
    this.userService.getCountries().subscribe((res:any)=>{
      this.countries = res?.data?res?.data:[];
      
    })
  }

  onChangeCountry(){
    this.buddieFilterform.get('city_id')?.patchValue('');
    this.buddieFilterform.get('university_id')?.patchValue('');
    this.cities = [];
    this.universities = [];

    let data = new FormData();
    data.append('country_id', this.buddieFilterform.get('country_id')?.value);
    this.userService.getCities(data).subscribe((res:any)=>{
      this.cities = res?.data?res?.data:[];
    }); 
  }

  onChangeCity(){
    this.buddieFilterform.get('university_id')?.patchValue('');
    this.universities = [];

     let data = new FormData();
     data.append('city_id', this.buddieFilterform.get('city_id')?.value);
     this.userService.getUniversities(data).subscribe((res:any)=>{
       this.universities = res?.data?res?.data:[];
     }); 
  }
  isLoading:boolean;
  error = '';
  onClickFindBuddies(){
    this.isLoading = true;
    this.submited = true;
    if(this.buddieFilterform.invalid){
      this.error = "All fields are required*";
      this.buddieFilterform.markAllAsTouched();
      this.isLoading = false;
      return;
    }
    
    this.router.navigate(["/home/buddies/list"], {queryParams:this.buddieFilterform.value})
  }

}
