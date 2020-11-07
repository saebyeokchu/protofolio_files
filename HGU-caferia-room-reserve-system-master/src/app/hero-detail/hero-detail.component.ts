import { Component, OnInit, Input } from '@angular/core';
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


@Component({
  selector: 'app-hero-detail',
  templateUrl: './hero-detail.component.html',
  styleUrls: ['./hero-detail.component.css']
})


export class HeroDetailComponent implements OnInit{

  @Input() hero: Hero;
  heroes : Hero[] = [];
  editName :string; 
  editSubtitle: string;
  editContent : string;
  editImgUrl : string;
  selectedName : string;
  hero2:Observable<Hero>;
  ref : AngularFirestoreDocument<Hero>;
  ic : Hero;
  id :number;

  url : string;

  uploadProgress : number;


  constructor(
    private route : ActivatedRoute,
    private heroService : HeroService,
    private location : Location,
    private messageService : MessageService,

  ) { 

    
    }





  ngOnInit() : void {
    this.getHeroes();
  }

  getHeroes() : void {

    this.heroService.getHeroes().subscribe(  
      (heroes: Hero[]) => {  
        this.heroes = heroes;   
      }  );


  }

  upload(event,id): void {
    this.heroService.upload(event);
    this.id = id;

    this.heroService.uploadProgress.subscribe(
      (progress : number) => {
        this.uploadProgress = progress;
      }
    )

  }


  editHero() : void {



    this.editName = (<HTMLInputElement>document.getElementById("editName")).value;
    this.editSubtitle = (<HTMLInputElement>document.getElementById("editSubtitle")).value;
    this.editContent = (<HTMLInputElement>document.getElementById("editContent")).value;
    this.editImgUrl = (<HTMLInputElement>document.getElementById("editImgURL")).value;

    //this.heroService.getHero(this.editName,this.editSubtitle,this.editContent,this.editImgUrl);
    this.heroService.getHero(this.editName,this.editSubtitle,this.editContent,this.heroService.fileName,this.editImgUrl,this.id);
  }

  getHero(selectedId:string) : void {
    this.messageService.add(selectedId);
  }
    



  goBack() : void {
    this.location.back();
  }

}
