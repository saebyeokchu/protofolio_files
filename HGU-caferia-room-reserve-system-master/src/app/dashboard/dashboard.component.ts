import { Component, OnInit } from '@angular/core';
import {Hero} from '../hero';
import {HeroService} from '../hero.service';
import { MessagesComponent } from '../messages/messages.component';

import {MessageService} from '../message.service';
import {LoginService} from '../login.service';

import { HeroDetailComponent } from '../hero-detail/hero-detail.component';
import { Observable } from '@firebase/util';
import { of } from 'rxjs/observable/of';
import { MypageComponent } from '../mypage/mypage.component';




@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css'],
  providers: [MessagesComponent,HeroDetailComponent,MypageComponent]
})


export class DashboardComponent implements OnInit {


  uid : string;


  heroes : Hero[] = [];
  urlheroes : Hero[] = [];
  url : string ;
  date : Date;
  toDate : string;
  toTime : string;

  constructor(private heroService: HeroService,
  public messagesComponent: MessagesComponent,
  private herodetailComonent : HeroDetailComponent,
  private messageService : MessageService,
  private mypageComponent : MypageComponent,
  public loginService:LoginService ) 
  {
   }

  ngOnInit() {
    this.getHeroes();
  }



  getHeroes() : void {

    this.heroService.getHeroes().subscribe(  
      (heroes: Hero[]) => {  
        this.heroes = heroes as Hero[]; 
      }

    );

  }

  likeInfo(hero:Hero) : void {

    this.toDate = new Date().toLocaleTimeString();
    this.toTime = new Date().toLocaleDateString();
    this.mypageComponent.setLikeTable(hero.name,this.toDate,this.toTime);

  }

  editHero(hero:Hero) : void {
   this.herodetailComonent.getHero(hero.id);
  }

  deleteHero(hero: Hero): void {
    this.heroService.deleteHero(hero);
    this.messagesComponent.openSnackBar();
  }

}
