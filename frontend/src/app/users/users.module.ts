import {NgModule} from '@angular/core';
import {UsersComponent} from "./users.component";
import {UsersRoutingModule} from "./users-routing.module";
import {ShowComponent} from './show/show.component';
import {MatPaginatorModule} from "@angular/material/paginator";
import {MatTableModule} from "@angular/material/table";
import {MatButtonModule} from "@angular/material/button";
import {CoreModule} from "../core";

@NgModule({
  imports: [
    UsersRoutingModule,
    MatPaginatorModule,
    MatTableModule,
    MatButtonModule,
    CoreModule,
  ],
  declarations: [UsersComponent, ShowComponent]
})
export class UsersModule {
}
