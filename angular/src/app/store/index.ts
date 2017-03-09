import { createSelector } from 'reselect';
import { ActionReducer } from '@ngrx/store';
import * as fromRouter from '@ngrx/router-store';

import { compose } from '@ngrx/core/compose';
import { storeFreeze } from 'ngrx-store-freeze';
import { combineReducers } from '@ngrx/store';

import * as fromMainPage from './main-page/main-page.reducer';

export interface State {
    mainPage: fromMainPage.State;
    router: fromRouter.RouterState;
}

const reducers = {
    mainPage: fromMainPage.reducer,
    router: fromRouter.routerReducer
};

const developmentReducer: ActionReducer<State> = compose(storeFreeze, combineReducers)(reducers);
const productionReducer: ActionReducer<State> = combineReducers(reducers);

export function reducer(state: any, action: any) {
    if (process.env.ENV === 'production') {
        return productionReducer(state, action);
    } else {
        return developmentReducer(state, action);
    }
}

export const getRouterPath = (state: State) => state.router.path;
export const getMainPageState = (state: State) => state.mainPage;
