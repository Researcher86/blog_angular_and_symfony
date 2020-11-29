import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {HTTP_INTERCEPTORS, HttpClientModule} from '@angular/common/http';
import { HttpTokenInterceptor } from './interceptors';

import {
  ApiService,
  AuthGuard,
  JwtService,
  UserService,
  ArticleService,
  CentrifugoService
} from './services';

@NgModule({
  imports: [
    CommonModule,
    HttpClientModule
  ],
  providers: [
    { provide: HTTP_INTERCEPTORS, useClass: HttpTokenInterceptor, multi: true },
    ApiService,
    AuthGuard,
    JwtService,
    UserService,
    ArticleService,
    CentrifugoService
  ],
  declarations: []
})
export class CoreModule { }
