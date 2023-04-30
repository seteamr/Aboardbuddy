import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { catchError } from 'rxjs/operators';
import { throwError } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ApiService {
  // baseUrl = env.baseUrl;

  constructor(private http: HttpClient,) { }
  post(endpoint: string, req: any) {
    let param = new HttpParams();
    param = req;
    return this.http.post(endpoint, param).pipe(
      // retry(1),
      catchError(this.errorHandl)
    );
  }

  get(endpoint: string, req:any ={}) {
    let param = new HttpParams();
    param = req;
    return this.http.get(endpoint, {params:param});
  }

  customGet(endpoint: string, req:any ={}) {
    let param = new HttpParams();
    param = req;
    return this.http.get(endpoint, {params:param, responseType:'text'});
  }

  delete(endpoint: string, req:any={}) {
    // let param = new HttpParams();
    let param = req;
    return this.http.delete(endpoint, {body:param} );
  }

  put(endpoint: string, req: any, headers?: any) {
    let param = new HttpParams();
    param = req;
    return this.http.put(endpoint, param, headers)
  }

  patch(endpoint: string, req: any) {
    let param = new HttpParams();
    param = req;
    return this.http.patch(endpoint, param)
  }

  fetch(endpoint: string, req: any) {
    let param = new HttpParams();
    param = req;
    return this.http.put(endpoint, param)
  }

  // Error handling
  errorHandl(error: any) {
    let errorMessage = '';
    if (error.error instanceof ErrorEvent) {

      // Get client-side error
      errorMessage = error.error.message;
    } else {

      // Get server-side error
      errorMessage = error;
      if (error.status === 401) {
        // localStorage.clear();
      }
    }
    return throwError(errorMessage);
  }

}
