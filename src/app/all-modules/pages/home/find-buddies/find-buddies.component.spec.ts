import { ComponentFixture, TestBed } from '@angular/core/testing';

import { FindBuddiesComponent } from './find-buddies.component';

describe('FindBuddiesComponent', () => {
  let component: FindBuddiesComponent;
  let fixture: ComponentFixture<FindBuddiesComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ FindBuddiesComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(FindBuddiesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
