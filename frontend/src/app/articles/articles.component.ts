import {AfterViewInit, Component, OnInit, ViewChild} from '@angular/core';
import {Article} from "../core/models";
import {MatTableDataSource} from "@angular/material/table";
import {MatPaginator} from "@angular/material/paginator";
import {ArticleService} from "../core/services";

@Component({
  selector: 'app-articles',
  templateUrl: './articles.component.html',
  styleUrls: ['./articles.component.scss']
})
export class ArticlesComponent  implements OnInit, AfterViewInit {
  articles: Article[] = [];

  dataSource = new MatTableDataSource<Article>(this.articles);
  @ViewChild(MatPaginator) paginator: MatPaginator;

  constructor(private articleService: ArticleService) {
  }

  ngOnInit() {
  }

  ngAfterViewInit() {
    this.articleService.getAll().subscribe(
      value => this.dataSource.data = value
    );

    this.dataSource.paginator = this.paginator;
  }

  edit(id: number) {
    alert('Edit article by id: ' + id);
  }

}
