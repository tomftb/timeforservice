import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
/* SIMILAR TO Webpack Encore - in Encore there is no need to add extension .js*/
import testScript from './lib/test-script.js';
testScript('In peace?',false);
console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
