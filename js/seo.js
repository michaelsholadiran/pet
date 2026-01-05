;(function () {
	// Basic site configuration. Update SITE_URL to your production domain.
	const SITE_URL = 'https://www.puppiary.com'
	const SITE_NAME = 'Puppiary'
	const DEFAULT_OG_IMAGE = '/products/indestructible-chew-toy/indestructible-chew-toy-1.jpg'
	const THEME_COLOR = '#ffffff'
	const TWITTER_HANDLE = '@puppiaryhq' // update if you have one

	function absoluteUrl(pathWithQuery) {
		try {
			// If already absolute, return as is
			const u = new URL(pathWithQuery)
			return u.toString()
		} catch (_) {
			return new URL(pathWithQuery.replace(/^\.\//, '/'), SITE_URL).toString()
		}
	}

	function ensureLink(rel, href, extraAttrs) {
		let el = document.querySelector(`link[rel="${rel}"]`)
		if (!el) {
			el = document.createElement('link')
			el.setAttribute('rel', rel)
			document.head.appendChild(el)
		}
		el.setAttribute('href', href)
		if (extraAttrs) {
			Object.entries(extraAttrs).forEach(([k, v]) => el.setAttribute(k, v))
		}
		return el
	}

	function ensureMetaByName(name, content) {
		if (content == null || content === '') return null
		let el = document.querySelector(`meta[name="${name}"]`)
		if (!el) {
			el = document.createElement('meta')
			el.setAttribute('name', name)
			document.head.appendChild(el)
		}
		el.setAttribute('content', content)
		return el
	}

	function ensureMetaByProperty(property, content) {
		if (content == null || content === '') return null
		let el = document.querySelector(`meta[property="${property}"]`)
		if (!el) {
			el = document.createElement('meta')
			el.setAttribute('property', property)
			document.head.appendChild(el)
		}
		el.setAttribute('content', content)
		return el
	}

	function setCanonical(pathWithQuery) {
		const href = absoluteUrl(pathWithQuery)
		return ensureLink('canonical', href)
	}

	function setHreflang(lang, pathWithQuery) {
		const href = absoluteUrl(pathWithQuery)
		let el = document.querySelector(`link[rel="alternate"][hreflang="${lang}"]`)
		if (!el) {
			el = document.createElement('link')
			el.setAttribute('rel', 'alternate')
			el.setAttribute('hreflang', lang)
			document.head.appendChild(el)
		}
		el.setAttribute('href', href)
		return el
	}

	function preconnect(url) {
		ensureLink('preconnect', url, { crossorigin: '' })
	}

	function setFavicons() {
		ensureLink('icon', '/favicon.svg', { type: 'image/svg+xml' })
		ensureLink('manifest', '/site.webmanifest')
	}

	function applyCommonSEO(opts) {
		const {
			title,
			description,
			path = window.location.pathname + window.location.search,
			robots = 'index,follow',
			ogImage = DEFAULT_OG_IMAGE,
			ogImageWidth,
			ogImageHeight,
			type = 'website',
		} = opts || {}

		if (title) document.title = title
		if (description) ensureMetaByName('description', description)
		ensureMetaByName('robots', robots)
		ensureMetaByName('theme-color', THEME_COLOR)

		// Canonical and hreflang - skip for noindex pages
		if (!robots.includes('noindex')) {
			setCanonical(path)
			setHreflang('en', path)
			setHreflang('x-default', path)
		} else {
			// Remove any existing canonical tag for noindex pages
			const existingCanonical = document.querySelector('link[rel="canonical"]')
			if (existingCanonical) {
				existingCanonical.remove()
			}
		}

		// Open Graph
		ensureMetaByProperty('og:site_name', SITE_NAME)
		ensureMetaByProperty('og:locale', 'en_US')
		if (title) ensureMetaByProperty('og:title', title)
		if (description) ensureMetaByProperty('og:description', description)
		ensureMetaByProperty('og:type', type)
		ensureMetaByProperty('og:url', absoluteUrl(path))
		if (ogImage) ensureMetaByProperty('og:image', absoluteUrl(ogImage))
		if (ogImageWidth) ensureMetaByProperty('og:image:width', String(ogImageWidth))
		if (ogImageHeight) ensureMetaByProperty('og:image:height', String(ogImageHeight))

		// Twitter
		ensureMetaByName('twitter:card', 'summary_large_image')
		if (TWITTER_HANDLE) ensureMetaByName('twitter:site', TWITTER_HANDLE)
		if (title) ensureMetaByName('twitter:title', title)
		if (description) ensureMetaByName('twitter:description', description)
		if (ogImage) ensureMetaByName('twitter:image', absoluteUrl(ogImage))
	}

	function addJsonLd(obj) {
		const script = document.createElement('script')
		script.type = 'application/ld+json'
		script.text = JSON.stringify(obj)
		document.head.appendChild(script)
		return script
	}

	function jsonLdWebPage({ type = 'WebPage', name, description, url }) {
		return addJsonLd({
			'@context': 'https://schema.org',
			'@type': type,
			name,
			description,
			url: url || SITE_URL,
		})
	}

	function jsonLdOrganization() {
		return addJsonLd({
			'@context': 'https://schema.org',
			'@type': 'Organization',
			name: SITE_NAME,
			url: SITE_URL,
			logo: absoluteUrl('/favicon.svg'),
		})
	}

	function jsonLdWebsite() {
		return addJsonLd({
			'@context': 'https://schema.org',
			'@type': 'WebSite',
			name: SITE_NAME,
			url: SITE_URL,
			potentialAction: {
				'@type': 'SearchAction',
				target: absoluteUrl('/products?search={search_term_string}'),
				'query-input': 'required name=search_term_string',
			},
		})
	}

	function jsonLdBreadcrumb(items) {
		return addJsonLd({
			'@context': 'https://schema.org',
			'@type': 'BreadcrumbList',
			itemListElement: items.map((item, index) => ({
				'@type': 'ListItem',
				position: index + 1,
				name: item.name,
				item: absoluteUrl(item.path),
			})),
		})
	}

	function jsonLdFAQ(questions) {
		// Prevent duplicate FAQPage structured data
		if (window.__faqStructuredDataAdded) {
			console.warn('FAQPage structured data already added, skipping duplicate');
			return null;
		}

		window.__faqStructuredDataAdded = true;

		return addJsonLd({
			'@context': 'https://schema.org',
			'@type': 'FAQPage',
			mainEntity: questions.map((q) => ({
				'@type': 'Question',
				name: q.question,
				acceptedAnswer: {
					'@type': 'Answer',
					text: q.answer
				}
			}))
		})
	}

	// Page bootstrapping
	document.addEventListener('DOMContentLoaded', function () {
		// Preconnect to critical origins
		preconnect('https://fonts.cdnfonts.com')
		preconnect('https://js.paystack.co')
		preconnect('https://cdn.jsdelivr.net')

		// Basic favicons/manifest
		setFavicons()

		const path = window.location.pathname

		// Home
		if (path.endsWith('/') || path.endsWith('/index') || path.endsWith('/index.html')) {
			applyCommonSEO({
				title: 'Puppy Toys, Teething & Starter Kits | Non-Toxic Supplies | Puppiary',
				description:
					'The ultimate resource for new puppy parents. Shop durable chew toys, training gear, and comfort essentials designed to solve teething pain and separation anxiety.',
				path: '/',
			})
			jsonLdOrganization()
			jsonLdWebsite()
		}

		// Products listing
		if (path.endsWith('/products') || path.endsWith('/products.html')) {
			applyCommonSEO({
				title: 'Shop All Products - Puppiary',
				description:
					'Browse our full catalog across categories. Filter by category and search to find your next favorite.',
				path: '/products' + window.location.search,
			})
			jsonLdWebPage({
				type: 'CollectionPage',
				name: 'Shop - Puppiary',
				description:
					'Browse our catalog across categories. Filter by category and search to find your next favorite.',
				url: absoluteUrl('/products'),
			})
		}

		// Product detail: defaults (will be overridden by page once product loads)
		// Check for /product/slug format or /product?slug= format (backward compatibility)
		const isProductPage = path.startsWith('/product/') || path.endsWith('/product') || path.endsWith('/product.html');
		if (isProductPage) {
			// Extract slug from path if using new format
			let productPath = '/product';
			if (path.startsWith('/product/')) {
				const pathParts = path.split('/').filter(part => part);
				if (pathParts.length > 1) {
					productPath = `/product/${pathParts[1]}`;
				}
			} else {
				productPath = '/product' + window.location.search;
			}
			applyCommonSEO({
				title: 'Product Details - Puppiary',
				description:
					'View product photos, specs, pricing and availability. Add to cart with one click.',
				path: productPath,
				type: 'product',
			})
		}

		// Informational pages
		if (path.endsWith('/about') || path.endsWith('/about.html')) {
			applyCommonSEO({
				title: 'About Puppiary',
				description:
					'Learn about Puppiary - your trusted partner for puppy and dog care essentials. Quality, safety, and genuine pet-parent support.',
				path: '/about',
			})
			jsonLdWebPage({
				type: 'AboutPage',
				name: 'About Puppiary',
				description:
					'Learn about Puppiary - your trusted partner for puppy and dog care essentials. Quality, safety, and genuine pet-parent support.',
				url: absoluteUrl('/about'),
			})
		}
		if (path.endsWith('/contact') || path.endsWith('/contact.html')) {
			applyCommonSEO({
				title: 'Contact Puppiary',
				description:
					'Questions or feedback? Get in touch with the Puppiary team - we\'re here to help.',
				path: '/contact',
			})
			jsonLdWebPage({
				type: 'ContactPage',
				name: 'Contact Puppiary',
				description:
					'Questions or feedback? Get in touch with the Puppiary team - we\'re here to help.',
				url: absoluteUrl('/contact'),
			})
			addJsonLd({
				'@context': 'https://schema.org',
				'@type': 'Organization',
				name: SITE_NAME,
				url: SITE_URL,
				contactPoint: [
					{
						'@type': 'ContactPoint',
						contactType: 'customer support',
						email: 'hello@puppiary.com',
						areaServed: 'US',
						availableLanguage: ['English'],
					},
				],
			})
		}
		if (path.endsWith('/faq') || path.endsWith('/faq.html')) {
			applyCommonSEO({
				title: 'FAQ - Puppiary',
				description:
					'Find answers to common questions about Puppiary shipping, ordering, returns, guarantees, and product safety for your puppy.',
				path: '/faq',
			})
			jsonLdWebPage({
				type: 'WebPage',
				name: 'FAQ - Puppiary',
				description:
					'Find answers to common questions about Puppiary shipping, ordering, returns, guarantees, and product safety for your puppy.',
				url: absoluteUrl('/faq'),
			})
		}
		if (path.endsWith('/privacy') || path.endsWith('/privacy.html')) {
			applyCommonSEO({
				title: 'Privacy Policy - Puppiary',
				description:
					'Read how Puppiary collects, uses and protects your personal information. Your trust is essential to our mission.',
				path: '/privacy',
			})
			jsonLdWebPage({
				type: 'WebPage',
				name: 'Privacy Policy - Puppiary',
				description:
					'Read how Puppiary collects, uses and protects your personal information. Your trust is essential to our mission.',
				url: absoluteUrl('/privacy'),
			})
		}
		if (path.endsWith('/terms') || path.endsWith('/terms.html')) {
			applyCommonSEO({
				title: 'Terms & Conditions - Puppiary',
				description:
					'Understand the terms that govern your use of Puppiary and our services.',
				path: '/terms',
			})
			jsonLdWebPage({
				type: 'WebPage',
				name: 'Terms & Conditions - Puppiary',
				description:
					'Understand the terms that govern your use of Puppiary and our services.',
				url: absoluteUrl('/terms'),
			})
		}
		if (path.endsWith('/refund') || path.endsWith('/refund.html')) {
			applyCommonSEO({
				title: 'Return & Refund Policy - Puppiary',
				description:
					'Learn about Puppiary\'s 24-Month Durability Promise and 30-Day Happiness Guarantee. Shop worry-free with our comprehensive return and refund policy.',
				path: '/refund',
			})
			jsonLdWebPage({
				type: 'WebPage',
				name: 'Return & Refund Policy - Puppiary',
				description:
					'Learn about Puppiary\'s 24-Month Durability Promise and 30-Day Happiness Guarantee. Shop worry-free with our comprehensive return and refund policy.',
				url: absoluteUrl('/refund'),
			})
		}
		if (path.endsWith('/cart') || path.endsWith('/cart.html')) {
			applyCommonSEO({
				title: 'Cart - Puppiary',
				description: 'Your shopping cart at Puppiary.',
				path: '/cart',
				robots: 'noindex,nofollow',
			})
		}
		if (path.endsWith('/checkout') || path.endsWith('/checkout.html')) {
			applyCommonSEO({
				title: 'Checkout - Puppiary',
				description: 'Secure checkout at Puppiary.',
				path: '/checkout',
				robots: 'noindex,nofollow',
			})
		}
		if (path.endsWith('/success') || path.endsWith('/success.html')) {
			applyCommonSEO({
				title: 'Order Confirmed - Puppiary',
				description: 'Your order has been confirmed.',
				path: '/success' + window.location.search,
				robots: 'noindex,nofollow',
			})
		}
		if (path.endsWith('/404') || path.endsWith('/404.html')) {
			applyCommonSEO({
				title: '404 - Page Not Found | Puppiary',
				description: 'Page not found - The page you\'re looking for doesn\'t exist. Return to Puppiary home or browse our puppy products.',
				path: '/404',
				robots: 'noindex,nofollow,noarchive',
			})
		}
	})

	// Expose helpers for page-specific SEO (e.g., product.html)
	window.SEO = {
		absoluteUrl,
		applyCommonSEO,
		addJsonLd,
		jsonLdOrganization,
		jsonLdWebsite,
		jsonLdWebPage,
		jsonLdBreadcrumb,
		jsonLdFAQ,
		setCanonical,
		ensureMetaByName,
		ensureMetaByProperty,
	}
})()


