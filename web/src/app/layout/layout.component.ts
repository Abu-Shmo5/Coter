import { CommonModule } from '@angular/common';
import { Component, Injector, OnInit } from '@angular/core';
import { RouterLink, RouterOutlet } from '@angular/router';
import { environment } from '../environment/environment';
import type { LocalstorageService } from '../services/localstorage.service';

@Component({
  selector: 'app-layout',
  imports: [RouterLink, RouterOutlet, CommonModule],
  templateUrl: './layout.component.html',
  styleUrl: './layout.component.scss'
})
export class LayoutComponent implements OnInit {

  isLoading = true
  isAuth = false
  localstorageService!: LocalstorageService

  constructor(private injector: Injector) {}

  async ngOnInit() {
    const { LazyloaderService } = await import('../services/lazyloader.service');
    const lazyLoader = this.injector.get(LazyloaderService);
    lazyLoader.loadCSS('/static/css/bootstrap.min.css');
    lazyLoader.loadScript('/static/js/bootstrap.bundle.min.js');

    const { PlatformserviceService } = await import('../services/platformservice.service');
    const platformserviceService = this.injector.get(PlatformserviceService);

    const { LocalstorageService } = await import('../services/localstorage.service');
    this.localstorageService = this.injector.get(LocalstorageService);

    const { HttpService } = await import('../services/http.service');
    const httpService = this.injector.get(HttpService);

    const { AuthService } = await import('../services/auth.service');
    const authService = this.injector.get(AuthService);

    const { ThemeService } = await import('../services/theme.service');
    const themeService = this.injector.get(ThemeService);
    
    const { TranslationService } = await import('../services/translation.service');
    const translationService = this.injector.get(TranslationService);

    let jwtToken = this.localstorageService.getItemSingle<string>(environment.tokenStorageName) ?? ""
    authService.checkToken(jwtToken).subscribe({next: (result: any) => {
      if (result['status'] == 200) {
        if (result.body['Status'] == 'Worked' && result.body['Message'])
        {
          this.isAuth = true
          return
        } else {
          this.localstorageService.setItem(environment.tokenStorageName, "")
          this.isAuth = false
        }
      }
    }, error: (error: any) => {
      return error
    }})

    this.localstorageService.getItem(environment.tokenStorageName).subscribe(value => {
      this.isAuth = value != "" && value != null
    });

    this.isLoading = false
  }

  logout() {
    this.localstorageService.setItem(environment.tokenStorageName, "");
  }

}
