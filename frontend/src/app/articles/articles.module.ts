import { NgModule } from '@angular/core';
import { ShowComponent } from './show/show.component';
import { ArticlesComponent } from './articles.component';
import {ArticlesRoutingModule} from "./articles-routing.module";
import {SharedModule} from "../shared";

@NgModule({
  imports: [
    ArticlesRoutingModule,
    SharedModule,
  ],
  declarations: [ArticlesComponent, ShowComponent]
})
export class ArticlesModule { }
