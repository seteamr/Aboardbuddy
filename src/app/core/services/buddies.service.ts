import { Injectable } from '@angular/core';
import { endPoints } from 'src/app/core/config';
import { ApiService } from 'src/app/shared/services';
@Injectable({
  providedIn: 'root'
})
export class BuddiesService {

  endPoints = endPoints.buddies; // fetch all endpoints;

  constructor(private api: ApiService) { }

  findBuddies(req:any={}){
    return this.api.post(this.endPoints.findBuddies, req);
  }

  home(req:any={}){
    return this.api.post(endPoints.home, req);
  }
}
