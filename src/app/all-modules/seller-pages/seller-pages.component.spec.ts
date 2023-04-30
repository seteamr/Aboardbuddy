import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SellerPagesComponent } from './seller-pages.component';

describe('SellerPagesComponent', () => {
  let component: SellerPagesComponent;
  let fixture: ComponentFixture<SellerPagesComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SellerPagesComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(SellerPagesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
