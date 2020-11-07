import { Component, OnInit } from '@angular/core';
import { MessageService} from '../message.service';

import { Observable } from 'rxjs';

import {MatSnackBar} from '@angular/material';


@Component({
  selector: 'app-messages',
  templateUrl: './messages.component.html',
  styleUrls: ['./messages.component.css'],
  
})
export class MessagesComponent implements OnInit {

  messages : string;

  constructor(public messageService : MessageService, public snackBar: MatSnackBar) { 
  }


  openSnackBar() {
    this.snackBar.open(this.messageService.messages, null, {
      duration: 1000,
    });
  }

  

  ngOnInit() {
    this.getMessages();

  }

  getMessages():void {
    this.messageService.getMessages().subscribe(messages => 
     { this.messages = messages});
  }

}


