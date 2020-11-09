import {NgModule} from '@angular/core';
import {UsersComponent} from "./users.component";
import {UsersRoutingModule} from "./users-routing.module";
import {SharedModule} from "../shared";
import { ShowComponent } from './show/show.component';

@NgModule({
  imports: [
    UsersRoutingModule,
    SharedModule,
  ],
  declarations: [UsersComponent, ShowComponent]
})
export class UsersModule {
}
