import { Component, OnInit} from '@angular/core';
import { FormGroup,FormBuilder, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { AdvertiseService, UserService } from 'src/app/core/services';
import { DataService } from 'src/app/shared/services';
import { Subscription } from 'rxjs';

@Component({
  selector: 'app-post-add',
  templateUrl: './post-add.component.html',
  styleUrls: ['./post-add.component.css']
})
export class PostAddComponent implements OnInit {
  addForm:FormGroup;
  
  submited: boolean; 
  isLoading:boolean; 
  updateError = ''; 
  updateSuccess = '';
  userId:any ='';

  propertyTypes = [{id:"Accommodation", value:"Accommodation"}, {id:"Furniture", value:"Furniture"}, {id:"Electronics", value:"Electronics"}, {id:"Vehicles", value:"Vehicles"}]
  dealTypes = [{id:"Sell", value:"Sell"}, {id:"Lease", value:"Lease"}]
  propertyImgUrl:any = "http://dairysystem.in/Buddy/admin/uploads/student/no_image.png";
  propertyImages:any =[];

  constructor(private fb: FormBuilder, private adService: AdvertiseService,  private router:Router) { }
  
  ngOnInit(): void {
    this.userId = localStorage.getItem('userId');
    this.addForm = this.fb.group({
          student_id:[this.userId, Validators.required],
          property_type :['', Validators.required], 
          select_option :['', Validators.required], 
          description:['', Validators.required],
          image:['',],
    });
  }

  // Image Preview
  onChangeImage(event:any) {
    const reader = new FileReader();
    
    if(event.target.files && event.target.files.length) {
      const [file] = event.target.files;
      reader.readAsDataURL(file);
      reader.onload = () => {
        this.propertyImages.push({url:reader.result as string, file:event.target.files[0] })
        // this.propertyImgUrl = reader.result as string;
        // this.addForm.get('image')?.patchValue(event.target.files[0])
      };
    }
  }

  get getControls(){
    return this.addForm.controls;
  }

  

  onSubmit(){
    this.updateError   = "";
    this.updateSuccess = "";
    this.isLoading     = true;
    this.submited      = true;
    if(this.addForm.invalid){
      this.updateError = "All fields are required*";
      this.addForm.markAllAsTouched();
      this.isLoading = false;
      return;
    }

    let value = this.addForm.value;
    let keys = Object.keys(value);

    let data = new FormData();
    keys.forEach(key=>{
      if(key != "image"){
        data.append(key, value[key]);
      }
    })
    this.propertyImages.forEach((file:any)=>{
      data.append("images[]", file?.file);
    })
    
    this.adService.postAd(data).subscribe((res:any)=>{
      if(res?.status == '200'){
        this.updateSuccess = "Ad posted successfully...";
        setTimeout(()=>{
          this.router.navigate(['/seller/advertise/my-adds']);
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

  removeImg(i=0){
   this.propertyImages.splice(i, 1);
  }
}
