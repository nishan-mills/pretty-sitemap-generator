/* eslint-env browser */
'use strict';

import jquery from 'jquery';
import accordion from 'modules/accordion.js';

(function($) {
  $(document).ready(function() {
    ready();
  });

  // Initalizing all modules
  function ready() {
    accordion();
  }
})(jquery);
