# Telson Optics: WordPress + WooCommerce theme

A custom theme rebuilt from an audit of https://telsonoptics.com/ (a rifle-scope and tactical-optics brand, dark UI, olive header, tan accents, Goldman + Oswald). No page builder. Content is managed with ACF Pro and WooCommerce.

## Requirements

| Item | Version |
| --- | --- |
| WordPress | 6.4+ |
| PHP | 8.0+ |
| WooCommerce | 9+ (tested on 11.1) |
| ACF Pro | 6+ (options page and flexible content are Pro features) |
| Gravity Forms | any current version, for the newsletter, contact and pro-staff-application forms |

Optional: Rank Math or Yoast SEO (the theme defers breadcrumbs and schema to them), Smash Balloon for the Instagram feed.

## Installation

1. Copy the `telson-optics` folder to `wp-content/themes/` and activate it.
2. Install and activate **WooCommerce** and **ACF Pro**.
3. On activation the theme:
   - flushes rewrite rules (registers the `pro-staff` archive),
   - converts the Cart and Checkout pages to the classic `[woocommerce_cart]` / `[woocommerce_checkout]` shortcodes **if they still hold WooCommerce's default block markup**, so the theme controls those layouts.
4. Go to **Settings → Permalinks** and save once.
5. ACF field groups load automatically from `acf-json/`. If ACF shows "Sync available", click *Sync*.
6. Create menus (see below), fill in **Telson Settings**, set the homepage (see below).

## What lives where

| Content | Where to edit |
| --- | --- |
| Logo, announcement bar, age gate, contact info, social links, footer text, newsletter, shop filter attributes, promise strip, product support box | **Telson Settings** (ACF options page) |
| Header and footer navigation | **Appearance → Menus** |
| Homepage and marketing pages | **Pages → edit → "Page sections"** (ACF flexible content) |
| Products, categories, attributes, prices, stock, reviews, orders | WooCommerce |
| Product key features, specs, PDF, badge text | **Product edit screen → "Product details"** |
| Pro staff | **Pro Staff** post type |
| Blog | Posts |
| Site icon | Appearance → Customize → Site Identity |

## ACF field groups (`acf-json/`)

| File | Location rule | Purpose |
| --- | --- | --- |
| `group_telson_options.json` | Options page `telson-settings` | Global settings, in tabs: Branding, Header, Age gate, Contact, Social, Footer, Newsletter, Shop, Product page |
| `group_telson_page_sections.json` | Post type = page | Flexible content field `sections`, plus "hide page title" |
| `group_telson_product.json` | Post type = product | `badge_text`, `key_features`, `specifications` (repeater), `spec_pdf` |
| `group_telson_pro_staff.json` | Post type = pro_staff | `staff_role`, `staff_links` |
| `group_telson_dealer.json` | Post type = dealer | `address`, `phone`, `website` |

### Section layouts

`hero`, `category_tiles`, `featured_products`, `series_showcase`, `image_text`, `banner`, `cta_links`, `testimonials`, `social_feed`, `newsletter`, `content_section`, `gallery`, `logo_grid`, `stats`, `model_showcase`.

Each layout renders from `template-parts/sections/{layout}.php`. To add a layout, add it to the flexible content field in ACF and create a template with the same name.

## Editing the homepage

1. **Settings → Reading → Your homepage displays → A static page**, and pick a page (for example "Home").
2. Edit that page and add sections. A layout matching the live site, in order:
   1. `hero`: kicker "Premium Riflescope, Optics & Gear", heading "Built for the Bold", button, background image (optional mobile crop).
   2. `category_tiles`: Tripods, Rifle Scope, RDS Custom Turrets, Mounts, Binoculars, Apparel, Accessories (link title is the tile label).
   3. `featured_products`: source *Hand-picked* or *A category*, carousel layout, "View All Products" button.
   4. `series_showcase`: The Scope Family (Target Master, ARC, Toxin, each with model links).
   5. `image_text` ×2: Tripods and Accessories (optional background image).
   6. `banner`: Range. Dial. Shoot.
   7. `cta_links`: Our Pro Staff, Become a Pro Staff, Product Reviews, About.
   8. `testimonials`, then `social_feed` (paste the Smash Balloon shortcode).
3. The footer newsletter ("Join the Bold") comes from Telson Settings, not from sections.

## Global settings (Telson Settings)

