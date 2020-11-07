import { Component, OnInit } from '@angular/core';
import {LoginService} from '../login.service';
import { Hero } from '../hero';

import {ActivatedRoute} from '@angular/router';
import {Location} from '@angular/common';
import {HeroService} from '../hero.service';
import {MessageService} from '../message.service';

import { AngularFirestore, 
  AngularFirestoreCollection,
  AngularFirestoreDocument 
} from 'angularfire2/firestore';
import { Observable } from '@firebase/util';

import { Pipe, PipeTransform } from '@angular/core';
import { transformMenu } from '@angular/material';
import { pipe } from 'rxjs';

import {Like} from '../like';
import * as firebase from 'firebase/app';




@Component({
  selector: 'app-mypage',
  templateUrl: './mypage.component.html',
  styleUrls: ['./mypage.component.css']
})
export class MypageComponent implements OnInit {


  displayedColumns = ['HeroName', 'LikedTime', 'LikedDate'];
  
  

  nickname : string;
  age : string;
  uid : string;

  likes : Like[] ;

  constructor(
    public loginService : LoginService
  ) { 

   

  }

  getTable() {

    this.loginService.afs.collection<Like>(`users/${this.loginService.uid}/Like`).valueChanges().subscribe(  
      (likes: Like[]) => {  
        this.likes = likes as Like[]; 
  })
}



  ngOnInit() {

    firebase.auth().onAuthStateChanged(user => {
      if (user) {
        this.getTable();
      }
    });




     
    
  }


  setLikeTable(name:string,toDate:string,toTime:string) : void {

    this.loginService.addLike(name ,toDate,toTime);
  }

  edit() : void {
    this.nickname = (<HTMLInputElement>document.getElementById("nickname")).value;
    this.age = (<HTMLInputElement>document.getElementById("age")).value;
    this.uid = (<HTMLInputElement>document.getElementById("uid")).value;
    this.loginService.editUser(this.nickname ,this.age, this.uid);
  }

}
