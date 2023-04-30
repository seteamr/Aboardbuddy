import { Injectable } from '@angular/core';
import { endPoints } from 'src/app/core/config';
import { ApiService } from 'src/app/shared/services';
@Injectable({
  providedIn: 'root'
})
export class UserService {
  endPoints = endPoints.users; // fetch all endpoints;
  dataEndpoint = endPoints.data; // fetch all endpoints;

  constructor(private api: ApiService) { }

  signup(req:any={}){
    return this.api.post(this.endPoints.signup, req);
  }

  login(req:any={}){
    return this.api.post(this.endPoints.login, req);
  }

  getCountries(req:any={}){
    return this.api.get(this.dataEndpoint.countries, req);
  }
  getCities(req:any={}){
    return this.api.post(this.dataEndpoint.cities, req);
  }

  getUniversities(req:any={}){
    return this.api.post(this.dataEndpoint.universities, req);
  }

  getProfile(req:any={}){
    return this.api.post(this.endPoints.profile, req);
  }

  updateProfile(req:any={}){
    return this.api.post(this.endPoints.updateProfile, req);
  }

  updateSellerProfile(req:any={}){
    return this.api.post(this.endPoints.updateSellerProfile, req);
  }

  postTrip(req:any={}){
    return this.api.post(this.endPoints.postTrip, req);
  }

  postPartyCarPool(req:any={}){
    return this.api.post(this.endPoints.postPartyCarpool, req);
  }

  getPartyCarPoolList(req:any={}){
    return this.api.post(this.endPoints.partyCarpoolList, req);
  }

  joinPartyCarPool(req:any={}){
    return this.api.post(this.endPoints.joinPartyCarPool, req);
  }

  getTrips(req:any={}){
    return this.api.post(this.endPoints.getTrips, req);
  }
  sellerSignup(req:any={}){
    return this.api.post(this.endPoints.selelrSignup, req);
  }

  deletePartyCarPool(req:any={}){
    return this.api.post(this.endPoints.deletePartyCarPool, req);
  }

  contactUs(req:any={}){
    return this.api.post(this.endPoints.contactUs, req);
  }
}
