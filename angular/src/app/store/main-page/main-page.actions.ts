import { Action } from '@ngrx/store';
import { type } from '../util';

export const ACTION = {
  EDIT_PAGE:  type('[Main] Edit Page'),

  OPEN:       type('[Main] Open'),
  CLOSE:      type('[Main] Close')
};

export class EditPageAction implements Action {
  type = ACTION.EDIT_PAGE;
}

export class OpenAction implements Action {
  type = ACTION.OPEN;

  constructor(public payload: number) { }
}
export class CloseAction implements Action {
  type = ACTION.CLOSE;

  constructor(public payload: number) { }
}

export type Actions
  = OpenAction
  | CloseAction
  | EditPageAction;
