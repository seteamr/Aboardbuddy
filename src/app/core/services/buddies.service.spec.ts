import { TestBed } from '@angular/core/testing';

import { BuddiesService } from './buddies.service';

describe('BuddiesService', () => {
  let service: BuddiesService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(BuddiesService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
