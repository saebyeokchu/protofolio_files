import { Injectable } from '@angular/core';

import { Observable } from 'rxjs';
import { of } from 'rxjs/observable/of';

@Injectable()
export class MessageService {



  messages: string ;
  loginMessages : string = "Log In";
 
  add(messages: string) {
    this.messages = messages;
  }

  addLogInMessage(loginMessages : string){
    this.loginMessages = loginMessages;
  }
  

  getMessages() : Observable<string> {
    return of(this.messages);
  }

  getLogInMessage () : Observable<string> {
    return of(this.loginMessages);
  }

  

}
