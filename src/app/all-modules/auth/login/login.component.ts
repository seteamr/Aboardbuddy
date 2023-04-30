import { Component, OnInit } from '@angular/core';
import { FormGroup,FormBuilder, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { UserService } from 'src/app/core/services';
@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent  implements OnInit {
  loginForms:FormGroup;
  submited: boolean; 
  isLoading:boolean;
  loginError = ''; 
  loginSuccess = '';
  constructor(private fb: FormBuilder, private userService: UserService, private router:Router) { }
  ngOnInit(): void {
    this.loginForms = this.fb.group({
          username:['', Validators.required],
          password:['',[Validators.required]],
    });
  }

  onSubmit(){
    this.loginError = "";
    this.loginSuccess = "";
    this.isLoading = true;
    this.submited = true;
    if(this.loginForms.invalid){
      this.loginError = "Username and password required";
      this.loginForms.markAllAsTouched();
      this.isLoading = false;
      return;
    }

    let value = this.loginForms.value;
    let data = new FormData();
    data.append('username', value.username);
    data.append('password', value.password);

    this.userService.login( data).subscribe((res:any)=>{
      if(res?.status == '200'){
        localStorage.setItem('userId',res?.data?.student_id);
        localStorage.setItem('username',res?.data?.username);
        this.loginSuccess = "Signedin successfully. Redirecting...";
        setTimeout(()=>{
           this.isLoading = false;

           let url = res?.data?.role=='seller'?['/seller/accounts/profile']:['/accounts/profile'];
           if(res?.data?.first_name){
            url = res?.data?.role=='seller'?['/seller/accounts/profile']:['/home/select-university'];
           }
           this.router.navigate(url);
        }, 1500)

      }else{
        this.isLoading = false;
        this.loginError = res?.message?res?.message:'Something went wrong, try again';
      }
    }, error=>{
      this.loginError = 'Something went wrong, try again';
      this.isLoading = false;
    })

  }
  
  get getControls(){
    return this.loginForms.controls;
  }

}
