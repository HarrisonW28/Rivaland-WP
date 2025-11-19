# Rivaland WordPress Migration Guide - DDEV Setup

Complete guide to migrate the Rivaland HTML website to WordPress using the Href Tailwind Starter boilerplate with DDEV for local development.

## Prerequisites

- [DDEV](https://ddev.readthedocs.io/en/stable/) installed
- Node.js 14+ installed
- Git
- Basic knowledge of WordPress and command line

---

## Step 1: Initial Setup

### 1.1 Navigate to Project Directory

```bash
cd rivaland-wp
```

### 1.2 Start DDEV

```bash
ddev start
```

This will:
- Create Docker containers
- Set up WordPress
- Generate database
- Provide site URL (usually `https://rivaland.ddev.site`)

**First time setup**: DDEV will prompt you to configure WordPress. Follow the prompts or use:

```bash
ddev wp core install --url=rivaland.ddev.site --title="Rivaland" --admin_user=admin --admin_password=admin --admin_email=admin@rivaland.local
```

---

## Step 2: Install Dependencies

### 2.1 Install PHP Dependencies (Composer)

```bash
ddev composer install
```

### 2.2 Install Node Dependencies

```bash
ddev exec npm install --prefix wp-content/themes/rivaland
```

Or if you prefer to run locally:

```bash
cd wp-content/themes/rivaland
npm install
```

---

## Step 3: Activate Theme

### 3.1 Access WordPress Admin

- URL: `https://rivaland.ddev.site/wp-admin`
- Credentials: Set during `ddev wp core install` (or use `admin` / `admin`)

### 3.2 Activate Theme

1. Go to **Appearance > Themes**
2. Find **"Rivaland"** theme
3. Click **"Activate"**

---

## Step 4: Install Required Plugins

### 4.1 Install Advanced Custom Fields (ACF)

```bash
ddev wp plugin install advanced-custom-fields --activate
```

Or via WordPress Admin:
1. Go to **Plugins > Add New**
2. Search for "Advanced Custom Fields"
3. Install and activate

### 4.2 Install ACF Pro (if you have license)

```bash
# Upload ACF Pro zip to wp-content/plugins/
ddev wp plugin activate advanced-custom-fields-pro
```

### 4.3 Install ACF Extended (Optional)

```bash
ddev wp plugin install acf-extended --activate
```

---

## Step 5: Import ACF Field Groups

1. Go to **Custom Fields > Field Groups** in WordPress Admin
2. Click **"Sync available"** button
3. Select all field groups and click **"Sync"**

**Field Groups to Import:**
- `group_accordion-block.json`
- `group_button-block.json`
- `group_card-block.json`
- `group_eyebrow-block.json`
- `group_feature-block.json`
- `group_hero-block.json`
- `group_intro-block.json`
- `group_news-block.json`
- `group_projects-block.json`
- `group_section-block.json`
- `group_testimonials-block.json`

---

## Step 6: Build Assets

### 6.1 Development Build (with Tailwind, no purging)

```bash
ddev exec npm run development --prefix wp-content/themes/rivaland
```

Or locally:
```bash
cd wp-content/themes/rivaland
npm run development
```

### 6.2 Watch Mode (auto-rebuild on changes)

```bash
ddev exec npm run watch --prefix wp-content/themes/rivaland
```

Or locally:
```bash
cd wp-content/themes/rivaland
npm run watch
```

### 6.3 Production Build (purged, minified)

```bash
ddev exec npm run production --prefix wp-content/themes/rivaland
```

---

## Step 7: Create Pages and Assign Templates

### 7.1 Create Pages

```bash
# Create pages via WP-CLI
ddev wp post create --post_type=page --post_title="Home" --post_status=publish
ddev wp post create --post_type=page --post_title="About" --post_status=publish
ddev wp post create --post_type=page --post_title="Services" --post_status=publish
ddev wp post create --post_type=page --post_title="Projects" --post_status=publish
ddev wp post create --post_type=page --post_title="News" --post_status=publish
ddev wp post create --post_type=page --post_title="Contact" --post_status=publish
```

Or via WordPress Admin:
1. Go to **Pages > Add New**
2. Create each page
3. In **Page Attributes** box, select the appropriate template:
   - Home → "Home" template
   - About → "About" template
   - Services → "Services" template
   - Projects → "Projects" template
   - News → "News" template
   - Contact → "Contact" template

### 7.2 Set Homepage

```bash
# Get page IDs first
ddev wp post list --post_type=page --format=ids

# Set homepage (replace 1 with actual Home page ID)
ddev wp option update show_on_front page
ddev wp option update page_on_front 5
```

Or via WordPress Admin:
1. Go to **Settings > Reading**
2. Select **"A static page"**
3. Set **Homepage** to "Home"
4. Click **"Save Changes"**

---

## Step 8: Set Up Navigation Menu

### 8.1 Create Menu via WP-CLI

```bash
# Create menu
ddev wp menu create "Primary Navigation"

# Add pages to menu (replace IDs with actual page IDs)
ddev wp menu item add-post "Primary Navigation" 2  # About
ddev wp menu item add-post "Primary Navigation" 3  # Services
ddev wp menu item add-post "Primary Navigation" 4  # Projects
ddev wp menu item add-post "Primary Navigation" 5  # News
ddev wp menu item add-post "Primary Navigation" 6  # Contact

# Assign to location
ddev wp menu location assign "Primary Navigation" primary
```

Or via WordPress Admin:
1. Go to **Appearance > Menus**
2. Create menu: "Primary Navigation"
3. Add pages: About, Services, Projects, News, Contact
4. Assign to "Primary Navigation" location

---

## Step 9: Configure Theme Settings

1. Go to **Theme Settings** (ACF Options Page)
2. Configure:
   - **Site Logo**: Upload logo
   - **Telephone**: `01564 123456`
   - **Email**: `info@rivaland.co.uk`
   - **Address**: Your address
   - **LinkedIn**: Your LinkedIn URL
   - **Copyright Text**: `© Riva Land 2025. All rights reserved. Registered in England and Wales No: 09080608.`

---

## Step 10: Development Workflow

### Daily Development

1. **Start DDEV**:
   ```bash
   ddev start
   ```

2. **Start asset watch** (in separate terminal):
   ```bash
   ddev exec npm run watch --prefix wp-content/themes/rivaland
   ```

3. **Access site**:
   - Frontend: `https://rivaland.ddev.site`
   - Admin: `https://rivaland.ddev.site/wp-admin`

### Making Changes

1. **Edit PHP templates** in `wp-content/themes/rivaland/`
2. **Edit SCSS** in `wp-content/themes/rivaland/scss/`
3. **Edit JavaScript** in `wp-content/themes/rivaland/js/`
4. **Watch mode** will auto-rebuild CSS/JS
5. **Refresh browser** to see changes

### DDEV Commands

```bash
# Start/Stop
ddev start          # Start containers
ddev stop           # Stop containers
ddev restart        # Restart containers

# Database
ddev import-db file.sql    # Import database
ddev export-db             # Export database

# WP-CLI
ddev wp [command]          # Run WP-CLI commands

# Shell access
ddev ssh                   # SSH into web container
ddev exec [command]        # Execute command in container

# Logs
ddev logs                   # View logs
ddev logs -f                # Follow logs

# Utilities
ddev describe               # Show project info
ddev poweroff               # Stop all DDEV projects
```

---

## Step 11: Import/Export Database

### Export Database

```bash
ddev export-db --file=backup.sql
```

### Import Database

```bash
ddev import-db backup.sql
```

---

## Step 12: Troubleshooting

### DDEV Not Starting

```bash
# Check Docker is running
docker ps

# Restart DDEV
ddev poweroff
ddev start
```

### Assets Not Building

```bash
# Reinstall dependencies
ddev exec npm install --prefix wp-content/themes/rivaland

# Clear node_modules and reinstall
ddev exec rm -rf wp-content/themes/rivaland/node_modules
ddev exec npm install --prefix wp-content/themes/rivaland
```

### Theme Not Appearing

- Check theme folder is in `wp-content/themes/rivaland/`
- Verify `style.css` has correct theme header
- Check file permissions: `ddev ssh` then `ls -la wp-content/themes/`

### Database Issues

```bash
# Reset database
ddev stop
ddev remove
ddev start
ddev wp core install --url=rivaland.ddev.site --title="Rivaland" --admin_user=admin --admin_password=admin --admin_email=admin@rivaland.local
```

### Port Conflicts

If port 80/443 are in use:

```bash
# Edit .ddev/config.yaml
# Change router_http_port and router_https_port
ddev restart
```

---

## Step 13: Production Build

Before deploying:

```bash
# Build production assets
ddev exec npm run production --prefix wp-content/themes/rivaland

# Export database
ddev export-db --file=production.sql

# Commit changes
git add .
git commit -m "Production build"
```

---

## Quick Reference

**Site URL**: `https://rivaland.ddev.site`  
**Admin URL**: `https://rivaland.ddev.site/wp-admin`  
**Theme Path**: `wp-content/themes/rivaland/`  
**Start DDEV**: `ddev start`  
**Stop DDEV**: `ddev stop`  
**Build Assets**: `ddev exec npm run development --prefix wp-content/themes/rivaland`  
**Watch Assets**: `ddev exec npm run watch --prefix wp-content/themes/rivaland`  
**WP-CLI**: `ddev wp [command]`  
**SSH Access**: `ddev ssh`

---

## Next Steps

- [ ] Add content to pages via ACF fields
- [ ] Create Projects custom post type entries
- [ ] Configure contact form
- [ ] Set up SEO plugin
- [ ] Test all pages and functionality
- [ ] Prepare for staging/production deployment

---

**Migration Complete!** 🎉

Your Rivaland WordPress site is now running locally with DDEV!

