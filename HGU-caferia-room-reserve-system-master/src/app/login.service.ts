import { Injectable } from '@angular/core';

import { Hero } from './hero';

import {Observable} from 'rxjs';
import { of } from 'rxjs/observable/of';
import { HEROES } from './mock-heroes';
import {HeroService} from './hero.service';

import { MessageService } from './message.service';

import { AngularFireDatabase } from 'angularfire2/database';
import { AngularFirestore, AngularFirestoreCollection,AngularFirestoreDocument } from 'angularfire2/firestore';
import { AngularFireStorageReference, AngularFireUploadTask, AngularFireStorage } from 'angularfire2/storage';
import { FirebaseApp } from 'angularfire2';

import { AngularFireAuth } from 'angularfire2/auth';
import * as firebase from 'firebase/app';
import {Router } from '@angular/router';

import {Like} from './like';



interface User {
  uid : string;
  nickname : string;
  age : string;
  email : string;
}

interface Register {
  uid : string;
}

@Injectable(
)

export class LoginService {

  user : Observable <User>

  ref : AngularFirestoreDocument<User>;

  uid : string;
  email : string;

  registers : string[];

  regiRef : AngularFirestoreDocument<Register>;

  isregistered : string;


  constructor( 
    public afAuth:AngularFireAuth,
    public afs: AngularFirestore,
    private router : Router,
    private messageService : MessageService
    ) { 

      afs.firestore.settings({ timestampsInSnapshots: true });

      this.afs.collection<Register>('registers');

      this.user = this.afAuth.authState.switchMap (user => {
      if (user) {
        return this.afs.doc<User>(`users/${user.uid}`).valueChanges()
      }
      else return Observable.of(null);
      })

      
    }

    setLogInMessage() {
      if(this.uid)
      this.messageService.addLogInMessage("Log Out");
      else this.messageService.addLogInMessage("Log In");
    }
    
    setUID (uidOfReload, emailOfReload) {
      this.uid = uidOfReload;
      this.email = emailOfReload;
    }


    sginInWithGoogle() {

      return this.afAuth.auth.signInWithPopup(new firebase.auth.GoogleAuthProvider())
        .then((credential) => {
          
          this.updateUserData(credential.user);
        });

    }

    updateUserData(user) {
      
      this.messageService.addLogInMessage("Log Out"); 

      this.uid = user.uid;
      this.email = user.email;
      
      const userRef : AngularFirestoreDocument<User> = this.afs.doc(`users/${user.uid}`);   
      
      this.afs.doc(`users/${user.uid}`).valueChanges().subscribe(user => {
        if(user) {
          console.log (" ");
        }
        else {
          this.registerNewData();
        }
      
      })

      
    //  return userRef.set(data);
    }

    registerNewData() {

      const userRef : AngularFirestoreDocument<User> = this.afs.doc(`users/${this.uid}`);   
      
      const data : User = {
        email : this.email,
        uid : this.uid,
        nickname: " ",
        age : " "
      }

      userRef.set(data);

    }
    

    editUser(nickname : string, age: string, uid:string):void {

      this.afs.doc(`users/${this.uid}`).update( {

        'nickname' : nickname,
        'age' : age

      }
    )
  
    }

    addLike(name:string,date:string,time : string)  : void {

      this.afs.doc<Like>(`users/${this.uid}/Like/${name}`).set( {

        'LikedName' : name,
        'LikedTime' : date,
        'LikedDate' : time,

      }
    )

    }

    logOut(){
      this.messageService.addLogInMessage("Log In"); 
      this.afAuth.auth.signOut();
    }

    isLoggedIn() {

    }


}
