import { Injectable } from '@angular/core';
import { BehaviorSubject, map, Observable } from 'rxjs';
import { PlatformserviceService } from './platformservice.service';

@Injectable({
  providedIn: 'root'
})
export class LocalstorageService {

  private storageSubject!: BehaviorSubject<{ [key: string]: any; }>;

  constructor(private platformService: PlatformserviceService) {
    if (!platformService.isBrowser()) return
    this.storageSubject = new BehaviorSubject(this.loadInitialData())
    this.setupStorageListener();
  }

  setItem(key: string, value: any): void {
    const serializedValue = JSON.stringify(value);
    localStorage.setItem(key, serializedValue);
    this.notifyChange(key, value);
  }

  getItem<T>(key: string): Observable<T | null> {
    return this.storageSubject.asObservable().pipe(
      map(data => data[key] ? data[key] as T : null)
    );
  }

  getItemSingle<T>(key: string, defaultValue: T | null = null): T | null {
    if (!this.storageSubject) {
      console.warn('BehaviorSubject is not initialized');
      return defaultValue;
    }
    return this.storageSubject.value[key] !== undefined ? this.storageSubject.value[key] as T : defaultValue;
  }
  
  removeItem(key: string): void {
    localStorage.removeItem(key);
    this.notifyChange(key, null);
  }

  clear(): void {
    localStorage.clear();
    this.storageSubject.next({});
}

  keyExists(key: string): boolean {
    return localStorage.getItem(key) !== null;
  }
  
  private notifyChange(key: string, value: any): void {
    const currentData = this.storageSubject.value;
    const updatedData = { ...currentData, [key]: value };
    this.storageSubject.next(updatedData);
  }

  private loadInitialData(): { [key: string]: any } {
    if (!this.platformService.isBrowser()) {
      return {}
    }

    const data: { [key: string]: any } = {};
    Object.keys(localStorage).forEach(key => {
      try {
        data[key] = JSON.parse(localStorage.getItem(key) as string);
      } catch (error) {
        console.warn(`Error parsing localStorage key "${key}"`, error);
      }
    });
    return data;
  }

  private setupStorageListener(): void {
    window.addEventListener('storage', (event: StorageEvent) => {
      if (event.key && event.newValue) {
        this.notifyChange(event.key, JSON.parse(event.newValue));
      }
    });
  }


}
