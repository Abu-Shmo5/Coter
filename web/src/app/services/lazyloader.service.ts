import { DOCUMENT } from '@angular/common';
import { Inject, Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class LazyloaderService {
  
  constructor(@Inject(DOCUMENT) private document: Document) {}

  loadCSS(url: string): Promise<void> {
    return new Promise((resolve, reject) => {
      const link = this.document.createElement('link');
      link.rel = 'stylesheet';
      link.href = url;
      link.onload = () => resolve(); // CSS fully loaded
      link.onerror = () => reject(`Failed to load CSS: ${url}`); // Handle load failure
      this.document.head.appendChild(link);
    });
  }

  loadScript(url: string): Promise<void> {
    return new Promise((resolve, reject) => {
      const script = this.document.createElement('script');
      script.type = 'text/javascript';
      script.src = url;
      script.async = true;
      script.onload = () => resolve(); // JS fully loaded
      script.onerror = () => reject(`Failed to load JS: ${url}`); // Handle load failure
      this.document.body.appendChild(script);
    });
  }
}
