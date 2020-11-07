import { Injectable } from '@angular/core';

import { Hero } from './hero';

import {Observable} from 'rxjs';
import { of } from 'rxjs/observable/of';
import { HEROES } from './mock-heroes';

import { MessageService } from './message.service';

import { AngularFireDatabase } from 'angularfire2/database';
import { AngularFirestore, AngularFirestoreCollection,AngularFirestoreDocument } from 'angularfire2/firestore';
import { AngularFireStorageReference, AngularFireUploadTask, AngularFireStorage } from 'angularfire2/storage';
import { FirebaseApp } from 'angularfire2';



@Injectable()


export class HeroService {

  defaultImgUrl ="https://material.angular.io/assets/img/examples/shiba2.jpg";
  newHero : Hero;

  heroRef : AngularFirestoreCollection<Hero>;
  updateRef : AngularFirestoreDocument<Hero>;
  heroes : Observable<Hero[]>;

  hero : Hero;
  i : number;
  fireID : string;
  storNum : number;

  /* 이미지 업로딩 */
  ref : AngularFireStorageReference;
  task : AngularFireUploadTask;
  uploadProgress : Observable<number>;
  fileName : string;
  beforeFile : string;

  /* download image */
  downloadURL: Observable<string>;
  downloadURLToString : string;
  url : string;


  constructor(
    private messageService: MessageService,
    public afs:AngularFirestore,
    public afStorage:AngularFireStorage,

) {


    //error defending
    afs.firestore.settings({ timestampsInSnapshots: true });
    
    this.heroRef = this.afs.collection<Hero>('HEROES');

      
  
  }

  upload(event) : void {
  
    const id = Math.random().toString(36).substring(2);
    this.ref = this.afStorage.ref(event.target.files[0].name);
    this.task = this.ref.put(event.target.files[0]);
    this.uploadProgress = this.task.percentageChanges();
    
    this.fileName = event.target.files[0];

  }

  getImgURL(filename){

   this.afStorage.ref(filename.name).getDownloadURL().subscribe(url => 
    { 
      this.url = url;
      this.setImgUrl(this.url);

     })


  }

  setImgUrl(url) {

    this.heroRef.doc(this.fireID).update(
      {
        'imgURL' : url });
  
     }


  




  getHeroes(): Observable<Hero[]> {

    this.heroes = this.afs.collection<Hero>('HEROES').valueChanges();  

    return this.heroes;
  }

  getHero(editName : string, editSubtitle : string, editContent : string,
                uploadImgUrl : string,editImgUrl : string,id):void {

    this.fireID = id;


    if(uploadImgUrl == null) {

      this.fileName = null;

      this.afs.collection('HEROES').doc(this.messageService.messages).update({
        'name' : editName,
        'subtitle' : editSubtitle,
        'content': editContent,
        'imgURL' : editImgUrl
    });
  
      } else {

        this.fileName = null;
  
        this.getImgURL(uploadImgUrl);

        this.afs.collection('HEROES').doc(this.messageService.messages).update({
            'name' : editName,
            'subtitle' : editSubtitle,
            'content': editContent,
            'imgURL' : ""
        });
  
     }

     

  }

  deleteHero(hero : Hero) : void {

    this.messageService.add("Hero : " + hero.name + " is deleted");
    this.afs.collection('HEROES').doc(hero.id.toString()).delete();

  }

  addHero(newName:string,newSubtitle:string,newContent:string) : void {


    this.messageService.add("New Hero : " + newName + " is added");
  

    this.storNum = Math.floor((Math.random() * 10000) + 1);
    this.fireID =this.storNum.toString();

    this.heroRef.doc(this.fireID).set(
    {'name' : newName,
    'id' : this.fireID, 'subtitle' : newSubtitle , 
    'content' : newContent , 'imgURL' : this.defaultImgUrl });
     

   }


}