- **Branding**: header logo and footer/age-gate logo (falls back to a text wordmark).
- **Header**: announcement bar on/off, text, link (dismissible per session).
- **Age gate**: on/off, copy, button labels, redirect URL for under-age visitors. Remembered 30 days in a first-party cookie. Client-side, so it works with full-page caching.
- **Contact / Social / Footer**: phone, email, address; social repeater; footer description, copyright (`{year}` token), credit line.
- **Newsletter**: heading, description, form shortcode — paste `[gravityforms id="1" title="false" description="false" ajax="true"]` here once the Gravity Forms form is built. Leave the shortcode empty to use the built-in fallback form (nonce + honeypot + `wp_mail` to the notification address).
- **Shop**: which product attributes appear in the filter, and the warranty/promise strip.
- **Product page**: the "Do you have a question?" support box.

## Navigation

Register menus in **Appearance → Menus**:

| Location | Notes |
| --- | --- |
| Primary menu | Top-level items with children become dropdowns (desktop) or an accordion (mobile). Depth 2. |
| Footer: Products / Learn / Info | The **menu name** becomes the column title. |
| Footer: legal bar | Privacy, Legal Information, Return Policy. |

## WooCommerce setup

- **Attributes for filtering**: create attributes under *Products → Attributes* (for example Objective diameter, Magnification, Reticle type, Parallax distance). They appear automatically in the shop filter, or choose a subset in Telson Settings → Shop.
- **Filtering** is real: the filter form submits category (`telson_cat[]`), attribute (`telson_attr[name][]`) and price (`min_price`/`max_price`) parameters that become a WooCommerce tax/price query. Attribute terms within one attribute are OR'd; different filters are AND'd.
- **"Pairs well with"** on the product page is the product's **Upsells** (Linked Products tab). Each row has a one-click AJAX add button.
- **Recommended products** are WooCommerce related products.
- **Cart drawer**: opens after any AJAX add-to-cart and from the header. WooCommerce fragments keep it and the header count in sync.
- **Store mode**: on new installs WooCommerce may be in "coming soon" mode; turn it off under *WooCommerce → Settings → Site visibility*.
- Products with a `badge_text` show it on cards ("New", "Coming soon").

## Currency switcher (display only)

Telson Settings → Currency lets you add up to six extra currencies (code, label, manual rate). When enabled, a plain `<select>` appears in the header; choosing one sets a first-party cookie (`telson_currency`, redirect-after-set, so it works fine behind page caching) and every catalogue, category and cart price gets a small "≈ CAD $275.93" line appended under it via `woocommerce_get_price_html`.

This is deliberately display-only, not a real multi-currency system:

- The number is a manual rate × the store price — no live FX feed, no per-gateway settlement currency.
- Checkout and the actual charge always use WooCommerce's own store currency (Settings → General); the filter explicitly skips `is_checkout()`.
- It exists to replace a "show an approximate price" plugin (e.g. YITH Multi Currency Switcher) when that's all you need. If you need customers to actually pay in their own currency (separate merchant settlement, gateway-side conversion), that's a real integration, not something to fake in the theme — keep a dedicated plugin, or your payment gateway's own multi-currency setting, for that.

## Gravity Forms

The theme has no Gravity Forms template overrides — it only makes a dropped-in form look native:

- `inc/gravity-forms.php` sets `gform_disable_css` to `true`, so Gravity Forms' own stylesheets (orbital/foundation/framework/reset) never load, and enqueues `assets/css/gravity-forms.css`, which restyles Gravity Forms' own markup (`.gform_wrapper`, `.gfield`, `.gform_button`, validation and confirmation messages) with the theme's tokens.
- Because that CSS uses the same custom properties as the rest of the theme, a form placed inside a dark section or the footer picks up light text automatically — no separate dark-mode form styles needed.
- Where to put each form:
  | Form | Where |
  | --- | --- |
  | Newsletter ("Join the Bold") | Telson Settings → Newsletter → *Form shortcode* |
  | Contact | Paste the shortcode into the **Contact Us** page content |
  | Pro Staff Application | Paste the shortcode into the **Pro Staff Application** page content |
  | Product "Do you have a question?" | This is a button (Telson Settings → Product page → *Support box button*) linking to the Contact page — point it at the page with your Gravity Forms contact form |
- Confirmations, notifications and conditional logic are configured in Gravity Forms as usual; the theme doesn't touch them.

## WooCommerce template overrides (`woocommerce/`)

Everything else is done with hooks in `inc/woocommerce.php`.

