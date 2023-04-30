import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PostCarpoolComponent } from './post-carpool.component';

describe('PostCarpoolComponent', () => {
  let component: PostCarpoolComponent;
  let fixture: ComponentFixture<PostCarpoolComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ PostCarpoolComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(PostCarpoolComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
