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
import { shouldPerformTransition, performTransition } from "turbo-view-transitions";
testScript('In peace?',false);
console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

let skipNextRenderTransition = false;

document.addEventListener("turbo:before-render", (event) => {
  if (shouldPerformTransition() && !skipNextRenderTransition) {
    event.preventDefault();

    performTransition(document.body, event.detail.newBody, async () => {
      await event.detail.resume();
    });
  }
});

document.addEventListener("turbo:load", () => {
  // View Transitions don't play nicely with Turbo cache
  // TURN of
  // if (shouldPerformTransition()) Turbo.cache.exemptPageFromCache();
});

document.addEventListener("turbo:before-frame-render", (event) => {
  if (shouldPerformTransition() && !event.target.hasAttribute('data-skip-transition')) {
    event.preventDefault();
    skipNextRenderTransition = true;
    setTimeout(()=>{
        skipNextRenderTransition = false;
    },100);
    performTransition(event.target, event.detail.newFrame, async () => {
      await event.detail.resume();
    });
  }
});