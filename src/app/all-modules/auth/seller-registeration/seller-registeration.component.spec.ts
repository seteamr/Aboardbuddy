import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SellerRegisterationComponent } from './seller-registeration.component';

describe('SellerRegisterationComponent', () => {
  let component: SellerRegisterationComponent;
  let fixture: ComponentFixture<SellerRegisterationComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SellerRegisterationComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(SellerRegisterationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
