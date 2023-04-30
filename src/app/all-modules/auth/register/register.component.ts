import { Component, OnInit } from '@angular/core';
import { FormGroup,FormBuilder, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { UserService } from 'src/app/core/services';
@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})
export class RegisterComponent  implements OnInit {
  registerForms:FormGroup;
  countries:any = [];
  cities:any = [];
  universities:any = [];
  submited: boolean; 
  isLoading:boolean; 
  signupError = ''; 
  signUpSuccess = '';
  constructor(private fb: FormBuilder, private userService: UserService, private router:Router) { }
  ngOnInit(): void {
    this.registerForms = this.fb.group({
          username:['', Validators.required],
          email:['', [Validators.required, Validators.email]],
          phone:['', Validators.required],
          gender:['', Validators.required],
          age:['', Validators.required],
          dob:['', Validators.required],
          password:['',[Validators.required]],
          cpassword:['', Validators.required],
          country_id:['', Validators.required],
          city_id:['', Validators.required],
          university_id:['', Validators.required],
          
          // country_code:['+91', Validators.required],
    });

    this.getCountries();
  }

  onSubmit(){

    this.signupError = "";
    this.signUpSuccess = "";
    this.isLoading = true;
    this.submited = true;
    if(this.registerForms.invalid){
      this.signupError = "All fields are required*";
      this.registerForms.markAllAsTouched();
      this.isLoading = false;
      return;
    }

    if(this.registerForms.value.password  != this.registerForms.value.cpassword){
      this.signupError = "Password and confirm password should be same";
      this.isLoading = false;
      this.registerForms.markAllAsTouched();
      return;
    }
    
    let value = this.registerForms.value;
    let data = new FormData();
    data.append('username', value.username);
    data.append('email', value.email);
    data.append('phone', value.phone);
    data.append('gender', value.gender);
    data.append('age', value.age);
    data.append('dob', value.dob);
    data.append('password', value.password);
    data.append('cpassword', value.cpassword);
    data.append('country_id', value.country_id);
    data.append('city_id', value.city_id);
    data.append('university_id', value.university_id);

    this.userService.signup(data).subscribe((res:any)=>{
      if(res?.status == '200'){
        let userId = res?.data?.student_id?res?.data?.student_id:res?.data?.username;
        localStorage.setItem('userId', userId);
        localStorage.setItem('username',res?.data?.username);
        this.signUpSuccess = "You have registered successfully. Redirecting...";
        setTimeout(()=>{
            this.router.navigate(['/accounts/profile']);
           this.isLoading = false;
        }, 1500)

      }else{
        this.isLoading = false;
        this.signupError = res?.message?res?.message:'Something went wrong, try again';
      }
    }, error=>{
      this.signupError = 'Something went wrong, try again';
      this.isLoading = false;
    })

  }
  
  get getControls(){
    return this.registerForms.controls;
  }

  
  getCountries(){
    this.userService.getCountries().subscribe((res:any)=>{
      this.countries = res?.data?res?.data:[];
      
    })
  }

  onChangeCountry(){
    this.registerForms.get('city_id')?.patchValue('');
    this.registerForms.get('university_id')?.patchValue('');
    this.cities = [];
    this.universities = [];

    let data = new FormData();
    data.append('country_id', this.registerForms.get('country_id')?.value);
    this.userService.getCities(data).subscribe((res:any)=>{
      this.cities = res?.data?res?.data:[];
    }); 
  }

  onChangeCity(){
    this.registerForms.get('university_id')?.patchValue('');
    this.universities = [];

     let data = new FormData();
     data.append('city_id', this.registerForms.get('city_id')?.value);
     this.userService.getUniversities(data).subscribe((res:any)=>{
       this.universities = res?.data?res?.data:[];
     }); 
  }
}
