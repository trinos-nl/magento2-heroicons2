# Trinos_Heroicons2

Use [Heroicons v2](https://heroicons.com/) in your [Hyvä](https://www.hyva.io/) Magento 2 storefront.

This module ships the complete Heroicons v2.2.0 set (324 icons) in all three styles and exposes them through Hyvä-compatible view models, so you can render any icon inline as an SVG from your `.phtml` templates.

## Features

- All 324 Heroicons v2.2.0 icons in **Outline**, **Solid** and **Mini** styles.
- Built on top of Hyvä's `Hyva\Theme\ViewModel\SvgIcons`, so the API matches the icon view models you already know.
- A magic method per icon (e.g. `->academicCapHtml()`) with full IDE autocompletion through `@method` doc blocks.
- Backwards compatibility with the original Heroicons v1 names, so upgrading does not break existing templates.

## Requirements

- Magento 2
- `hyva-themes/magento2-theme-module`

## Installation

```bash
composer require trinos/magento2-heroicons2
bin/magento module:enable Trinos_Heroicons2
bin/magento setup:upgrade
```

## Usage

The module provides three view models, one per icon style:

| Style   | View model                                       | Source                  |
|---------|--------------------------------------------------|-------------------------|
| Outline | `Trinos\Heroicons2\ViewModel\Heroicons2Outline`  | `svg/heroicons2/24/outline` |
| Solid   | `Trinos\Heroicons2\ViewModel\Heroicons2Solid`    | `svg/heroicons2/24/solid`   |
| Mini    | `Trinos\Heroicons2\ViewModel\Heroicons2Mini`     | `svg/heroicons2/20/solid`   |

### In a `.phtml` template

```php
<?php
/** @var \Magento\Framework\View\Element\Template $block */
/** @var \Magento\Framework\Escaper $escaper */

$heroicons = $viewModels->require(\Trinos\Heroicons2\ViewModel\Heroicons2Outline::class);
?>

<?= $heroicons->renderHtml('academic-cap') ?>
```

`renderHtml()` accepts the same arguments as Hyvä's `SvgIcons`:

```php
$heroicons->renderHtml(
    'academic-cap',      // icon name (kebab-case)
    'w-6 h-6 text-black', // CSS classes
    24,                   // width
    24,                   // height
    ['aria-hidden' => 'true'] // extra attributes
);
```

### Magic methods

Every icon is also available as a camelCase magic method ending in `Html`, with IDE
autocompletion provided by the `@method` doc blocks on `Heroicons2Interface`:

```php
<?= $heroicons->academicCapHtml('w-6 h-6') ?>
<?= $heroicons->arrowDownTrayHtml() ?>
```

> **Note:** the magic method cannot represent a few icon names whose digits make the
> camelCase → kebab-case conversion ambiguous — `h1`, `h2`, `h3` and `squares-2x2`.
> Call `renderHtml()` directly for these:
>
> ```php
> <?= $heroicons->renderHtml('h1') ?>
> <?= $heroicons->renderHtml('squares-2x2') ?>
> ```

## Heroicons v1 compatibility

The Heroicons v1 → v2 upgrade renamed a large number of icons (for example
`cash` → `banknotes`, `search` → `magnifying-glass`). To ease migration, this module:

- **Maps old names automatically.** Passing a v1 name to `renderHtml()` transparently
  resolves it to its v2 equivalent, so `renderHtml('search')` renders `magnifying-glass`.
- **Keeps the old magic methods.** The `HeroiconsCompat` trait provides deprecated
  methods for every renamed v1 icon that delegate to the new name, so existing calls
  such as `->searchHtml()` keep working (your IDE will flag them as deprecated).

### Swap out the v1 view models entirely

If your templates still inject the Heroicons v1 view models that Hyvä ships
(`Hyva\Theme\ViewModel\HeroiconsOutline` and `Hyva\Theme\ViewModel\HeroiconsSolid`),
you can redirect them to the v2 view models with a `<preference>` in your own module's
`etc/di.xml`. Every existing template then renders Heroicons v2 without any template
changes:

```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="urn:magento:framework:ObjectManager/etc/config.xsd">
    <preference for="Hyva\Theme\ViewModel\HeroiconsOutline"
                type="Trinos\Heroicons2\ViewModel\Heroicons2Outline"/>
    <preference for="Hyva\Theme\ViewModel\HeroiconsSolid"
                type="Trinos\Heroicons2\ViewModel\Heroicons2Solid"/>
</config>
```

This works because the automatic name mapping and the `HeroiconsCompat` methods let the
v2 view models understand the old v1 icon names.

## Maintenance scripts

Two helper scripts regenerate the generated PHP when the icon set is updated. Both
require **GNU sed** (they are incompatible with the BSD/macOS `sed`).

- `generate-heroicon-signatures.sh` — regenerates the `@method` doc blocks on
  `Heroicons2Interface` from the SVG files.
- `generate-compat-functions.sh` — regenerates the deprecated compatibility methods in
  the `HeroiconsCompat` trait from `updated-icon-names.csv`.

## License

GNU General Public License (GPL).
