/**
 * Product image carousel + lightbox.
 * Blade: <x-product-gallery :images="..." :slug="..." :product-name="..." />
 * (Alpine state lives in resources/views/components/product-gallery.blade.php)
 */
export function registerProductGallery(Alpine) {
    Alpine.data('productGallery', (images = []) => ({
        images: Array.isArray(images) ? images : [],
        selectedIndex: 0,
        lightbox: false,
        touchStartX: null,

        get len() {
            return this.images.length;
        },

        prev() {
            if (this.len < 2) {
                return;
            }
            this.selectedIndex = (this.selectedIndex - 1 + this.len) % this.len;
        },

        next() {
            if (this.len < 2) {
                return;
            }
            this.selectedIndex = (this.selectedIndex + 1) % this.len;
        },

        onTouchStart(e) {
            this.touchStartX = e.changedTouches[0].screenX;
        },

        onTouchEnd(e) {
            if (this.touchStartX === null || this.len < 2) {
                return;
            }
            const end = e.changedTouches[0].screenX;
            const d = this.touchStartX - end;
            if (d > 48) {
                this.next();
            } else if (d < -48) {
                this.prev();
            }
            this.touchStartX = null;
        },
    }));
}
