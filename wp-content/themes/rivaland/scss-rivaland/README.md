# SCSS Architecture

This directory follows the **7-1 Pattern** (adapted for this project) - a popular SCSS architecture that organizes styles into logical folders.

## Directory Structure

```
scss/
├── abstracts/          # Variables, mixins, functions (no CSS output)
│   ├── _variables.scss
│   └── _mixins.scss
├── base/              # Base styles, resets, typography
│   └── _base.scss
├── components/        # Reusable UI components
│   ├── _intro.scss
│   ├── _services.scss
│   ├── _feature.scss
│   ├── _projects.scss
│   └── _news.scss
├── layout/            # Major layout sections
│   ├── _hero.scss
│   ├── _navigation.scss
│   └── _footer.scss
├── _responsive.scss   # All media queries (kept at root for simplicity)
└── style.scss         # Main file that imports everything
```

## Import Order

The main `style.scss` imports files in this specific order:

1. **Abstracts** - Variables and mixins (no CSS output, just definitions)
2. **Base** - Base styles and resets
3. **Layout** - Major structural components
4. **Components** - Reusable UI components
5. **Responsive** - Media queries (must come last)

## Naming Convention

- All partial files start with an underscore (`_`)
- The main file (`style.scss`) does NOT have an underscore
- Imports don't include the underscore or `.scss` extension

## Compilation

### Using npm scripts (recommended):

```bash
# Install dependencies first
npm install

# Compile once
npm run sass

# Watch mode (auto-compile on changes) - RECOMMENDED for development
npm run dev
# or
npm run sass:watch

# Production build (compressed, no source maps)
npm run build
```

### Using Sass directly:

```bash
# Compile once
sass scss/style.scss style.css

# Watch mode
sass --watch scss/style.scss:style.css

# Compressed for production
sass scss/style.scss style.css --style compressed
```

## Best Practices

- ✅ **Only edit SCSS files** - Never edit `style.css` directly (it gets overwritten)
- ✅ **Use watch mode during development** - Run `npm run dev` to auto-compile on save
- ✅ **Compiled CSS is gitignored** - Only source SCSS files are tracked in git
- ✅ **Compile before committing** - Run `npm run build` before deploying

## Benefits

- **Clear Organization**: Easy to find and locate styles
- **Scalability**: Simple to add new components or sections
- **Maintainability**: Changes are isolated to specific files
- **Best Practices**: Follows industry-standard SCSS architecture
- **Reusability**: Abstracts (variables/mixins) shared across all files

## Adding New Styles

- **New component?** → Add to `components/`
- **New layout section?** → Add to `layout/`
- **New variable/mixin?** → Add to `abstracts/`
- **New responsive rule?** → Add to `_responsive.scss`

Remember to import new files in `style.scss` in the correct order!
