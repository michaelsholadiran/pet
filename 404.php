<?php
http_response_code(404);
$page_title = '404 - Page Not Found | Puppiary';
$page_description = "Page not found - The page you're looking for doesn't exist. Return to Puppiary home or browse our puppy products.";
$page_canonical = '/404';
$robots_noindex = true;
$extra_head = '<style>
.error-404 { min-height: 60vh; display: flex; align-items: center; justify-content: center; padding: 4rem 2rem; text-align: center; }
.error-404-content { max-width: 600px; margin: 0 auto; }
.error-404-number { font-size: 8rem; font-weight: 700; color: var(--primary-orange); line-height: 1; margin-bottom: 1rem; text-shadow: 0 4px 12px rgba(255, 122, 1, 0.2); }
.error-404-title { font-size: 2rem; font-weight: 600; color: var(--text-dark); margin-bottom: 1rem; }
.error-404-message { font-size: 1.125rem; color: #666; margin-bottom: 2.5rem; line-height: 1.6; }
.error-404-actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
@media (max-width: 768px) {
    .error-404-number { font-size: 5rem; }
    .error-404-title { font-size: 1.5rem; }
    .error-404-message { font-size: 1rem; }
    .error-404-actions { flex-direction: column; }
    .error-404-actions .btn { width: 100%; }
}
</style>';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
    <main>
        <section class="error-404">
            <div class="error-404-content">
                <h1 class="error-404-number" aria-label="404">404</h1>
                <h2 class="error-404-title">Oops! Page Not Found</h2>
                <p class="error-404-message">
                    The page you're looking for seems to have wandered off. Don't worry—we'll help you find your way back to our puppy products and resources.
                </p>
                <div class="error-404-actions">
                    <a href="/" class="btn btn-primary">🏠 Go to Homepage</a>
                    <a href="/products" class="btn btn-secondary">🛒 Browse Products</a>
                </div>
            </div>
        </section>
    </main>
<?php require __DIR__ . '/includes/footer.php'; ?>
