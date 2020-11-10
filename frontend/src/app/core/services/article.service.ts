import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

import { ApiService } from './api.service';
import { Article } from '../models';
import { map } from 'rxjs/operators';


@Injectable()
export class ArticleService {
  constructor (
    private apiService: ApiService,
  ) {}

  getAll(): Observable<Article[]> {
    return this.apiService
      .get('/articles')
      .pipe(map(data => {
        return data;
      }));
  }

  getById(id: number): Observable<Article> {
    return this.apiService
      .get('/articles/' + id)
      .pipe(map(data => {
        return data;
      }));
  }
}
