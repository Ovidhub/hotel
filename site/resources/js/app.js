import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import bookingPicker from './bookingPicker';
import galleryManager from './galleryManager';

Alpine.plugin(collapse);
Alpine.data('bookingPicker', bookingPicker);
Alpine.data('galleryManager', galleryManager);
window.Alpine = Alpine;
Alpine.start();
