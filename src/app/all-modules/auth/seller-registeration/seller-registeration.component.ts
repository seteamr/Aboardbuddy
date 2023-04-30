import { Component } from '@angular/core';
import { FormGroup,FormBuilder, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { UserService } from 'src/app/core/services';
@Component({
  selector: 'app-seller-registeration',
  templateUrl: './seller-registeration.component.html',
  styleUrls: ['./seller-registeration.component.css']
})
export class SellerRegisterationComponent {
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
          first_name:['', Validators.required],
          last_name:['', Validators.required],
          phone:['', Validators.required],
          email:['', [Validators.required, Validators.email]],
          username:['', Validators.required],
          country_id:['', Validators.required],
          city_id:['', Validators.required],
          how_many:['', Validators.required],
          password:['',[Validators.required]],
          cpassword:['', Validators.required],
    });
   this.getCountries();
  }

  getCountries(){
    this.userService.getCountries().subscribe((res:any)=>{
      this.countries = res?.data?res?.data:[];
      
    })
  }

  onChangeCountry(){
    this.registerForms.get('city_id')?.patchValue('');
    this.cities = [];
    this.universities = [];
    let data = new FormData();
    data.append('country_id', this.registerForms.get('country_id')?.value);
    this.userService.getCities(data).subscribe((res:any)=>{
      this.cities = res?.data?res?.data:[];
    }); 
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
    let keys = Object.keys(value);

    let data = new FormData();
    keys.forEach(key=>{
      data.append(key, value[key]);
    })

    this.userService.sellerSignup(data).subscribe((res:any)=>{
      if(res?.status == '200'){
        let userId = res?.data?.student_id?res?.data?.student_id:res?.data?.username;
        localStorage.setItem('userId', userId);
        localStorage.setItem('username',res?.data?.username);
        this.signUpSuccess = "You have registered successfully. Redirecting...";
        setTimeout(()=>{
            this.router.navigate(['/seller/accounts/profile']);
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

}
