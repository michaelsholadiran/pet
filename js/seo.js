;(function () {
	// Basic site configuration. Update SITE_URL to your production domain.
	const SITE_URL = 'https://www.puppiary.com'
	const SITE_NAME = 'Puppiary'
	const DEFAULT_OG_IMAGE = 'https://picsum.photos/seed/puppiary/1200/630'
	const THEME_COLOR = '#ffffff'
	const TWITTER_HANDLE = '@puppiary' // update if you have one

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

		// Canonical and hreflang
		setCanonical(path)
		setHreflang('en', path)
		setHreflang('x-default', path)

		// Open Graph
		ensureMetaByProperty('og:site_name', SITE_NAME)
		ensureMetaByProperty('og:locale', 'en_US')
		if (title) ensureMetaByProperty('og:title', title)
		if (description) ensureMetaByProperty('og:description', description)
		ensureMetaByProperty('og:type', type)
		ensureMetaByProperty('og:url', absoluteUrl(path))
		if (ogImage) ensureMetaByProperty('og:image', ogImage)
		if (ogImageWidth) ensureMetaByProperty('og:image:width', String(ogImageWidth))
		if (ogImageHeight) ensureMetaByProperty('og:image:height', String(ogImageHeight))

		// Twitter
		ensureMetaByName('twitter:card', 'summary_large_image')
		if (TWITTER_HANDLE) ensureMetaByName('twitter:site', TWITTER_HANDLE)
		if (title) ensureMetaByName('twitter:title', title)
		if (description) ensureMetaByName('twitter:description', description)
		if (ogImage) ensureMetaByName('twitter:image', ogImage)
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
				target: absoluteUrl('/products.html?search={search_term_string}'),
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
		return addJsonLd({
			'@context': 'https://schema.org',
			'@type': 'FAQPage',
			mainEntity: questions.map((q) => ({
				'@type': 'Question',
				name: q.question,
				acceptedAnswer: { '@type': 'Answer', text: q.answer },
			})),
		})
	}

	// Page bootstrapping
	document.addEventListener('DOMContentLoaded', function () {
		// Preconnect to image CDN used in this project
		preconnect('https://picsum.photos')
		preconnect('https://cdn.shopify.com')
		preconnect('https://fonts.cdnfonts.com')
		preconnect('https://js.paystack.co')
		preconnect('https://cdn.jsdelivr.net')

		// Basic favicons/manifest
		setFavicons()

		const path = window.location.pathname

		// Home
		if (path.endsWith('/') || path.endsWith('/index.html')) {
			applyCommonSEO({
				title: 'Puppy Toys, Teething & Starter Kits | Non-Toxic Supplies | Puppiary',
				description:
					'The ultimate resource for new puppy parents. Shop durable chew toys, training gear, and comfort essentials designed to solve teething pain and separation anxiety.',
				path: '/index.html',
			})
			jsonLdOrganization()
			jsonLdWebsite()
		}

		// Products listing
		if (path.endsWith('/products.html')) {
			applyCommonSEO({
				title: 'Shop All Products - Puppiary',
				description:
					'Browse our full catalog across categories. Filter by category and search to find your next favorite.',
				path: '/products.html' + window.location.search,
			})
			jsonLdWebPage({
				type: 'CollectionPage',
				name: 'Shop - Puppiary',
				description:
					'Browse our catalog across categories. Filter by category and search to find your next favorite.',
				url: absoluteUrl('/products.html'),
			})
		}

		// Product detail: defaults (will be overridden by page once product loads)
		if (path.endsWith('/product.html')) {
			applyCommonSEO({
				title: 'Product Details - Puppiary',
				description:
					'View product photos, specs, pricing and availability. Add to cart with one click.',
				path: '/product.html' + window.location.search,
				type: 'product',
			})
		}

		// Informational pages
		if (path.endsWith('/about.html')) {
			applyCommonSEO({
				title: 'About Puppiary',
				description:
					'Learn about Puppiary - your trusted partner for puppy and dog care essentials. Quality, safety, and genuine pet-parent support.',
				path: '/about.html',
			})
			jsonLdWebPage({
				type: 'AboutPage',
				name: 'About Puppiary',
				description:
					'Learn about Puppiary - your trusted partner for puppy and dog care essentials. Quality, safety, and genuine pet-parent support.',
				url: absoluteUrl('/about.html'),
			})
		}
		if (path.endsWith('/contact.html')) {
			applyCommonSEO({
				title: 'Contact Puppiary',
				description:
					'Questions or feedback? Get in touch with the Puppiary team - we\'re here to help.',
				path: '/contact.html',
			})
			jsonLdWebPage({
				type: 'ContactPage',
				name: 'Contact Puppiary',
				description:
					'Questions or feedback? Get in touch with the Puppiary team - we\'re here to help.',
				url: absoluteUrl('/contact.html'),
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
		if (path.endsWith('/faq.html')) {
			applyCommonSEO({
				title: 'FAQ - Puppiary',
				description:
					'Find answers to common questions about Puppiary shipping, ordering, returns, guarantees, and product safety for your puppy.',
				path: '/faq.html',
			})
			jsonLdWebPage({
				type: 'FAQPage',
				name: 'FAQ - Puppiary',
				description:
					'Find answers to common questions about Puppiary shipping, ordering, returns, guarantees, and product safety for your puppy.',
				url: absoluteUrl('/faq.html'),
			})
		}
		if (path.endsWith('/privacy.html')) {
			applyCommonSEO({
				title: 'Privacy Policy - Puppiary',
				description:
					'Read how Puppiary collects, uses and protects your personal information. Your trust is essential to our mission.',
				path: '/privacy.html',
			})
			jsonLdWebPage({
				type: 'WebPage',
				name: 'Privacy Policy - Puppiary',
				description:
					'Read how Puppiary collects, uses and protects your personal information. Your trust is essential to our mission.',
				url: absoluteUrl('/privacy.html'),
			})
		}
		if (path.endsWith('/terms.html')) {
			applyCommonSEO({
				title: 'Terms & Conditions - Puppiary',
				description:
					'Understand the terms that govern your use of Puppiary and our services.',
				path: '/terms.html',
			})
			jsonLdWebPage({
				type: 'WebPage',
				name: 'Terms & Conditions - Puppiary',
				description:
					'Understand the terms that govern your use of Puppiary and our services.',
				url: absoluteUrl('/terms.html'),
			})
		}
		if (path.endsWith('/refund.html')) {
			applyCommonSEO({
				title: 'Return & Refund Policy - Puppiary',
				description:
					'Learn about Puppiary\'s 24-Month Durability Promise and 30-Day Happiness Guarantee. Shop worry-free with our comprehensive return and refund policy.',
				path: '/refund.html',
			})
			jsonLdWebPage({
				type: 'WebPage',
				name: 'Return & Refund Policy - Puppiary',
				description:
					'Learn about Puppiary\'s 24-Month Durability Promise and 30-Day Happiness Guarantee. Shop worry-free with our comprehensive return and refund policy.',
				url: absoluteUrl('/refund.html'),
			})
		}
		if (path.endsWith('/cart.html')) {
			applyCommonSEO({
				title: 'Cart - Puppiary',
				description: 'Your shopping cart at Puppiary.',
				path: '/cart.html',
				robots: 'noindex,nofollow',
			})
		}
		if (path.endsWith('/checkout.html')) {
			applyCommonSEO({
				title: 'Checkout - Puppiary',
				description: 'Secure checkout at Puppiary.',
				path: '/checkout.html',
				robots: 'noindex,nofollow',
			})
		}
		if (path.endsWith('/success.html')) {
			applyCommonSEO({
				title: 'Order Confirmed - Puppiary',
				description: 'Your order has been confirmed.',
				path: '/success.html' + window.location.search,
				robots: 'noindex,nofollow',
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


