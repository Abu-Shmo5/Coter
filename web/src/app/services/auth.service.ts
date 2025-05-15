import { Injectable } from '@angular/core';
import { environment } from '../environment/environment';
import { HttpService } from './http.service';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  constructor(private http: HttpService) { }

  login(username: string, password: string) {
    this.http.post(`${environment.ApiUrl}account.php`, `action=login&username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`, 
    {headers: {"content-type": "application/x-www-form-urlencoded"}, observe: 'events'}).subscribe({next: (result: any) => {
      return result
    }, error: (error: any) => {
      return error
    }})
  }

  register(username: string, password: string) {

  }

  checkToken(jwtToken: string) {

  }
}
