import { Component, OnInit } from '@angular/core';
import {User, UserService} from "../../core";
import {ActivatedRoute} from "@angular/router";

@Component({
  selector: 'app-show',
  templateUrl: './show.component.html',
  styleUrls: ['./show.component.scss']
})
export class ShowComponent implements OnInit {
  user: User;

  constructor(private userService: UserService, private route: ActivatedRoute) {
  }

  ngOnInit(): void {
    this.route.paramMap.subscribe(params => {
      this.userService.getById(+params.get('id')).subscribe(
        value => this.user = value
      );
    });
  }

}
