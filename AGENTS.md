# AIV Performance Development Rules

## Source of Truth

Use current WordPress Developer Resources as the primary source of truth:

- Plugin Handbook.
- Code Reference.
- Theme Handbook when behavior touches frontend output.
- Block Editor Handbook when behavior could affect the editor.
- WordPress Coding Standards.

Do not rely on outdated WordPress Codex pages when current Developer Resources are available.

## Standards

- Follow WordPress Coding Standards for PHP, documentation, escaping, sanitization, and internationalization.
- Target PHP 8.1+.
- Use the `aiv-performance` text domain for user-facing strings.
- Prefix functions and filters with `aiv_performance_`.
- Prefix constants with `AIV_PERFORMANCE_`.
- Keep runtime dependencies out of v1.

## Performance Approach

AIV Performance is conservative and project-developer oriented. It should expose safe hooks and small defaults, not aggressive automated optimization.

Do not add these features in v1:

- Page caching or object caching.
- CSS or JavaScript minification.
- Critical CSS generation.
- Image compression or automatic image rewriting.
- Database cleanup.
- CDN logic.
- Server configuration changes.
- Heavy admin settings UI.

Do not add jQuery, Bootstrap, Tailwind, external fonts, external icons, sliders, animation libraries, or external runtime dependencies.

## Safety Rules

- Do not break WooCommerce, forms, checkout, payment scripts, the mini-cart, block editor, REST API, AJAX, cron, logged-in workflows, or wp-admin.
- Run frontend optimizations only after checking `aiv_performance_is_frontend_request()`.
- All modules must be toggleable with `aiv_performance_enable_{$module}_module` filters.
- Scripts, styles, resource hints, and image changes must be opt-in by developer filter unless the behavior is a small safe WordPress cleanup.
- Do not globally dequeue block styles, theme styles, WooCommerce styles, or form plugin styles.
- Do not remove canonical tags, SEO plugin output, or REST API links by default.

## Verification

- Run `composer run lint:php` after PHP changes.
- Run `composer run fix:php` only for intentional formatting fixes.
- After behavior changes, manually test homepage, landing pages, posts, contact forms, logged-in views, block editor, REST API, WooCommerce product pages, cart, checkout, and account pages.
