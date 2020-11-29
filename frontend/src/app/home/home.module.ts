import {NgModule} from '@angular/core';

import {HomeRoutingModule} from './home-routing.module';
import {HomeComponent} from "./home.component";
import {CoreModule} from "../core";

@NgModule({
  imports: [
    HomeRoutingModule,
    CoreModule
  ],
  declarations: [HomeComponent]
})
export class HomeModule {}
