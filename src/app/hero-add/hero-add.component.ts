import { Component, OnInit } from '@angular/core';

import {ActivatedRoute} from '@angular/router';
import {Location} from '@angular/common';
import {HeroService} from '../hero.service';
import { Hero } from '../hero';

import {MessagesComponent} from '../messages/messages.component';
import { StringifyOptions } from 'querystring';


@Component({
  selector: 'app-hero-add',
  templateUrl: './hero-add.component.html',
  styleUrls: ['./hero-add.component.css'],
  providers: [MessagesComponent]
})
export class HeroAddComponent implements OnInit {

  url:string;

  constructor(private route : ActivatedRoute,
    public heroService : HeroService,
    private location : Location,
    public messagesComponent: MessagesComponent) { 

    }


  goBack() : void {
    this.location.back();
  }

  addHero(name:string,subtitle:string,content:string) : void {
    
    this.heroService.addHero(name,subtitle,content);
    this.messagesComponent.openSnackBar();

  }

  upload(event): void {
    this.heroService.upload(event);
  }

  ngOnInit() {
  }

}
