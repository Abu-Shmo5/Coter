import { Component } from '@angular/core';
import { AuthService } from '../../services/auth.service';
import { FormsModule } from '@angular/forms';
import { HttpResponse, HttpResponseBase } from '@angular/common/http';
import { LocalstorageService } from '../../services/localstorage.service';
import { environment } from '../../environment/environment';

@Component({
  selector: 'app-login',
  imports: [FormsModule],
  templateUrl: './login.component.html',
  styleUrl: './login.component.scss'
})
export class LoginComponent {

  username: string = ""
  password: string = ""

  constructor(private auth: AuthService, private localstorage: LocalstorageService) {}

  login() {
    let response = this.auth.login(this.username, this.password)
    response.subscribe({next: (result: any) => {
      if (result['status'] == 200) {
        if (result.body['Status'] == "Worked") {
          this.localstorage.setItem(environment.tokenStorageName, result.body['Message'])
          return
        }
        alert(result.body['Message'])
      }
    }, error: (error: any) => {
      console.log(error)
    }})
  }
}
