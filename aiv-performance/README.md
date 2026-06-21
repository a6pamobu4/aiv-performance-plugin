# AIV Performance

Safe modular performance optimizations for custom WordPress projects.

AIV Performance is intentionally conservative. It removes a few low-risk frontend assets, exposes developer filters for project-specific tuning, and avoids broad guesses that can break custom Gutenberg blocks, WooCommerce, forms, checkout, admin, logged-in workflows, or editor screens.

## What it does

- Disables WordPress emoji frontend assets.
- Disables the `wp-embed` frontend script.
- Removes the WordPress generator meta tag.
- Removes shortlink and adjacent post links from the frontend head.
- Provides filters for resource hints.
- Provides a safe `defer` helper for explicitly selected script handles.
- Provides filters to dequeue explicitly selected styles.
- Provides image attribute filters for known LCP or lazy-loading exceptions.
- Provides conservative WooCommerce cart fragments handling, disabled by default.

## What it does not do

- No page caching.
- No object caching.
- No CDN integration.
- No image compression.
- No CSS or JS minification.
- No critical CSS generation.
- No background-image lazy loading.
- No server config changes.
- No database option changes.
- No aggressive one-click optimization.

Good hosting, caching, CDN configuration, image compression, and careful theme/block development remain separate responsibilities.

## Why modules are filterable

Performance optimizations are site-specific. A safe optimization for one custom theme can break another project if it changes asset order, checkout scripts, form behavior, or editor assumptions. Each module can be disabled with a filter:

```php
add_filter( 'aiv_performance_enable_emojis_optimization', '__return_false' );
add_filter( 'aiv_performance_enable_embeds_optimization', '__return_false' );
add_filter( 'aiv_performance_enable_wp_version_optimization', '__return_false' );
add_filter( 'aiv_performance_enable_shortlink_optimization', '__return_false' );
add_filter( 'aiv_performance_enable_resource_hints', '__return_false' );
add_filter( 'aiv_performance_enable_scripts_optimization', '__return_false' );
add_filter( 'aiv_performance_enable_styles_optimization', '__return_false' );
add_filter( 'aiv_performance_enable_images_optimization', '__return_false' );
add_filter( 'aiv_performance_enable_woocommerce_optimization', '__return_false' );
```

Each module can also be disabled with a constant before plugins load:

```php
define( 'AIV_PERFORMANCE_ENABLE_SCRIPTS_OPTIMIZATION', false );
```

Available module constants:

- `AIV_PERFORMANCE_ENABLE_EMOJIS_OPTIMIZATION`
- `AIV_PERFORMANCE_ENABLE_EMBEDS_OPTIMIZATION`
- `AIV_PERFORMANCE_ENABLE_WP_VERSION_OPTIMIZATION`
- `AIV_PERFORMANCE_ENABLE_SHORTLINK_OPTIMIZATION`
- `AIV_PERFORMANCE_ENABLE_RESOURCE_HINTS`
- `AIV_PERFORMANCE_ENABLE_SCRIPTS_OPTIMIZATION`
- `AIV_PERFORMANCE_ENABLE_STYLES_OPTIMIZATION`
- `AIV_PERFORMANCE_ENABLE_IMAGES_OPTIMIZATION`
- `AIV_PERFORMANCE_ENABLE_WOOCOMMERCE_OPTIMIZATION`

## Defer selected scripts

Scripts are never deferred automatically. Add only handles you own or have tested. jQuery and common WooCommerce cart/checkout handles are blocked by default.

```php
add_filter(
	'aiv_performance_defer_script_handles',
	function ( array $handles ): array {
		$handles[] = 'theme-navigation';
		$handles[] = 'custom-block-accordion';

		return $handles;
	}
);
```

Test forms, consent scripts, analytics, menus, sliders, and any interactive blocks after adding a handle.

## Dequeue selected styles

The plugin does not globally dequeue `wp-block-library` or block styles in v1. Removing block styles can break Gutenberg output and custom blocks.

```php
add_filter(
	'aiv_performance_dequeue_style_handles',
	function ( array $handles ): array {
		$handles[] = 'unused-plugin-frontend-style';

		return $handles;
	}
);
```

## Resource hints

No external domains or preloads are hard-coded. Add only domains and assets your project actually needs:

```php
add_filter(
	'aiv_performance_resource_hints',
	function ( array $urls, string $relation_type ): array {
		if ( 'preconnect' === $relation_type ) {
			$urls[] = array(
				'href'        => 'https://fonts.gstatic.com',
				'crossorigin' => 'anonymous',
			);
		}

		if ( 'preload' === $relation_type ) {
			$urls[] = array(
				'href' => get_stylesheet_directory_uri() . '/assets/fonts/site.woff2',
				'as'   => 'font',
				'type' => 'font/woff2',
				'crossorigin' => 'anonymous',
			);
		}

		return $urls;
	},
	10,
	2
);
```

## Image attributes

WordPress already lazy-loads many images. AIV Performance does not guess the LCP image. Configure known exceptions by attachment ID or class:

```php
add_filter(
	'aiv_performance_lazy_load_excluded_attachment_ids',
	function ( array $ids ): array {
		$ids[] = 123;

		return $ids;
	}
);

add_filter(
	'aiv_performance_fetchpriority_high_classes',
	function ( array $classes ): array {
		$classes[] = 'hero-image';

		return $classes;
	}
);
```

## WooCommerce

WooCommerce optimizations are conservative and disabled by default. Cart fragments are never removed from cart, checkout, account, or WooCommerce catalog/product pages.

Enable cart fragments removal only for projects without a dynamic mini-cart outside WooCommerce pages:

```php
add_filter( 'aiv_performance_optimize_woocommerce_assets', '__return_true' );
add_filter( 'aiv_performance_disable_cart_fragments_on_non_wc_pages', '__return_true' );
```

Always test product pages, cart, checkout, account pages, payment methods, coupons, shipping calculators, and any mini-cart.

## Testing checklist

- Homepage.
- Landing page.
- Blog post.
- Contact form.
- WooCommerce product.
- Cart.
- Checkout.
- Logged-in user.
- Mobile PageSpeed.
- Desktop PageSpeed.
