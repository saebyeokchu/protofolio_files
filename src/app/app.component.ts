import { Component, OnInit } from '@angular/core';
import { AngularFirestore,AngularFirestoreDocument  } from 'angularfire2/firestore';
import { AngularFireStorage, AngularFireStorageReference,AngularFireUploadTask  } from 'angularfire2/storage';
import { AngularFireDatabase }
  from 'angularfire2/database';

import {Observable} from 'rxjs';
import {Hero} from './hero';
import {HEROES} from './mock-heroes';
import { LoginComponent } from './login/login.component';
import { MessageService } from './message.service';
import {LoginService } from './login.service';
import * as firebase from 'firebase/app';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
  providers : [LoginComponent]

})
export class AppComponent implements OnInit {
  
  option=1;

  title = 'Tour of Heroes';
  isregistered : boolean = false;
  registers : string[];

  constructor(public messageService:MessageService,
  private loginComponent:LoginComponent,
  private loginService:LoginService,
  private afs:AngularFirestore){
  

  }

  change() {
    this.option = 2;
  }


  decideWay() {
    if( this.messageService.loginMessages=="Log Out") {
      this.loginService.logOut();
    }
  }

  

  isRegistered(isRegistered) {
    this.isregistered = isRegistered;
    
  }


  check(isTrue,user) {

    if(isTrue) {
      this.messageService.addLogInMessage("Log Out");
      this.loginService.setUID(user.uid,user.email);

      this.afs.doc(`users/${user.uid}`).valueChanges().subscribe(user => {
        if(user) {
          console.log(" ");
        }
        else {
          this.loginService.registerNewData();
        }
      });

    } else this.messageService.addLogInMessage("Log In");


   

  }

  


  ngOnInit() {

    //this.loginService.logOut();

    firebase.auth().onAuthStateChanged(user => {
      if (user) {

        console.log("logged in");
        this.check(true,user);

      } else {
        console.log("logged out");
         this.check(false, null);
      }
    });
  }  

}