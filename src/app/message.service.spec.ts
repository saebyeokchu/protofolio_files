import { TestBed, inject } from '@angular/core/testing';

import { MessageService } from './message.service';
import {HeroService} from './hero.service';

describe('MessageService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [MessageService]
    });
  });

  it('should be created', inject([MessageService], (service: MessageService) => {
    expect(service).toBeTruthy();
  }));
});
