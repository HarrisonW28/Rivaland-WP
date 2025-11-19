# Component Variants

This folder contains variant styles for reusable components using BEM-style modifier classes.

## Structure

Each component variant file follows the naming pattern: `_[component]-variants.scss`

## Usage

Instead of creating page-specific overrides, use modifier classes:

```html
<!-- Base component -->
<section class="hero">...</section>

<!-- With variant -->
<section class="hero hero--dark">...</section>
<section class="hero hero--services">...</section>
```

## Available Variants

### Hero Variants (`_hero-variants.scss`)
- `.hero--dark` - Dark blue hero with diagonal teal triangle (About page)
- `.hero--services` - Dark blue hero with image below (Services page)

### Intro Variants (`_intro-variants.scss`)
- `.intro--about` - Full-width intro with centered text (About page)

### Feature Variants (`_feature-variants.scss`)
- `.feature--team` - Team feature with circular headshot image

### Testimonial Variants (`_testimonial-variants.scss`)
- Placeholder for future testimonial variants

## Benefits

1. **Reusability** - Variants can be used across multiple pages
2. **Maintainability** - All variants in one place, easy to find
3. **Consistency** - Same variant looks the same everywhere
4. **Scalability** - Easy to add new variants without touching base components

## Adding New Variants

1. Create or edit the appropriate variant file
2. Use BEM naming: `.component--variant-name`
3. Import in `style.scss` (already done)
4. Use in HTML: `class="component component--variant-name"`

