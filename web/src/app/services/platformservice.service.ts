import { isPlatformBrowser } from '@angular/common';
import { Inject, Injectable, PLATFORM_ID } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class PlatformserviceService {

  constructor(@Inject(PLATFORM_ID) private platformId: object) {}
  
  loadInit(): boolean {
    return true
  }

  isBrowser(): boolean {
    return isPlatformBrowser(this.platformId);
  }
}
