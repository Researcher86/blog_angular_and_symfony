import {Component} from '@angular/core';
import {CentrifugoService} from "./core";
import {MatSnackBar} from "@angular/material/snack-bar";

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  title = 'app';
  constructor(private centrifugoService: CentrifugoService, private _snackBar: MatSnackBar) {
    this.centrifugoService.subscribe("news", function(ctx) {
      this._snackBar.open(ctx.data.message, 'X', {
        duration: -1,
        horizontalPosition: 'end',
        verticalPosition: 'top',
      });
    }.bind(this));
  }
}
