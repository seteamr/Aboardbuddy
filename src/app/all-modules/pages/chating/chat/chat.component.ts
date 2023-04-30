import { Component } from '@angular/core';
import { ChatService } from 'src/app/core/services';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-chat',
  templateUrl: './chat.component.html',
  styleUrls: ['./chat.component.css']
})
export class ChatComponent {
  
  userId:any="";
  selectedUserId:any ='';
  requests:any=[];
  contacts:any=[];
  messages:any=[];
  messageText:any;
  messageInterval:any;
  constructor(private chatService:ChatService, private router:Router, private route:ActivatedRoute,){
    this.route.queryParams.subscribe(param=>{
      this.selectedUserId = param?.selectedUserId;
    })
  }

  ngOnInit(): void {
    this.userId = localStorage.getItem('userId');
    this.getChatRequests();
    this.getConnections();
    if(this.selectedUserId){
      this.getMessages();
    }
  }

  ngOnDestroy(){
    clearInterval(this.messageInterval);
  }
  getChatRequests(){
    let data = new FormData();
    data.append('my_id', this.userId)
    this.chatService.getRequests(data).subscribe((res:any)=>{
      this.requests = res?.data?res?.data:[];
      // this.contacts = this.requests;

    })
  }

  getConnections(){
    let data = new FormData();
    data.append('my_id', this.userId)
    this.chatService.getContacts(data).subscribe((res:any)=>{
      this.contacts = res?.data?res?.data:[];
    })
  }


  getMessages(clearMessageInterval:boolean=true){
    let data = new FormData();
    data.append('from_id', this.userId)
    data.append('to_id', this.selectedUserId)
    // console.log("fromId="+this.userId+", to_id="+this.selectedUserId);
   
    this.chatService.getMessage(data).subscribe((res:any)=>{
      if(clearMessageInterval){
        clearInterval(this.messageInterval);
        this.setMessageInterval();
      }
      this.messages = res?.data?res?.data:[];
    })
  }

  setMessageInterval(){
    this.messageInterval = setInterval(()=>{
      this.getMessages(false);
     }, 2500)

  }


  messageIdentity(index:any, item:any){
    return item.id; 
 }
  sendMessage(){
    let data = new FormData();
    data.append('from_id', this.userId)
    data.append('to_id', this.selectedUserId)
    data.append('message', this.messageText)
    this.chatService.sendMessage(data).subscribe((res:any)=>{
      this.messageText = "";
      this.getMessages();
    })
  }

  acceptRejectRequest(id:any, action:string){
    let data = new FormData();
    data.append('id', id)
    data.append('status', action)
    this.chatService.acceptRejectRequest(data).subscribe((res:any)=>{
      this.getChatRequests();
    })
  }
  
  onChangeContact(contact:any){
    console.log('contact', contact)
      this.selectedUserId = contact?.from_id==this.userId?contact?.to_id:contact?.from_id;
      if(this.selectedUserId){
        this.getMessages();
      }
  }
}