| File | Why |
| --- | --- |
| `woocommerce/archive-product.php` | Adds the slide-out filter panel, toolbar and promise strip around the standard loop hooks. |
| `woocommerce/content-product.php` | Delegates to `template-parts/products/product-card.php`, the single reusable card. |
| `woocommerce/cart/cart.php` | Provides the standard, correctly-classed (`shop_table`) cart table. Every standard hook is intact, so per-item data any plugin adds via `woocommerce_get_item_data` (e.g. Advanced Product Fields' selected option) still shows. **Root cause found on staging (2026-09-30):** this override was never the actual problem — the Cart *page's content in the database* had been overwritten at some point with a frozen, hand-baked snapshot of rendered cart HTML (a literal `<h1>CART</h1>`, a classless `<table>`, even a stale hard-coded nonce) instead of the `[woocommerce_cart]` shortcode. No plugin was intercepting `woocommerce_locate_template` — there was simply no shortcode left in the page to invoke it. **Fix:** edit the Cart page (Pages → Cart → Text/Code view) and make sure its content is exactly `[woocommerce_cart]`. If any other WooCommerce page (Shop, Checkout, My Account) ever shows unstyled or frozen output again, check its page content the same way before suspecting a plugin conflict. |

The product card is also used on the homepage, search results, related products and the cart page.

## Theme structure

```
inc/                 setup, enqueue, post types, ACF, helpers, template tags, hooks, search REST route, WooCommerce, Gravity Forms
template-parts/      header, footer, components, products, content, sections (one file per flexible layout)
assets/css           base (tokens, reset, buttons, forms), components, sections, woocommerce, gravity-forms
assets/js            core (dialogs, nav, age gate), search, cart, shop, sections. Vanilla JS, no libraries
acf-json/            version-controlled field groups
woocommerce/         two documented overrides
```

Design tokens (all in `assets/css/base.css`, measured from the live site's computed styles and Elementor kit):

| Role | Value |
| --- | --- |
| Page background | `#C5C3BB` (light warm grey; the live site is **not** dark mode) |
| Body / heading text | `#222` / `#000` (product titles `#212121`) |
| Header, footer, buttons hover, accent | `#41453B` (olive) |
| Primary (tan) | `#AA9677`, used as the accent on dark bands and in the age-gate button |
| Dark bands (Tripods, Pro Staff, Reviews, promise strip, footer bar) | `#222` with `#C5C3BB` text and tan headings |
| Cards | transparent, 1px `#222` border, `#212121` title, `#222` buttons with `#C5C3BB` text and `#666` border |
| Panels, inputs, cart drawer, age gate | `#F1F0EF` |

Headings use Goldman and body text Oswald. Container is 1452px, corners are square, breakpoints are 1024 and 767.

Dark contexts (header, footer, hero, banners, `.section--dark`) re-declare the semantic tokens, so any component placed inside them adapts automatically. The homepage sections `image_text`, `cta_links`, `testimonials`, `content_section` and `stats` have a **Background** option (light or dark) in ACF.

Two deliberate contrast deviations from the live colors: breadcrumbs and links on the light grey use olive `#41453B` instead of tan (tan on `#C5C3BB` is about 1.5:1), and the tan buttons use dark `#222` text instead of light text.

## Search

The overlay calls `GET /wp-json/telson/v1/search?q=` (products plus pages, posts and pro staff, published only, query 2 to 80 characters). The results page (`search.php`) groups products (shared card) and other content.

## Performance and accessibility notes

- Assets are versioned by file mtime. Styles load conditionally: sections CSS only on pages, WooCommerce CSS only when WooCommerce is active. Scripts are deferred, no jQuery dependency except `cart.js` (WooCommerce already loads it).
- Images use `wp_get_attachment_image()` (responsive `srcset`), hero is eager and high priority, the rest lazy.
- Fonts come from Google Fonts with `display=swap` and preconnect. For strict privacy, self-host Goldman and Oswald in `assets/fonts` and replace the `telson-fonts` enqueue in `inc/enqueue.php`.
- Skip link, visible focus rings, focus-trapped dialogs (search, cart, age gate), Escape to close, `aria-expanded` on toggles, labelled forms, `prefers-reduced-motion` respected. Single `<h1>` per template.

## Differences from the live site (deliberate or not yet done)

- The live mega menu is rebuilt as a standard WordPress dropdown/accordion, managed from Appearance → Menus.
- Product images, hero imagery and copy are not bundled; upload them in the Media Library and enter the copy in ACF. The live homepage copy is listed above as a guide.
- The live site's `/contact/` page has always returned 403 to automated fetching, so it was never audited. Build it as a normal page with your Gravity Forms shortcode.
- No product "quick view" modal is built in (the live site's comes from a plugin — see below). Add one later if you need it; it isn't required for checkout to work.

## Full site inventory (from the live sitemap, not just nav links)

The initial audit only followed pages linked from the header/footer nav. Pulling the actual XML sitemaps (`/page-sitemap.xml` etc.) surfaced the real count: **40 static pages, 28 products, 19 blog posts, 22 pro-staff profiles.** Products, posts and pro-staff need no theme work — the existing templates handle any number of them. The 40 pages break down like this:

| Page(s) | What it needs |
| --- | --- |
| Home, About Us, Product Review, Digital Manuals, Returns Policy, Legal Information, Privacy Policy, Warranty, Downloads, Military & Law Enforcement, Join the Bold, Shop/Cart/Checkout/My Account, Pro Staff archive | Standard page sections or content — no special widgets. Military & Law Enforcement is plain informational content with a manual apply process; it does **not** use a third-party verification service like SheerID or ID.me. |
| Find a Dealer, Become a Dealer | **New**: Dealer Directory (below). "Become a Dealer" is a Gravity Forms application (Business Info / Personal Info) — a normal page with the form shortcode, like Contact. |
| `/showcase/` + 4 child pages (`tmv-5-32x56`, `tm-5-25x56`, `toxin-3-18x50`, `arc-2-5-15x44-ir-sfp`) | **New**: `model_showcase` section (below) for the child pages. The parent listing page just uses the existing `category_tiles` layout, linking to each child. |
| `premium-optics-shop`, `premium-optics-shop-3`, `preorder-store`, `coming-soon` | All four show the same rifle-scope carousel as the homepage — almost certainly old ad-campaign landing pages. Buildable with existing hero/featured-products/series-showcase sections; worth asking the client whether they're still live campaigns or safe to retire. |
| `b2b-registration`, and the `/my-account/` sub-pages (`dashboard`, `saved-items`, `warranty-request`, `order-history`, `address-book`, `account-information`, `inside-newsletter`) | B2BKing's own account area — dynamic, plugin-rendered. Leave these as-is; they're not static content to migrate. |
| `Compare` | **Unresolved.** No comparison-plugin markup or product-compare classes showed up in a static fetch, and the page has no real content besides its title — can't tell from outside whether it's a JS-rendered tool, unfinished, or something else. Check its raw `_elementor_data` (or ask the client what currently powers it) before deciding whether to rebuild or drop it. |
| `opt-out-preferences` | Standard CCPA "do not sell" page, almost certainly rendered by Complianz — keep that plugin, no theme work needed. |

## Dealer Directory (new)

A `dealer` custom post type (`inc/post-types.php`) with three fields (`acf-json/group_telson_dealer.json`): Address, Phone, Website. No map/store-locator plugin required — the live site's own "Find a Dealer" page turned out to be nothing more than a plain list of dealers, each with a static `https://www.google.com/maps/search/?api=1&query=...` link, and `page-dealer-directory.php` reproduces exactly that (the map link is generated automatically from the Address field).

To use it: add each dealer under **Dealers** in the admin menu, then on the **Find a Dealer** page set **Page Attributes → Template → Dealer Directory**.

## Model Showcase section (new)

A `model_showcase` flexible-content layout for per-scope deep-dive pages like `/showcase/tmv-5-32x56/`. Fields: model name, tagline, hero image, then paired heading+text blocks for Turrets ("Precision & Adjustability"), Key Features (a bullet repeater), Reticle (with its own image), and Optical Quality, plus a review pull-quote and a warranty callout with a CTA button. Renders from `template-parts/sections/model_showcase.php`.

Build each child showcase page as a normal page with one `model_showcase` section (plus `hide_page_title` checked, since the section carries its own `<h1>`). The parent `/showcase/` listing page just uses `category_tiles` to link out to each child.

## Reducing plugin dependence

The theme is built so the page-builder/layout stack (Elementor + Elementor Pro, JetElements, JetMenu, JetSmartFilters, PowerPack Lite, Premium Addons + PRO, Crocoblock Wizard) has nothing left to do — menus, filters, the mega menu, upsells ("pairs well with"), testimonials, socials and the warranty/promise content are all native WordPress/WooCommerce/ACF now. A few things are **deliberately not** absorbed into the theme, because doing so would mean re-implementing a plugin's real job (rate feeds, merchant settlement, business rules) rather than just its markup — keep a plugin for these:

| Stays a plugin | Why |
| --- | --- |
| Payment gateways, tax (Stripe, Sezzle, WooCommerce Tax) | Regulated, per-provider integrations; not theme concerns. |
| B2BKing | Wholesale groups, tiered pricing, custom registration/roles, B2B messaging — a real application, not a template. |
| Product add-on fields (if actually used for engraving/custom options, etc.) | WooCommerce has no native per-product custom-field system; check a live product edit screen before dropping this. |
| Affiliate/marketing sync (AvantLink, Goaffpro, ActiveCampaign/Mailchimp for WooCommerce) | External network integrations with their own APIs. |
| Region/geo-IP banner | Real geo-IP needs a maintained database or paid API; not something to fake client-side. |
| A **real** multi-currency checkout (different settlement currency per customer) | See the currency switcher note above — the theme only does the display-only version. |
| SEO, analytics, backups, security, SMTP, activity log | Infrastructure/credentials-driven, and changes independently of the theme. |
| Gravity Forms | You asked for it explicitly — see above. |

Two plugins you can likely already drop without building anything: **WebToffee Product Import Export** (WooCommerce ships a native product CSV importer/exporter under *Products → All Products → Import/Export*), and **Cart Abandonment Recovery for WooCommerce** if ActiveCampaign or Mailchimp for WooCommerce is also active — both of those already have their own abandoned-cart flows, so the extra plugin may be redundant.

One thing worth deciding, not something the theme can silently decide for you: the live site's product reviews come from **ReviewX** (multi-criteria ratings, verified badges, shop-owner replies), not native WooCommerce reviews. This theme's product page calls `comments_template()` directly, which shows native reviews — if you keep ReviewX, its tab-replacement hook needs to be re-wired in `inc/woocommerce.php` before launch, otherwise its reviews won't show. If you're fine with plain WooCommerce reviews (which this theme already supports with no plugin at all), you can drop ReviewX instead.

## Deploying to production without downtime

Activating a theme itself is instant (one DB option flip) — the real risk on a live WooCommerce store is (a) the new theme going live before content/menus/ACF are filled in, and (b) a staging→production push overwriting **orders placed on the live site while you were building on staging**. Sequence it like this:

1. Build and QA everything on staging until it's complete: theme active, menus built, Telson Settings filled in, homepage sections done, ACF synced, products' extra fields filled in. Confirm cart → checkout → order end-to-end on staging with a test payment (gateway test mode).
2. **Push files only, not the database**, from staging to production — most host staging tools (e.g. SiteGround's, which this site's inactive "Speed/Security Optimizer" plugins suggest is the host) let you choose what to push. A full database overwrite would erase every order, customer and cart placed on the live site since staging was created; never push the DB wholesale on a store that's taking real orders. If your tool only supports "push everything," do it during your lowest-traffic window and immediately re-import/reconcile any orders placed in that gap from your gateway's own dashboard.
3. Content-only items that live in the database (Telson Settings values, menus, ACF field values, the section layout on each page) will need to be re-entered directly on production after the files land — that's the trade-off for not overwriting the live DB. Keep your staging site open as the reference while you do this.
4. Right before the file push, take a full backup/snapshot of production (files + DB) so you can revert in seconds if something's wrong — most hosts' staging-push tools do this automatically, confirm yours does.
5. After the push, purge every cache layer in order: object cache, page cache (e.g. SiteGround SuperCacher/Speed Optimizer), and any CDN (Cloudflare, etc.) — a stale cached page mixing the old theme's HTML with the new theme's CSS is the most common "why does it look broken" moment right after a cutover.
6. Do the cutover with WooCommerce **not** in maintenance/coming-soon mode already, and don't put it into that mode for this — a well-executed file-only push should be seconds of disruption at most, not a maintenance window.
7. Immediately after, walk through: homepage, shop, one product of each type (simple, variable), add to cart, the cart drawer, checkout, and search. That matches what was verified in this session below.

## Verification performed

Run on a scratch WordPress 7.1 + WooCommerce 11.1 install (SQLite). ACF Pro is a paid plugin and was **not** available there, so a small stub implementing `get_field`, `have_rows` and friends stood in for it. The JSON field groups were validated as JSON but **not** loaded into real ACF Pro, so do a quick import check on first install.

- All templates lint clean (`php -l`), JS parses, no PHP notices or warnings in `debug.log`.
- Home, shop, category, simple and variable products, cart, checkout, account, search (hit and empty), pro staff archive and single, 404 all return the expected status with one `<h1>` and no image missing alt text.
- Filters verified against known data (category, attribute, price, combined, malformed query strings).
- Variable product: add-to-cart is disabled until an option is chosen, price updates, correct variation lands in the cart, header count and drawer refresh.
- Reviews render and the form is present; upsells ("Pairs well with") add via AJAX.
- Layout checked at 1440, 768 and 375 widths, with no horizontal overflow. Not run: Lighthouse, a real payment gateway, and a full 430/390 device matrix.
