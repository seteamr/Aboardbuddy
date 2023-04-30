import { Component,OnInit } from '@angular/core';
import { FormGroup,FormBuilder, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { UserService } from 'src/app/core/services';
import { DataService } from 'src/app/shared/services';
@Component({
  selector: 'app-support',
  templateUrl: './support.component.html',
  styleUrls: ['./support.component.css']
})
export class SupportComponent {
  contactUsForms:FormGroup;
  submited: boolean; 
  isLoading:boolean; 
  errorMsg = ''; 
  successMsg = '';
  userDetails:any = {}; 
  userSubscription:any={};
  constructor(private fb: FormBuilder, private userService: UserService, private router:Router, private dataService:DataService) { }
  ngOnInit(): void {
    this.contactUsForms = this.fb.group({
          name:['', Validators.required],
          email:['', [Validators.required, Validators.email]],
          phone_no:['', Validators.required],
          message:['', Validators.required],          
    });

    this.userSubscription = this.dataService.getUserDetails().subscribe((res:any)=>{
      if(res){
        this.userDetails = res;
        console.log('this.userDetails', this.userDetails)
      }
    })

  }

  ngOnDestroy(){
    if(this.userSubscription){this.userSubscription.unsubscribe()}
  }

  onSubmit(){
    this.errorMsg = "";
    this.successMsg = "";
    this.isLoading = true;
    this.submited = true;
    if(this.contactUsForms.invalid){
      this.errorMsg = "All fields are required*";
      this.contactUsForms.markAllAsTouched();
      this.isLoading = false;
      return;
    }

    
    let value = this.contactUsForms.value;
    let keys = Object.keys(value);

    let data = new FormData();
    keys.forEach(key=>{
      data.append(key, value[key]);
    })

    this.userService.contactUs(data).subscribe((res:any)=>{
      if(res?.status == '200'){
        this.successMsg = "Your request submited successfully. Redirecting...";
        setTimeout(()=>{
           let url =['/'];
           if(this.userDetails?.role !=="student"){
            url =['/seller/advertise/post-add'];
           }
            this.router.navigate(url);
           this.isLoading = false;
        }, 1500)

      }else{
        this.isLoading = false;
        this.errorMsg = res?.message?res?.message:'Something went wrong, try again';
      }
    }, error=>{
      this.errorMsg = 'Something went wrong, try again';
      this.isLoading = false;
    })

  }
  
  get getControls(){
    return this.contactUsForms.controls;
  }

}
