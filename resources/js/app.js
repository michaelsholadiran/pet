import './bootstrap';
import './shop';
import Alpine from 'alpinejs';
import { registerProductGallery } from './product-gallery';

registerProductGallery(Alpine);

window.Alpine = Alpine;
Alpine.start();
