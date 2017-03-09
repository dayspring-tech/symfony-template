import { createSelector } from 'reselect';
import * as mainPage from './main-page.actions';

export interface State {
  readOnly: boolean;
  open: number;
};

const initialState: State = {
  readOnly: undefined,
  open: undefined
};

export function reducer(state = initialState, action: mainPage.Actions): State {
  switch (action.type) {
    case mainPage.ACTION.EDIT_PAGE:
      return {
        readOnly: false,
        open: undefined
      };

    case mainPage.ACTION.OPEN:
      return {
        readOnly: state.readOnly,
        open: (<mainPage.OpenAction>action).payload
      };
    case mainPage.ACTION.CLOSE:
      return {
        readOnly: state.readOnly,
        open: undefined
      };

    default:
      return state;
  }
}

export const getMainPageState = (state: State) => state;
