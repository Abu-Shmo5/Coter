import { CanActivateFn, Router } from '@angular/router';
import { PlatformserviceService } from './platformservice.service';
import { inject } from '@angular/core';
import { LocalstorageService } from './localstorage.service';
import { environment } from '../environment/environment';

export const authGuard: CanActivateFn = (route, state) => {
  const platformService = inject(PlatformserviceService)
  if (!platformService.isBrowser()) return false;
  const router = inject(Router)
  const localstorageService = inject(LocalstorageService)
  const token = localstorageService.getItemSingle(environment.tokenStorageName, "")
  if (token == "") {
    router.navigate(['/account/login'])
    return false;
  }
  return true;
};
