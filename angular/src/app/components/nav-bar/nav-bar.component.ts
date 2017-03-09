import { Component, Input } from '@angular/core';
import { Logger } from 'angular2-logger/core';
import { Store } from '@ngrx/store';
import { go } from '@ngrx/router-store';
import { Observable } from 'rxjs/Observable';

import * as fromRoot from '../../store';
import * as fromMainPage from '../../store/main-page/main-page.reducer';
import * as page from '../../store/main-page/main-page.actions';

@Component({
  selector: 'nav-bar',
  template: require('./nav-bar.component.html'),
  styles: [require('./nav-bar.component.scss')]
})
export class NavBarComponent {
  @Input() readOnly: boolean = false;
  pageState$: Observable<fromMainPage.State>;

  private path: string;
  private urlDefinitions: {key: string, url: string}[] = [
    { key: 'home', url: '/home' },
    { key: 'about', url: '/about' },
    { key: 'contact', url: '/contact' }
  ];
  private urls: any;
  private active: any;

  constructor(private store: Store<fromRoot.State>, private $log: Logger) {
    this.pageState$ = store.select(fromRoot.getMainPageState);

    this.urls = {};
    this.active = {};
    this.urlDefinitions.forEach(value => {
      this.urls[value.key] = value.url;
      this.active[value.key] = false;
    });

    store.select(fromRoot.getRouterPath).subscribe((path) => {
      console.log(path);
      this.path = path;
      this.urlDefinitions.forEach(value => {
        this.active[value.key] = (path === value.url);
      });
    });

    this.pageState$.subscribe((state) => {
      this.readOnly = (state.readOnly !== undefined) ? state.readOnly : this.readOnly;
    });
  }

  private go(urlName: string): void {
    this.store.dispatch(go([this.urls[urlName]]));
  }
}
