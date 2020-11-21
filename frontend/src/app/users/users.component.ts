import {AfterViewInit, Component, OnInit, ViewChild} from '@angular/core';
import {User, UserService} from "../core";
import {MatTableDataSource} from "@angular/material/table";
import {MatPaginator} from "@angular/material/paginator";

@Component({
  selector: 'users',
  templateUrl: './users.component.html',
  styleUrls: ['./users.component.scss']
})
export class UsersComponent implements OnInit, AfterViewInit {
  users: User[] = [];

  dataSource = new MatTableDataSource<User>(this.users);
  @ViewChild(MatPaginator) paginator: MatPaginator;

  constructor(private userService: UserService) {
  }

  ngOnInit() {
    // this.userService.getAll().subscribe(
    //   value => this.dataSource.data = value
    // );
  }

  ngAfterViewInit() {
    this.userService.getAll().subscribe(
      value => this.dataSource.data = value
    );

    this.dataSource.paginator = this.paginator;
  }

  edit(id: number) {
    alert('Edit user by id: ' + id);
  }
}
