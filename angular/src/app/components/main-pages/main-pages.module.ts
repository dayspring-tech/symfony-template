import { NgModule } from '@angular/core';
import { ReactiveFormsModule } from '@angular/forms';
import { BrowserModule } from '@angular/platform-browser';
import { LOG_LOGGER_PROVIDERS } from 'angular2-logger/core';

import { HomeComponent } from './home.component';
import { AboutComponent } from './about.component';
import { ContactComponent } from './contact.component';

import { MainPagesRoutingModule } from './main-pages-routing.module';

@NgModule({
  imports: [
    BrowserModule,
    ReactiveFormsModule,
    MainPagesRoutingModule
  ],
  declarations: [
    HomeComponent,
    AboutComponent,
    ContactComponent
  ],
  bootstrap: [HomeComponent],
  providers: [LOG_LOGGER_PROVIDERS]
})
export class MainPagesModule { }
