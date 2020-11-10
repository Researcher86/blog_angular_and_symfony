import { Component, OnInit } from '@angular/core';
import {Article} from "../../core/models";
import {ArticleService} from "../../core/services";
import {ActivatedRoute} from "@angular/router";

@Component({
  selector: 'app-show',
  templateUrl: './show.component.html',
  styleUrls: ['./show.component.css']
})
export class ShowComponent implements OnInit {

  article: Article;

  constructor(private articleService: ArticleService, private route: ActivatedRoute) {
  }

  ngOnInit(): void {
    this.route.paramMap.subscribe(params => {
      this.articleService.getById(+params.get('id')).subscribe(
        value => this.article = value
      );
    });
  }

}
