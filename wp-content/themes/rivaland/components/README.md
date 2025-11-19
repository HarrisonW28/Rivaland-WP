# WordPress Component Library

This directory contains reusable WordPress template parts that correspond to the standardized SCSS components.

## Available Components

### Button Component
**File**: `button.php`

Standardized button component with variants and sizes.

```php
<?php
get_template_part('components/button', null, [
    'text' => 'View all projects',
    'url' => '/projects',
    'variant' => 'primary', // primary, secondary, outline, ghost
    'size' => 'md', // sm, md, lg
    'show_icon' => true,
    'class' => 'custom-class',
    'id' => 'button-id'
]);
?>
```

### Card Component
**File**: `card.php`

Flexible card component for projects, news, services, etc.

```php
<?php
get_template_part('components/card', null, [
    'type' => 'project', // project, news, service, feature
    'variant' => 'light', // light, dark
    'image' => [
        'url' => 'image.jpg',
        'alt' => 'Description'
    ],
    'eyebrow' => 'Category',
    'title' => 'Card Title',
    'text' => 'Card description...',
    'meta' => 'Residential / Application submitted',
    'location' => 'Woodcote, South Oxfordshire',
    'link' => [
        'url' => '/project',
        'text' => 'Read more'
    ]
]);
?>
```

### Icon Arrow Component
**File**: `icon-arrow.php`

Standardized arrow icon with direction variants.

```php
<?php
get_template_part('components/icon', 'arrow', [
    'direction' => 'right', // right, left, up, down
    'size' => 'sm', // sm, md, lg
    'class' => 'custom-class'
]);
?>
```

### Eyebrow Component
**File**: `eyebrow.php`

Label/eyebrow component for section headers.

```php
<?php
get_template_part('components/eyebrow', null, [
    'text' => 'Category Label',
    'variant' => 'primary', // primary, secondary, light, dark
    'tag' => 'p', // p, span, div
    'class' => 'custom-class'
]);
?>
```

### Section Component
**File**: `section.php`

Section wrapper component for consistent page sections.

```php
<?php
get_template_part('components/section', null, [
    'type' => 'intro', // intro, services, projects, news, feature, testimonial
    'variant' => 'light', // light, dark
    'id' => 'section-id',
    'class' => 'custom-class',
    'content' => '<p>Section content...</p>'
]);
?>
```

## Integration with ACF (Advanced Custom Fields)

These components work seamlessly with ACF field groups:

```php
<?php
// Example: Button from ACF
$button = get_field('button');
if ($button):
    get_template_part('components/button', null, [
        'text' => $button['text'],
        'url' => $button['url'],
        'variant' => $button['variant'] ?? 'primary'
    ]);
endif;

// Example: Card from ACF Repeater
if (have_rows('cards')):
    while (have_rows('cards')): the_row();
        get_template_part('components/card', null, [
            'type' => get_sub_field('type'),
            'title' => get_sub_field('title'),
            'text' => get_sub_field('text'),
            'image' => get_sub_field('image')
        ]);
    endwhile;
endif;
?>
```

## Component Structure

All components follow this structure:
1. **Parameters**: Accept array of arguments via `$args`
2. **Defaults**: Provide sensible defaults for all parameters
3. **Escaping**: All output is properly escaped
4. **Flexibility**: Support additional classes and IDs
5. **Accessibility**: Include proper ARIA attributes where needed

## Best Practices

1. **Always use template parts**: Don't duplicate component HTML
2. **Provide defaults**: Always provide default values in ACF
3. **Escape output**: Components handle escaping, but be mindful
4. **Consistent naming**: Use component parameter names consistently
5. **Document customizations**: Document any custom classes or modifications

## Migration from Static HTML

When migrating from static HTML to WordPress:

1. Identify component type (button, card, etc.)
2. Extract component data (text, URL, image, etc.)
3. Create ACF field group if needed
4. Replace HTML with `get_template_part()` call
5. Test component rendering

## Example: Full Page Section

```php
<?php
// Services section with cards
get_template_part('components/section', null, [
    'type' => 'services',
    'variant' => 'light',
    'content' => '
        <div class="services-card">
            ' . get_template_part('components/eyebrow', null, [
                'text' => 'Meeting all demands',
                'variant' => 'primary'
            ]) . '
            ' . get_template_part('components/button', null, [
                'text' => 'What we offer',
                'url' => '/services',
                'variant' => 'primary'
            ]) . '
        </div>
        <div class="service-list">
            ' . render_service_cards() . '
        </div>
    '
]);
?>
```

## Support

For component documentation, see:
- `docs/COMPONENT_LIBRARY.md` - Complete component reference
- `docs/COMPONENT_REFACTOR_SUMMARY.md` - Implementation summary
