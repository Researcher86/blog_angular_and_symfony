import {NgModule} from '@angular/core';
import {UsersComponent} from "./users.component";
import {UsersRoutingModule} from "./users-routing.module";
import { ShowComponent } from './show/show.component';
import {SharedModule} from "../shared";

@NgModule({
  imports: [
    UsersRoutingModule,
    SharedModule,
  ],
  declarations: [UsersComponent, ShowComponent]
})
export class UsersModule {
}
