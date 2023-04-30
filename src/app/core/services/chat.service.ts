import { Injectable } from '@angular/core';
import { ApiService } from 'src/app/shared/services';
import { endPoints } from '../config';

@Injectable({
  providedIn: 'root'
})
export class ChatService {

  endPoints = endPoints.chat; // fetch all endpoints;

  constructor(private api: ApiService) { }

  sendChatRequest(req:any={}){
    return this.api.post(this.endPoints.sendRequest, req);
  }

  getRequests(req:any={}){
    return this.api.post(this.endPoints.getRequests, req);
  }

  getContacts(req:any={}){
    return this.api.post(this.endPoints.getContacts, req);
  }

  acceptRejectRequest(req:any={}){
    return this.api.post(this.endPoints.acceptRejectReq, req);
  }

  sendMessage(req:any={}){
    return this.api.post(this.endPoints.sendMessage, req);
  }

  getMessage(req:any={}){
    return this.api.post(this.endPoints.getMessages, req);
  }

  getMessageDetails(req:any={}){
    return this.api.post(this.endPoints.messageDetails, req);
  }
}
