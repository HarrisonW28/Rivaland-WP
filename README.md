# Rivaland WordPress Theme

WordPress theme for Rivaland built with the Href Tailwind Starter boilerplate and DDEV for local development.

## 🚀 Quick Start with DDEV

### Prerequisites

- [DDEV](https://ddev.readthedocs.io/en/stable/) installed
- Node.js 14+ installed

### Setup

1. **Start DDEV**:
   ```bash
   ddev start
   ```

2. **Install WordPress** (first time only):
   ```bash
   ddev wp core install --url=rivaland.ddev.site --title="Rivaland" --admin_user=admin --admin_password=admin --admin_email=admin@rivaland.local
   ```

3. **Install Dependencies**:
   ```bash
   ddev composer install
   ddev exec npm install --prefix wp-content/themes/rivaland
   ```

4. **Build Assets**:
   ```bash
   ddev exec npm run development --prefix wp-content/themes/rivaland
   ```

5. **Activate Theme**:
   - Visit `https://rivaland.ddev.site/wp-admin`
   - Go to **Appearance > Themes**
   - Activate **"Rivaland"**

6. **Install ACF Plugin**:
   ```bash
   ddev wp plugin install advanced-custom-fields --activate
   ```

7. **Sync ACF Fields**:
   - Go to **Custom Fields > Field Groups**
   - Click **"Sync available"**

## 📁 Project Structure

```
rivaland-wp/
├── .ddev/                    # DDEV configuration
│   └── config.yaml
├── wp-content/
│   └── themes/
│       └── rivaland/         # Main theme
│           ├── includes/     # Boilerplate structure
│           │   ├── blocks/   # Gutenberg blocks
│           │   ├── lib/      # Core functionality
│           │   └── partials/ # Reusable partials
│           ├── assets/       # Images, SVG
│           ├── js/           # JavaScript files
│           ├── components/   # PHP components
│           ├── scss/         # SCSS source (boilerplate)
│           ├── scss-rivaland/# Original Rivaland SCSS
│           ├── template-parts/ # Template parts
│           ├── templates/    # Page templates
│           ├── acf-json/     # ACF field groups
│           ├── tailwind.config.js
│           └── package.json
└── DDEV_MIGRATION_GUIDE.md  # Complete setup guide
```

## 🛠️ Development

### Start Development

```bash
# Start DDEV
ddev start

# Watch assets (auto-rebuild)
ddev exec npm run watch --prefix wp-content/themes/rivaland
```

### Build Commands

```bash
# Development (with Tailwind, no purging)
ddev exec npm run development --prefix wp-content/themes/rivaland

# Production (purged, minified)
ddev exec npm run production --prefix wp-content/themes/rivaland

# Watch mode (auto-rebuild)
ddev exec npm run watch --prefix wp-content/themes/rivaland
```

### DDEV Commands

```bash
ddev start          # Start containers
ddev stop           # Stop containers
ddev restart        # Restart containers
ddev ssh            # SSH into container
ddev wp [command]   # Run WP-CLI commands
ddev logs           # View logs
```

## 📚 Documentation

- **DDEV_MIGRATION_GUIDE.md** - Complete step-by-step migration guide
- **QUICK_START_DDEV.md** - 5-minute quick start guide

## 🎨 Features

- ✅ Href Tailwind Starter boilerplate structure
- ✅ Tailwind CSS configured with Rivaland design system
- ✅ Laravel Mix for asset compilation
- ✅ ACF field groups ready to sync
- ✅ Custom Post Types (Projects)
- ✅ All Rivaland assets integrated
- ✅ Responsive navigation menu
- ✅ DDEV local development environment

## 📝 Next Steps

1. Create pages and assign templates
2. Set up navigation menu
3. Configure theme settings (ACF Options)
4. Add content via ACF fields
5. Test all functionality

---

**Ready to develop!** Visit `https://rivaland.ddev.site` after starting DDEV.
