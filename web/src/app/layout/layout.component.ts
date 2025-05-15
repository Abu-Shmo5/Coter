import { CommonModule } from '@angular/common';
import { Component, Injector, OnInit } from '@angular/core';
import { RouterLink, RouterOutlet } from '@angular/router';
import { environment } from '../environment/environment';

@Component({
  selector: 'app-layout',
  imports: [RouterLink, RouterOutlet, CommonModule],
  templateUrl: './layout.component.html',
  styleUrl: './layout.component.scss'
})
export class LayoutComponent implements OnInit {

  isLoading = true
  isAuth = false

  constructor(private injector: Injector) {}

  async ngOnInit() {
    const { LazyloaderService } = await import('../services/lazyloader.service');
    const lazyLoader = this.injector.get(LazyloaderService);
    lazyLoader.loadCSS('/static/css/bootstrap.min.css');
    lazyLoader.loadScript('/static/js/bootstrap.bundle.min.js');

    const { PlatformserviceService } = await import('../services/platformservice.service');
    const platformserviceService = this.injector.get(PlatformserviceService);

    const { LocalstorageService } = await import('../services/localstorage.service');
    const localstorageService = this.injector.get(LocalstorageService);

    const { HttpService } = await import('../services/http.service');
    const httpService = this.injector.get(HttpService);

    const { AuthService } = await import('../services/auth.service');
    const authService = this.injector.get(AuthService);

    const { ThemeService } = await import('../services/theme.service');
    const themeService = this.injector.get(ThemeService);
    
    const { TranslationService } = await import('../services/translation.service');
    const translationService = this.injector.get(TranslationService);

    let jwtToken = localstorageService.getItemSingle<string>(environment.tokenStorageName) ?? ""
    authService.checkToken(jwtToken).subscribe({next: (result: any) => {
      if (result['status'] == 200) {
        if (result.body['Status'] == 'Worked' && result.body['Message'])
        {
          this.isAuth = true
          return
        }
      }
      this.isAuth = false
    }, error: (error: any) => {
      return error
    }})
    
    this.isLoading = false
  }

}
