import { Injectable } from '@angular/core';
import { environment } from '../../../environments/environment';
import { HttpHeaders, HttpClient, HttpParams } from '@angular/common/http';
import { Observable ,  throwError } from 'rxjs';

import { JwtService } from './jwt.service';
import { catchError } from 'rxjs/operators';
import * as Centrifuge from "centrifuge";

@Injectable()
export class CentrifugoService {
  private centrifuge = new Centrifuge(`${environment.centrifugo_url}`);

  constructor() {
    this.centrifuge.setToken("eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxIn0.FasJnVFa0htLRI3VajVhbweHTHfeKTXV0y6emUBiFGs");

    this.centrifuge.on('connect', function(ctx) {
      console.log("connected", ctx);
    });

    this.centrifuge.on('disconnect', function(ctx) {
      console.log("disconnected", ctx);
    });

    this.centrifuge.connect();
  }

  subscribe(channel: string, callback: (ctx) => void) {
    this.centrifuge.subscribe(channel, callback);
  }
}
