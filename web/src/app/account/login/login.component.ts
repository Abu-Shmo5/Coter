import { Component } from '@angular/core';
import { AuthService } from '../../services/auth.service';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-login',
  imports: [FormsModule],
  templateUrl: './login.component.html',
  styleUrl: './login.component.scss'
})
export class LoginComponent {

  username: string = ""
  password: string = ""

  constructor(private auth: AuthService) {}

  login() {
    console.log(this.username, this.password)
    this.auth.login(this.username, this.password)
  }
}
