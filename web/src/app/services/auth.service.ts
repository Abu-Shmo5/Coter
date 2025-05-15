import { Injectable } from '@angular/core';
import { environment } from '../environment/environment';
import { HttpService } from './http.service';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  constructor(private http: HttpService) { }

  login(username: string, password: string) {
    return this.http.post(`${environment.ApiUrl}account.php`, `action=login&username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`, 
    {headers: {"content-type": "application/x-www-form-urlencoded"}, observe: 'events'})
  }

  register(username: string, password: string) {

  }

  checkToken(jwtToken: string) {
    return this.http.post(`${environment.ApiUrl}account.php`, `action=check_jwt&token=${encodeURIComponent(jwtToken)}`, 
    {headers: {"content-type": "application/x-www-form-urlencoded"}, observe: 'events'})
  }
}
