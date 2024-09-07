/* The @ symbol isn't important: that's just a character namespaced JavaScript packages user. */
import { startStimulusApp } from '@symfony/stimulus-bundle';
import Popover from 'stimulus-popover'

const app = startStimulusApp();
app.register('popover', Popover);
