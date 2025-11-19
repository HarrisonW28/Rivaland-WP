# ACF JSON Import Files

This directory contains ACF (Advanced Custom Fields) JSON export files for all component blocks with variation integration.

## Import Instructions

### Method 1: Import via ACF UI (Recommended)

1. Install and activate **Advanced Custom Fields Pro** (or ACF Free)
2. Go to **Custom Fields > Tools** in WordPress admin
3. Click **Import Field Groups**
4. Select the JSON file you want to import
5. Click **Import**

### Method 2: Automatic Sync (Recommended for Development)

1. Create an `acf-json` folder in your theme root: `wp-content/themes/rivaland/acf-json/`
2. Copy all JSON files from this directory to the `acf-json` folder
3. ACF will automatically sync field groups when you edit them in the admin
4. Changes made in the admin will be saved back to the JSON files

### Method 3: Programmatic Import

You can also import these programmatically using ACF's import functions in your theme's `functions.php`.

## Available Field Groups

### Component Blocks

1. **Card Block** (`group_card-block.json`)
   - Types: project, news, service, feature, default
   - Variants: light, dark
   - Nested eyebrow component with variant options
   - Conditional fields based on card type

2. **Hero Block** (`group_hero-block.json`)
   - Types: home, about, services
   - Color schemes: light, dark
   - White navbar option
   - Background/image options

3. **Intro Block** (`group_intro-block.json`)
   - Variants: default, about
   - WYSIWYG content editor

4. **Accordion Block** (`group_accordion-block.json`)
   - Types: services, page
   - Variants: light, dark
   - Left column card with nested eyebrow and button components (for services type)
   - Repeater for accordion items with nested button components
   - Conditional fields for page type (preview text, images)
   - All buttons include: variant, size, and icon toggle options

5. **Feature Block** (`group_feature-block.json`)
   - Types: standard, team
   - Variants: light, dark
   - Nested eyebrow component with variant options
   - Nested button component with variant, size, and icon toggle options
   - Image and content fields

6. **Projects Block** (`group_projects-block.json`)
   - Filter toggle option
   - Project type selection (residential, commercial, mixed)
   - Status selection
   - Repeater for projects
   - Nested button component with variant, size, and icon toggle options

7. **News Block** (`group_news-block.json`)
   - Repeater for news items with nested eyebrow components
   - Optional view all button with variant, size, and icon toggle options
   - Image, title, text, link fields

8. **Testimonials Block** (`group_testimonials-block.json`)
    - Variants: light, dark
    - Repeater for testimonials
    - Autoplay options
    - Author image support

9. **Section Block** (`group_section-block.json`)
    - Types: intro, services, projects, news, feature, testimonial, default
    - Variants: light, dark
    - Wrapper for other content

## Nested Components

Button and eyebrow components are **not standalone blocks** - they are nested within their parent blocks as field groups:

- **Button Component**: Nested in Accordion, Feature, Projects, News blocks
  - Includes: text, url, variant (primary/secondary/outline/ghost), size (sm/md/lg), show_icon toggle
  
- **Eyebrow Component**: Nested in Card, Accordion, Feature, News blocks
  - Includes: text, variant (primary/secondary/light/dark)

This design ensures buttons and eyebrows are always used in context with their parent components, providing better organization and preventing orphaned components.

## Block Registration

These field groups are configured for ACF Blocks. To use them, you'll need to register the blocks in your theme's `functions.php`:

```php
// Register ACF Blocks
add_action('acf/init', 'register_acf_blocks');
function register_acf_blocks() {
    if (function_exists('acf_register_block_type')) {
        // Card Block
        acf_register_block_type([
            'name' => 'card-block',
            'title' => 'Card',
            'description' => 'Card component with types and variants',
            'render_template' => 'template-parts/blocks/card.php',
            'category' => 'rivaland',
            'icon' => 'grid-view',
            'keywords' => ['card', 'project', 'news'],
            'supports' => ['align' => false]
        ]);
        
        // Accordion Block
        acf_register_block_type([
            'name' => 'accordion-block',
            'title' => 'Accordion',
            'description' => 'Accordion component with nested buttons and eyebrows',
            'render_template' => 'template-parts/blocks/accordion.php',
            'category' => 'rivaland',
            'icon' => 'editor-ul',
            'keywords' => ['accordion', 'services', 'faq'],
            'supports' => ['align' => false]
        ]);
        
        // Add other blocks similarly...
    }
}
```

## Usage in Templates

Once imported, you can use these blocks in the Gutenberg editor or reference them in templates:

```php
// Example: Using nested button in Accordion block
$accordion_card = get_field('accordion_left_card');
if ($accordion_card && $accordion_card['button']):
    $button = $accordion_card['button'];
    get_template_part('components/button', null, [
        'text' => $button['text'],
        'url' => $button['url'],
        'variant' => $button['variant'] ?? 'primary',
        'size' => $button['size'] ?? 'md',
        'show_icon' => $button['show_icon'] ?? true
    ]);
endif;

// Example: Using nested eyebrow in Feature block
$eyebrow = get_field('feature_eyebrow');
if ($eyebrow && $eyebrow['text']):
    get_template_part('components/eyebrow', null, [
        'text' => $eyebrow['text'],
        'variant' => $eyebrow['variant'] ?? 'primary'
    ]);
endif;

// Example: Using nested button in Accordion item repeater
if (have_rows('accordion_items')):
    while (have_rows('accordion_items')): the_row();
        $button = get_sub_field('button');
        if ($button):
            get_template_part('components/button', null, [
                'text' => $button['text'],
                'url' => $button['url'],
                'variant' => $button['variant'] ?? 'primary',
                'size' => $button['size'] ?? 'md',
                'show_icon' => $button['show_icon'] ?? true
            ]);
        endif;
    endwhile;
endif;
```

## Field Naming Convention

All fields follow a consistent naming pattern:
- Field keys: `field_[component]_[field_name]`
- Field names: `[component]_[field_name]` (snake_case)
- Group keys: `group_[component]_block`

## Conditional Logic

Many fields use conditional logic to show/hide fields based on selections:
- Card fields change based on card type
- Accordion fields change based on accordion type
- Hero fields change based on hero type

## Notes

- All field groups are set to `"show_in_rest": 0` for performance
- Field groups use `"position": "normal"` and `"style": "default"`
- Required fields are marked with `"required": 1`
- Default values are provided where appropriate
- UI select fields are enabled for better UX

## Support

For questions or issues with these field groups, refer to:
- [ACF Documentation](https://www.advancedcustomfields.com/resources/)
- [ACF Block Documentation](https://www.advancedcustomfields.com/resources/acf_register_block_type/)

