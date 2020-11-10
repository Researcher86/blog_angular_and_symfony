import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {ArticlesComponent} from "./articles.component";
import {ShowComponent} from "./show/show.component";

const routes: Routes = [
  {
    path: '',
    component: ArticlesComponent,
  },
  {
    path: 'articles/:id',
    component: ShowComponent,
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ArticlesRoutingModule { }

