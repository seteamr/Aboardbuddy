import { Component, OnInit} from '@angular/core';
import { FormGroup,FormBuilder, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { UserService } from 'src/app/core/services';
import { DataService } from 'src/app/shared/services';
@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.css']
})
export class ProfileComponent implements OnInit {
  profileForm:FormGroup;

  submited: boolean; 
  isLoading:boolean; 
  updateError = ''; 
  updateSuccess = '';
  userId:any ='';
  countries:any = [];
  cities:any = [];
  universities:any = [];
  constructor(private fb: FormBuilder, private userService: UserService, private router:Router, private dataService:DataService) { }
  ngOnInit(): void {
    this.userId = localStorage.getItem('userId');
    this.profileForm = this.fb.group({
          student_id:[this.userId],
          first_name:['', Validators.required],
          last_name:['', Validators.required],
          phone:['', Validators.required],
          email:['', [Validators.required, Validators.email]],
          country_id :[''], 
          city_id :[''], 
          profile_pic:['',],
          how_many:['', Validators.required],
    });
    
    this.getProfile();
  }

  userDetails:any = {};
  profileImgUrl:any = "http://dairysystem.in/Buddy/admin/uploads/student/no_image.png";
   // Image Preview
   onChangeImage(event:any) {
    const reader = new FileReader();
    
    if(event.target.files && event.target.files.length) {
      const [file] = event.target.files;
      reader.readAsDataURL(file);
      reader.onload = () => {
   
        this.profileImgUrl = reader.result as string;
        this.profileForm.get('profile_pic')?.patchValue(event.target.files[0])
      };
   
    }
  }
  getProfile(){
    let data = new FormData();
    data.append('student_id', this.userId)
    this.userService.getProfile(data).subscribe((res:any)=>{
     this.userDetails = res?.data?res?.data:{};
     this.profileForm.patchValue(this.userDetails);
     this.profileImgUrl = this.userDetails?.profile_pic?this.userDetails?.profile_pic:this.profileImgUrl;  
     this.getCountries(true);
     this.onChangeCountry(true);
     this.onChangeCity(true);
    })
  }


  updateProfile(){
    this.updateError = "";
    this.updateSuccess = "";
    this.isLoading = true;
    this.submited = true;

    if(this.profileForm.invalid){
      this.updateError = "All fields are required*";
      this.profileForm.markAllAsTouched();
      this.isLoading = false;
      return;
    }

    
    let value = this.profileForm.value;
    let keys = Object.keys(value);

    let data = new FormData();
    keys.forEach(key=>{
      data.append(key, value[key]);
    })

    this.userService.updateSellerProfile(data).subscribe((res:any)=>{
      if(res?.status == '200'){
        this.dataService.getUpdatedUserInfo();
        this.updateSuccess = "Profile updated successfully. Redirecting...";
        setTimeout(()=>{
            this.router.navigate(['/seller/advertise/post-add']);
           this.isLoading = false;
        }, 1500)

      }else{
        this.isLoading = false;
        this.updateError = res?.message?res?.message:'Something went wrong, try again';
      }
    }, error=>{
      this.updateError = 'Something went wrong, try again';
      this.isLoading = false;
    })

  }
  
  get getControls(){
    return this.profileForm.controls;
  }

  getCountries(isEdit:boolean = false){
    this.userService.getCountries().subscribe((res:any)=>{
      this.countries = res?.data?res?.data:[];
    })
  }

  onChangeCountry(isEdit:boolean = false){
    if(!isEdit){
      this.profileForm.get('city_id')?.patchValue('');
      this.profileForm.get('university_id')?.patchValue('');
      this.universities = [];
    }
    this.cities = [];
    let data = new FormData();
    data.append('country_id', this.profileForm.get('country_id')?.value);
    this.userService.getCities(data).subscribe((res:any)=>{
      this.cities = res?.data?res?.data:[];
    }); 
  }

  onChangeCity(isEdit:boolean = false){
    if(!isEdit){
      this.profileForm.get('university_id')?.patchValue('');
      this.universities = [];
    }
     let data = new FormData();
     data.append('city_id', this.profileForm.get('city_id')?.value);
     this.userService.getUniversities(data).subscribe((res:any)=>{
       this.universities = res?.data?res?.data:[];
     }); 
  }


}
