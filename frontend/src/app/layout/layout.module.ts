import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {HeaderComponent} from './header/header.component';
import {FooterComponent} from './footer/footer.component';
import {MatIconModule} from "@angular/material/icon";
import {MatToolbarModule} from "@angular/material/toolbar";
import {RouterModule} from "@angular/router";
import {MatButtonModule} from "@angular/material/button";

@NgModule({
  declarations: [HeaderComponent, FooterComponent],
  imports: [
    CommonModule,
    MatIconModule,
    MatIconModule,
    MatToolbarModule,
    RouterModule,
    MatButtonModule,
    MatIconModule
  ],
  exports: [
    HeaderComponent, FooterComponent
  ]
})
export class LayoutModule {
}
