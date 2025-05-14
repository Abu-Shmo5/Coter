import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { IndexComponent } from './index/index.component';
import { IdComponent } from './id/id.component';

const routes: Routes = [
  {
    path: "",
    component: IndexComponent
  },
  {
    path: "id",
    component: IdComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class NotesRoutingModule { }
