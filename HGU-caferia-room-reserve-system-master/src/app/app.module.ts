import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';


import { AppComponent } from './app.component';
import { HeroesComponent } from './heroes/heroes.component';
import { HeroDetailComponent } from './hero-detail/hero-detail.component';
import { MessagesComponent } from './messages/messages.component';
import {MessageService} from './message.service';
import {HeroService} from './hero.service';
import {AppRoutingModule} from './app-routing.module';
import {DashboardComponent } from './dashboard/dashboard.component';

import {
  MatButtonModule,
  MatCheckboxModule,
  MatToolbarModule,
  MatCardModule,
  MatTableModule,
  MatProgressBarModule,
  MatFormFieldModule,
  MatInputModule
} from '@angular/material';
import {MatSnackBarModule} from '@angular/material/snack-bar';

import { AngularFireModule } from 'angularfire2';
import { environment } from '../environments/environment';
import { AngularFirestoreModule } from 'angularfire2/firestore';
import { AngularFireStorageModule } from 'angularfire2/storage';
import { AngularFireAuthModule } from 'angularfire2/auth';
import { LoginComponent } from './login/login.component';
import { AngularFireDatabase } from 'angularfire2/database';
import { MypageComponent } from './mypage/mypage.component';
import { LoginService } from './login.service';
import { HeroAddComponent } from './hero-add/hero-add.component';



@NgModule({
  declarations: [
    AppComponent,
    HeroesComponent,
    HeroDetailComponent,
    MessagesComponent,
    DashboardComponent,
    LoginComponent,
    MypageComponent,
    HeroAddComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    AppRoutingModule,
    AngularFireModule.initializeApp(environment.firebase),
    AngularFirestoreModule, // imports firebase/firestore, only needed for database features
    AngularFireAuthModule, // imports firebase/auth, only needed for auth features,
    AngularFireStorageModule,
    BrowserAnimationsModule,
    /*material import part*/
    MatButtonModule,
    MatCheckboxModule,
    MatToolbarModule,
    MatCardModule,
    MatSnackBarModule,
    MatTableModule,
    MatProgressBarModule,
    MatFormFieldModule,
    BrowserModule,
    MatButtonModule,
    MatCheckboxModule,
    BrowserAnimationsModule,
    MatFormFieldModule,
    MatCardModule,
    BrowserModule, 
    FormsModule,
    MatCheckboxModule,
    MatCardModule,
    MatInputModule
  ],
  providers: [
    MessageService,
    HeroService,
    LoginService
  ],
  bootstrap: [AppComponent],
  exports: [
  ]
})
export class AppModule { }
