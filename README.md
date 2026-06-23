# AIV Performance

Safe modular performance optimizations for custom WordPress projects.

AIV Performance is intentionally conservative. It removes a few low-risk frontend outputs and exposes developer filters for project-specific tuning. It is not a one-click optimization plugin and does not guess which assets or images are safe to change.

## What It Does

- Disables WordPress emoji frontend assets.
- Disables the `wp-embed` script on frontend requests.
- Removes safe unnecessary head output: generator meta, shortlink, RSD link, WLW manifest link, and adjacent post links.
- Provides opt-in resource hint filters with no hard-coded domains.
- Provides opt-in `defer` handling for explicitly selected script handles.
- Provides opt-in style dequeue handling for explicitly selected style handles.
- Provides minimal image attribute hooks for known images.
- Provides conservative WooCommerce cart fragments handling, disabled by default.

## What It Does Not Do

- No caching.
- No minification.
- No critical CSS generation.
- No image compression.
- No automatic LCP image detection.
- No database cleanup.
- No CDN logic.
- No server configuration changes.
- No heavy admin UI.
- No external runtime dependencies.

## Installation

1. Copy this repository directory to `wp-content/plugins/aiv-performance`.
2. Activate **AIV Performance** in WordPress.
3. Add project-specific filters in the theme, an mu-plugin, or a site plugin after testing each change.

## Modules

All modules are enabled by default unless noted, and every module can be disabled by filter:

```php
add_filter( 'aiv_performance_enable_emojis_module', '__return_false' );
add_filter( 'aiv_performance_enable_embeds_module', '__return_false' );
add_filter( 'aiv_performance_enable_head_cleanup_module', '__return_false' );
add_filter( 'aiv_performance_enable_resource_hints_module', '__return_false' );
add_filter( 'aiv_performance_enable_scripts_module', '__return_false' );
add_filter( 'aiv_performance_enable_styles_module', '__return_false' );
add_filter( 'aiv_performance_enable_images_module', '__return_false' );
add_filter( 'aiv_performance_enable_woocommerce_module', '__return_false' );
```

### Emojis

Removes frontend emoji detection scripts, emoji styles, and related filters. It does not change stored content.

### Embeds

Dequeues and deregisters the frontend `wp-embed` script. Admin and editor oEmbed behavior is left alone.

### Head Cleanup

Removes safe, low-value head output. Canonical tags, SEO plugin output, and REST API links stay enabled by default.

REST links can be removed only with an explicit opt-in:

```php
add_filter( 'aiv_performance_remove_rest_api_head_links', '__return_true' );
```

### Resource Hints

No domains or assets are added by default.

```php
add_filter(
	'aiv_performance_preconnect_urls',
	function ( array $urls ): array {
		$urls[] = array(
			'href'        => 'https://example.com',
			'crossorigin' => 'anonymous',
		);

		return $urls;
	}
);

add_filter(
	'aiv_performance_dns_prefetch_urls',
	function ( array $urls ): array {
		$urls[] = '//example.com';

		return $urls;
	}
);

add_filter(
	'aiv_performance_preload_assets',
	function ( array $assets ): array {
		$assets[] = array(
			'href'        => get_stylesheet_directory_uri() . '/assets/fonts/site.woff2',
			'as'          => 'font',
			'type'        => 'font/woff2',
			'crossorigin' => 'anonymous',
		);

		return $assets;
	}
);
```

### Scripts

Scripts are never deferred automatically. Add only handles you own or have tested.

```php
add_filter(
	'aiv_performance_defer_script_handles',
	function ( array $handles ): array {
		$handles[] = 'theme-navigation';

		return $handles;
	}
);
```

jQuery and common WooCommerce cart, checkout, payment, and product scripts are blocked from defer by default.

### Styles

Styles are never dequeued automatically. Add only handles you own or have tested.

```php
add_filter(
	'aiv_performance_dequeue_style_handles',
	function ( array $handles ): array {
		$handles[] = 'unused-plugin-frontend-style';

		return $handles;
	}
);
```

Core block styles, global styles, and common WooCommerce styles are blocked from dequeue by default.

### Images

The plugin does not automatically detect the LCP image or rewrite every image. Use filters for known images.

```php
add_filter(
	'aiv_performance_fetchpriority_high_attachment_ids',
	function ( array $ids ): array {
		$ids[] = 123;

		return $ids;
	}
);

add_filter(
	'aiv_performance_lazy_load_excluded_classes',
	function ( array $classes ): array {
		$classes[] = 'hero-image';

		return $classes;
	}
);

add_filter( 'aiv_performance_image_decoding_attribute', fn() => 'async' );
```

### WooCommerce

The WooCommerce module only runs when WooCommerce is active. Cart fragments are not disabled by default because they often power mini-cart and AJAX cart behavior.

Enable cart fragment removal only when the project does not need dynamic mini-cart behavior outside WooCommerce pages:

```php
add_filter( 'aiv_performance_optimize_woocommerce_assets', '__return_true' );
add_filter( 'aiv_performance_disable_cart_fragments_on_non_wc_pages', '__return_true' );
```

Cart fragments are never removed from cart, checkout, account, product, product category, or product tag pages.

## Testing Checklist

- Homepage.
- Landing page.
- Blog post.
- Contact form.
- Logged-in user.
- Block editor.
- REST API endpoint.
- WooCommerce product page.
- Cart.
- Checkout.
- Account page.
