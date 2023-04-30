import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BuddiesDetailComponent } from './buddies-detail.component';

describe('BuddiesDetailComponent', () => {
  let component: BuddiesDetailComponent;
  let fixture: ComponentFixture<BuddiesDetailComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ BuddiesDetailComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(BuddiesDetailComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
