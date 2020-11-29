import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import * as Centrifuge from "centrifuge";
import {CentrifugoService} from "../core";


// import { ArticleListConfig, TagsService, UserService } from '../core';

@Component({
  selector: 'app-home-page',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {

  constructor(private centrifugoService: CentrifugoService) {
    this.centrifugoService.subscribe("news", function(ctx) {
      console.log(ctx.data);
    });
  }

  ngOnInit() {
  }
}
