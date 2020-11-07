import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import {HeroesComponent} from './heroes/heroes.component';
import {DashboardComponent} from './dashboard/dashboard.component';
import {HeroDetailComponent} from './hero-detail/hero-detail.component';
import {LoginComponent} from './login/login.component';
import {MypageComponent} from './mypage/mypage.component';
import {AppComponent} from './app.component';
import {HeroAddComponent} from './hero-add/hero-add.component';


const routes: Routes = [
  {path : 'heroes', component : HeroesComponent},
  {path : 'dashboard', component : DashboardComponent},
  {path : '', redirectTo : '/dashboard', pathMatch : 'full'},
  {path : 'detail/:id', component: HeroDetailComponent},
  {path : 'login', component: LoginComponent},
  {path : 'mypage', component: MypageComponent},
  {path : 'app', component : AppComponent},
  {path : 'add', component : HeroAddComponent}

];


@NgModule({
  exports: [RouterModule],
  imports : [RouterModule.forRoot(routes)]

})
export class AppRoutingModule { 
}
