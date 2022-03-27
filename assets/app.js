/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)

import "./styles/css/bootstrap.min.css";
import "./styles/css/core.css";
import "./styles/css/shortcode/shortcodes.css";
import "./styles/style.css";
import "./styles/css/responsive.css";
import "./styles/css/custom.css";
import "./styles/css/color/skin-default.css";

import './styles/app.css';

var $ = require('jquery');
global.$ = global.jQuery = $;

// start the Stimulus application
 import './bootstrap';
 import "./js/search.js";

// import "./js/vendor/jquery-1.12.0.min.js";
// const $ = require('jquery');
// import "./js/bootstrap.min.js";
// import "./js/slider/jquery.nivo.slider.pack.js";
// import "./js/slider/nivo-active.js";
// import "./js/jquery.countdown.min.js";
// import "./js/plugins.js";
// import "./js/main.js";
