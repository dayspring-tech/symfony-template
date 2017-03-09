import { NgModule }            from '@angular/core';
import { RouterModule }        from '@angular/router';

import { HomeComponent }    from './home.component';
import { AboutComponent } from './about.component';
import { ContactComponent } from './contact.component';

@NgModule({
  imports: [RouterModule.forChild([
    { path: 'home', component: HomeComponent },
    { path: 'about', component: AboutComponent },
    { path: 'contact', component: ContactComponent }
  ])],
  exports: [RouterModule]
})
export class MainPagesRoutingModule {}
