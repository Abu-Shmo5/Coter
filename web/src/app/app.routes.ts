import { Routes } from '@angular/router';
import { LayoutComponent } from './layout/layout.component';
import { IndexComponent } from './index/index.component';
import { InvalidPageComponent } from './invalid-page/invalid-page.component';


export const routes: Routes = [
    {
        path: '',
        component: LayoutComponent,
        children: [
            {
                path: '',
                component: IndexComponent
            },
            {
                path: 'notes',
                loadChildren: () => import('./notes/notes.module').then(m => m.NotesModule),
            },
            {
                path: 'account',
                loadChildren: () => import('./account/account.module').then(m => m.AccountModule)
            },
            {
                path: '**',
                component: InvalidPageComponent
            }
        ]
    }
];
