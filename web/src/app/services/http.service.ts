import { HttpClient, HttpContext, HttpEvent, HttpHeaders, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

interface interfaceHeaders {
  Auth?: string
  "Content-Type"?: string
}

@Injectable({
  providedIn: 'root'
})
export class HttpService {

  constructor(private http: HttpClient) { }

  get<T>(url: string, options: { headers?: HttpHeaders | { [header: string]: string | string[]; }; observe: "events"; context?: HttpContext; params?: HttpParams | { [param: string]: string | number | boolean | ReadonlyArray<string | number | boolean>; }; reportProgress?: boolean; responseType?: "json"; withCredentials?: boolean; transferCache?: { includeHeaders?: string[]; } | boolean; }): Observable<HttpEvent<T>> {
    return this.http.get<T>(url, options)
  }
  
  post<T>(url: string, body: any | null, options: { headers?: HttpHeaders | { [header: string]: string | string[]; }; observe: "events"; context?: HttpContext; params?: HttpParams | { [param: string]: string | number | boolean | ReadonlyArray<string | number | boolean>; }; reportProgress?: boolean; responseType?: "json"; withCredentials?: boolean; transferCache?: { includeHeaders?: string[]; } | boolean; }): Observable<HttpEvent<T>> {
    return this.http.post<T>(url, body, options)
  }

}