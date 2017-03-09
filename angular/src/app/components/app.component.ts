import { Component } from '@angular/core';
import { Logger } from 'angular2-logger/core';
import '../../sass/styles.scss';

@Component({
  selector: 'my-app',
  template: require('./app.component.html')
})
export class AppComponent {

  constructor(private $log: Logger) { }

  resetScores() {
    this.$log.info('reset');
    return false;
  }
  addScores(text: String) {
    this.$log.info(text);
    return false;
  }
}
