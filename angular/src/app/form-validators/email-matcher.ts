import { AbstractControl } from '@angular/forms';

export const emailMatcher = (control: AbstractControl): {[key: string]: boolean} => {
  const email = control.get('email');
  const confirm = control.get('confirm');
  if (!email || !confirm) return undefined;
  return email.value === confirm.value ? undefined : { nomatch: true };
};
