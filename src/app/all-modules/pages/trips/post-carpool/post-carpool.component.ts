import { Component, OnInit} from '@angular/core';
import { FormGroup,FormBuilder, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { UserService } from 'src/app/core/services';
import { DataService } from 'src/app/shared/services';
import { Subscription } from 'rxjs';

@Component({
  selector: 'app-post-carpool',
  templateUrl: './post-carpool.component.html',
  styleUrls: ['./post-carpool.component.css']
})
export class PostCarpoolComponent {
  postTripForm:FormGroup;
  
  submited: boolean; 
  isLoading:boolean; 
  updateError = ''; 
  updateSuccess = '';
  userId:any ='';
  userDetails:any={};
  userSubscription:Subscription;  
  cities:any=[];

  constructor(private fb: FormBuilder, private userService: UserService,  private dataService:DataService, private router:Router) { }
  ngOnInit(): void {
    this.userId = localStorage.getItem('userId');
    this.postTripForm = this.fb.group({
          student_id:[this.userId, Validators.required],
          location :['', Validators.required], 
          driving_experience :['', Validators.required], 
          smoking_habit:['', Validators.required],
          consumed_alcohol:['', Validators.required],
          no_of_passengers:['', Validators.required],
          car_description:['', Validators.required],
          leave_notes:['', [Validators.required]],
    });
    this.userDetails = this.dataService.getUserDetails().subscribe((res:any)=>{
      if(res){
        this.userDetails = res;
        if(this.userDetails?.country_id){
          // this.getCities();
        }
      }
    })
  }

  ngOnDestroy(){
    if(this.userSubscription){this.userSubscription.unsubscribe()}
  }

  getCities(){
    this.cities = [];
    let data = new FormData();
    data.append('country_id', this.userDetails?.country_id);
    this.userService.getCities(data).subscribe((res:any)=>{
      this.cities = res?.data?res?.data:[];
    }); 
  }

  get getControls(){
    return this.postTripForm.controls;
  }

  

  postTrip(){
    this.updateError   = "";
    this.updateSuccess = "";
    this.isLoading     = true;
    this.submited      = true;
    console.log('this.postTripForm', this.postTripForm.value)
    if(this.postTripForm.invalid){
      this.updateError = "All fields are required*";
      this.postTripForm.markAllAsTouched();
      this.isLoading = false;
      return;
    }

    let value = this.postTripForm.value;
    let keys = Object.keys(value);

    let data = new FormData();
    keys.forEach(key=>{
      data.append(key, value[key]);
    })

    this.userService.postPartyCarPool(data).subscribe((res:any)=>{
      if(res?.status == '200'){
        this.updateSuccess = "Party Carpool post added successfully...";
        setTimeout(()=>{
          this.router.navigate(['/trips/party-car-pool-list']);
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
}
