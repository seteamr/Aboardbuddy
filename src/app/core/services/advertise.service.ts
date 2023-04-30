import { Injectable } from '@angular/core';
import { ApiService } from 'src/app/shared/services';
import { endPoints } from '../config';

@Injectable({
  providedIn: 'root'
})
export class AdvertiseService {
  endPoints = endPoints.advertise; // fetch all endpoints;

  constructor(private api: ApiService) { }

  postAd(req:any={}){
    return this.api.post(this.endPoints.postAd, req);
  }

  getMyAds(req:any={}){
    return this.api.post(this.endPoints.getAds, req);
  }

  getAdByCity(req:any={}){
    return this.api.post(this.endPoints.addByCity, req);
  }

  getAdDetails(req:any={}){
    return this.api.post(this.endPoints.getAdDetails, req);
  }

  reportAdAsSpam(req:any={}){
    return this.api.post(this.endPoints.reportAdAsSpam, req);
  }

}
