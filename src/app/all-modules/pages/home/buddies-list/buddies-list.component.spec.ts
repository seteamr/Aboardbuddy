import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BuddiesListComponent } from './buddies-list.component';

describe('BuddiesListComponent', () => {
  let component: BuddiesListComponent;
  let fixture: ComponentFixture<BuddiesListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ BuddiesListComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(BuddiesListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
