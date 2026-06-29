import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import bookingPicker from './bookingPicker';

Alpine.plugin(collapse);
Alpine.data('bookingPicker', bookingPicker);
window.Alpine = Alpine;
Alpine.start();
