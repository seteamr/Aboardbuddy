import { Component, OnInit} from '@angular/core';
import { FormGroup,FormBuilder, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { UserService } from 'src/app/core/services';
import { DataService } from 'src/app/shared/services';
import { Subscription } from 'rxjs';

@Component({
  selector: 'app-post-trip',
  templateUrl: './post-trip.component.html',
  styleUrls: ['./post-trip.component.css']
})
export class PostTripComponent {
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
          trip_from :['', Validators.required], 
          trip_to :['', Validators.required], 
          trip_date:['', Validators.required],
          trip_time:['', Validators.required],
          no_of_passanger:['', Validators.required],
          smoker:['', Validators.required],
          licend:['', [Validators.required]],
          description:['', Validators.required],
          notes:['',Validators.required],
    });
    this.userDetails = this.dataService.getUserDetails().subscribe((res:any)=>{
      if(res){
        this.userDetails = res;
        if(this.userDetails?.country_id){
          this.getCities();
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

    this.userService.postTrip(data).subscribe((res:any)=>{
      if(res?.status == '200'){
        this.updateSuccess = "Trip added successfully...";
        setTimeout(()=>{
          this.router.navigate(['/trips/listing']);
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
