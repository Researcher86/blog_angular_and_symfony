import { NgModule } from '@angular/core';
import { ShowComponent } from './show/show.component';
import { ArticlesComponent } from './articles.component';
import {ArticlesRoutingModule} from "./articles-routing.module";
import {MatPaginatorModule} from "@angular/material/paginator";
import {MatTableModule} from "@angular/material/table";
import {MatButtonModule} from "@angular/material/button";
import {CoreModule} from "../core";

@NgModule({
  imports: [
    ArticlesRoutingModule,
    MatPaginatorModule,
    MatTableModule,
    MatButtonModule,
    CoreModule,
  ],
  declarations: [ArticlesComponent, ShowComponent]
})
export class ArticlesModule { }
