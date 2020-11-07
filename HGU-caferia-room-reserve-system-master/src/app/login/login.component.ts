import { Component, OnInit } from '@angular/core';

import {HeroService} from '../hero.service';
import {MessageService} from '../message.service';
import {Hero} from '../hero';
import {LoginService} from '../login.service';

import { AngularFirestore, AngularFirestoreDocument,AngularFirestoreCollection } from 'angularfire2/firestore';
import { Observable} from 'rxjs/Observable';
import { of } from 'rxjs/observable/of';
import 'rxjs/add/operator/map';

import { AngularFireAuth } from 'angularfire2/auth';
import * as firebase from 'firebase/app';

import {Router, ActivatedRoute} from '@angular/router';





@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {

  isregistered : string;

  constructor(
    public loginService : LoginService,
    private messageService : MessageService,
    private heroService : HeroService,
    private r : Router,
    private route: ActivatedRoute
    ) { 
    
  }



    login() {
        this.loginService.sginInWithGoogle();

  
    }

    logout() {
      this.loginService.logOut();
    }

      //  this.r.navigate([`/login`], { relativeTo: this.route });
     

  ngOnInit() {

  }  

 





}
