# Plesk Migration Plan: DDEV Local to Live Server

## Overview

This guide covers migrating the Rivaland WordPress site from a local DDEV environment to a live Plesk server.

**Estimated Time:** 2-4 hours (depending on site size and server speed)

---

## Pre-Migration Checklist

### 1. Local Environment Preparation

- [ ] **Backup everything locally**
  ```bash
  cd rivaland-wp
  ddev export-db --file=backup-$(date +%Y%m%d).sql.gz
  ```

- [ ] **Note your local database credentials**
  - Database name: `db`
  - Database user: `db`
  - Database password: `db`
  - Database host: `db` (in DDEV)

- [ ] **Document your local URLs**
  - Local site URL: `https://rivaland.ddev.site` (or your DDEV URL)
  - Check `wp-config.php` or run: `ddev describe`

- [ ] **List all plugins and themes**
  - Note any custom plugins
  - Document active plugins
  - Note theme customizations

- [ ] **Check file sizes**
  ```bash
  ddev exec du -sh wp-content/uploads
  ```

### 2. Plesk Server Preparation

- [ ] **Create domain/subdomain in Plesk**
  - Domain: `yourdomain.com` (or staging subdomain)
  - PHP version: 8.2 (match DDEV)
  - Enable SSL certificate

- [ ] **Create database in Plesk**
  - Database name: `rivaland_db` (or your preference)
  - Database user: Create dedicated user
  - Database password: Strong password
  - Note all credentials

- [ ] **Check PHP settings**
  - PHP version: 8.2
  - Memory limit: 256M or higher
  - Max upload size: 64M or higher
  - Max execution time: 300

- [ ] **Install WordPress (optional)**
  - You can install via Plesk Application Installer OR
  - Upload files manually (recommended for custom theme)

---

## Migration Steps

### Step 1: Export Local Database

```bash
# Navigate to project directory
cd rivaland-wp

# Export database
ddev export-db --file=rivaland-database.sql.gz

# Or export without compression
ddev export-db --file=rivaland-database.sql
```

**Alternative method:**
```bash
# Access DDEV database
ddev mysql

# In MySQL:
mysqldump -u db -pdb db > rivaland-database.sql
exit

# Or use phpMyAdmin
ddev launch -p
# Navigate to phpMyAdmin and export database
```

### Step 2: Prepare Database for Production

**Important:** Before importing, you need to replace local URLs with production URLs.

**Option A: Use WP-CLI (Recommended)**
```bash
# In DDEV, search and replace URLs
ddev wp search-replace 'https://rivaland.ddev.site' 'https://yourdomain.com' --all-tables
ddev wp search-replace 'http://rivaland.ddev.site' 'https://yourdomain.com' --all-tables

# Export again with updated URLs
ddev export-db --file=rivaland-database-production.sql.gz
```

**Option B: Use Search-Replace-DB script**
1. Download: https://github.com/interconnectit/Search-Replace-DB
2. Upload to your local site temporarily
3. Run search-replace tool
4. Export database again

**Option C: Manual SQL replacement (after import)**
- Import database first
- Use Plesk's phpMyAdmin to run SQL:
  ```sql
  UPDATE wp_options SET option_value = replace(option_value, 'https://rivaland.ddev.site', 'https://yourdomain.com');
  UPDATE wp_posts SET post_content = replace(post_content, 'https://rivaland.ddev.site', 'https://yourdomain.com');
  UPDATE wp_posts SET guid = replace(guid, 'https://rivaland.ddev.site', 'https://yourdomain.com');
  UPDATE wp_postmeta SET meta_value = replace(meta_value, 'https://rivaland.ddev.site', 'https://yourdomain.com');
  ```

### Step 3: Upload Files to Plesk

**Method 1: FTP/SFTP (Recommended for large files)**

1. **Get FTP credentials from Plesk**
   - Host: Your domain or server IP
   - Username: Plesk FTP user
   - Password: Plesk FTP password
   - Port: 21 (FTP) or 22 (SFTP)

2. **Connect using FileZilla or similar**
   - Navigate to: `/httpdocs` or `/public_html` (Plesk document root)

3. **Upload WordPress files**
   ```
   Upload these directories/files:
   - wp-content/themes/rivaland/ (entire theme)
   - wp-content/uploads/ (all media files)
   - wp-content/plugins/ (if custom plugins)
   - .htaccess (if exists)
   - wp-config.php (will need editing)
   ```

**Method 2: Plesk File Manager**
1. Log into Plesk
2. Go to **Files** → **File Manager**
3. Navigate to `httpdocs` or `public_html`
4. Upload files via web interface (slower for large files)

**Method 3: SSH/SCP (Fastest)**
```bash
# From your local machine
cd rivaland-wp

# Upload entire wp-content directory
scp -r wp-content username@yourdomain.com:/var/www/vhosts/yourdomain.com/httpdocs/

# Or use rsync (better for large uploads)
rsync -avz --progress wp-content/ username@yourdomain.com:/var/www/vhosts/yourdomain.com/httpdocs/wp-content/
```

### Step 4: Import Database to Plesk

**Method 1: Plesk phpMyAdmin**
1. Log into Plesk
2. Go to **Databases** → **phpMyAdmin**
3. Select your database
4. Click **Import** tab
5. Choose your SQL file (`rivaland-database-production.sql`)
6. Click **Go**

**Method 2: Plesk Database Import**
1. Log into Plesk
2. Go to **Databases** → Your database
3. Click **Import Dump**
4. Upload SQL file
5. Click **OK**

**Method 3: SSH/Command Line**
```bash
# SSH into Plesk server
ssh username@yourdomain.com

# Import database
mysql -u database_user -p database_name < /path/to/rivaland-database-production.sql

# Or if compressed
gunzip < rivaland-database-production.sql.gz | mysql -u database_user -p database_name
```

### Step 5: Configure wp-config.php

1. **Download wp-config.php from Plesk** (or create new)

2. **Update database credentials:**
   ```php
   define('DB_NAME', 'rivaland_db');
   define('DB_USER', 'database_user');
   define('DB_PASSWORD', 'database_password');
   define('DB_HOST', 'localhost');
   ```

3. **Update URLs:**
   ```php
   define('WP_HOME', 'https://yourdomain.com');
   define('WP_SITEURL', 'https://yourdomain.com');
   ```

4. **Add security keys:**
   - Visit: https://api.wordpress.org/secret-key/1.1/salt/
   - Copy new keys and replace in wp-config.php

5. **Update table prefix (if different):**
   ```php
   $table_prefix = 'wp_'; // Check your database
   ```

6. **Add production-specific settings:**
   ```php
   define('WP_DEBUG', false);
   define('WP_DEBUG_LOG', false);
   define('WP_DEBUG_DISPLAY', false);
   define('DISALLOW_FILE_EDIT', true);
   ```

7. **Upload wp-config.php** to Plesk document root

### Step 6: Update File Permissions

**Via SSH:**
```bash
# Navigate to document root
cd /var/www/vhosts/yourdomain.com/httpdocs

# Set correct permissions
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;

# WordPress specific
chmod 600 wp-config.php
chmod 755 wp-content
chmod -R 755 wp-content/themes
chmod -R 755 wp-content/plugins
chmod -R 755 wp-content/uploads
```

**Via Plesk File Manager:**
- Right-click files/folders → **Change Permissions**
- Directories: `755`
- Files: `644`
- wp-config.php: `600`

### Step 7: Run Final URL Replacements

**Using WP-CLI (if available on Plesk):**
```bash
# SSH into server
ssh username@yourdomain.com

# Navigate to WordPress directory
cd /var/www/vhosts/yourdomain.com/httpdocs

# Run search-replace
wp search-replace 'https://rivaland.ddev.site' 'https://yourdomain.com' --all-tables
wp search-replace 'http://rivaland.ddev.site' 'https://yourdomain.com' --all-tables
```

**Using phpMyAdmin:**
Run the SQL queries from Step 2, Option C.

### Step 8: Build Assets (CSS/JS)

**If using Webpack/NPM build:**
```bash
# SSH into server
ssh username@yourdomain.com

# Navigate to theme directory
cd /var/www/vhosts/yourdomain.com/httpdocs/wp-content/themes/rivaland

# Install dependencies
npm install

# Build production assets
npm run production
# OR
npm run build
```

**Or build locally and upload:**
```bash
# On local machine
cd rivaland-wp/wp-content/themes/rivaland
npm run production

# Upload dist/ directory
scp -r dist/ username@yourdomain.com:/var/www/vhosts/yourdomain.com/httpdocs/wp-content/themes/rivaland/
```

### Step 9: Configure SSL Certificate

1. **In Plesk:**
   - Go to **Domains** → Your domain → **SSL/TLS Settings**
   - Install Let's Encrypt certificate (free)
   - Enable **Redirect from HTTP to HTTPS**

2. **Update WordPress:**
   - Settings → General
   - WordPress Address (URL): `https://yourdomain.com`
   - Site Address (URL): `https://yourdomain.com`
   - Save Changes

### Step 10: Test the Site

- [ ] **Homepage loads correctly**
- [ ] **All pages accessible**
- [ ] **Images/media display**
- [ ] **Navigation works**
- [ ] **Forms submit (if any)**
- [ ] **Admin login works**
- [ ] **ACF fields display**
- [ ] **Custom post types work**
- [ ] **Mobile menu functions**
- [ ] **No console errors**
- [ ] **SSL certificate valid**

---

## Post-Migration Tasks

### 1. WordPress Admin

- [ ] **Login to WordPress Admin**
  - URL: `https://yourdomain.com/wp-admin`
  - Use your local admin credentials

- [ ] **Update permalinks**
  - Settings → Permalinks
  - Click **Save Changes** (no need to change settings)

- [ ] **Check ACF field groups**
  - Custom Fields → Field Groups
  - Verify all field groups are synced
  - Click **Sync available** if needed

- [ ] **Update plugins**
  - Plugins → Installed Plugins
  - Update all plugins to latest versions

- [ ] **Check theme settings**
  - Appearance → Customize
  - Verify logo, colors, etc.

### 2. Performance Optimization

- [ ] **Enable caching**
  - Install caching plugin (WP Super Cache, W3 Total Cache, etc.)
  - Configure for your server

- [ ] **Optimize images**
  - Use image optimization plugin
  - Compress existing images

- [ ] **Minify CSS/JS**
  - Use optimization plugin
  - Or ensure production build is minified

### 3. Security

- [ ] **Change admin password**
- [ ] **Install security plugin** (Wordfence, iThemes Security)
- [ ] **Enable two-factor authentication**
- [ ] **Review user accounts**
- [ ] **Check file permissions**
- [ ] **Set up regular backups**

### 4. Monitoring

- [ ] **Set up Google Analytics**
- [ ] **Configure Search Console**
- [ ] **Set up uptime monitoring**
- [ ] **Enable error logging**

### 5. Backup Strategy

- [ ] **Configure Plesk backups**
  - Go to **Backups** in Plesk
  - Set up automatic daily backups
  - Store off-server if possible

- [ ] **Test backup restoration**

---

## Troubleshooting

### Issue: Site shows "Error establishing database connection"

**Solutions:**
1. Check `wp-config.php` database credentials
2. Verify database exists in Plesk
3. Check database user has proper permissions
4. Verify database host (usually `localhost`)

### Issue: Images not displaying

**Solutions:**
1. Check file permissions on `wp-content/uploads`
2. Verify images were uploaded correctly
3. Run URL replacement again (images may have old URLs)
4. Check `.htaccess` file exists

### Issue: 404 errors on pages

**Solutions:**
1. Go to Settings → Permalinks → Save Changes
2. Check `.htaccess` file exists and is correct
3. Verify mod_rewrite is enabled on server
4. Check Plesk PHP settings

### Issue: CSS/JS not loading

**Solutions:**
1. Rebuild assets: `npm run production`
2. Check file paths in browser console
3. Verify `dist/` directory uploaded
4. Clear browser cache
5. Check file permissions

### Issue: ACF fields not showing

**Solutions:**
1. Go to Custom Fields → Field Groups → Sync available
2. Verify ACF plugin is active
3. Check ACF JSON files uploaded to `acf-json/`
4. Clear WordPress cache

### Issue: Mixed content warnings (HTTP/HTTPS)

**Solutions:**
1. Run search-replace for all URLs
2. Use plugin like "SSL Insecure Content Fixer"
3. Check hardcoded URLs in theme files
4. Update database URLs

---

## Rollback Plan

If something goes wrong:

1. **Keep local DDEV site running** until migration is confirmed
2. **Backup Plesk database** before making changes
3. **Document all changes** made during migration
4. **Test on staging subdomain** first if possible

**To rollback:**
1. Restore previous database backup
2. Revert file changes
3. Fix any issues
4. Try migration again

---

## Quick Reference: File Locations

### DDEV Local
- Document root: `rivaland-wp/public/`
- Database: `db` (via DDEV)
- URL: `https://rivaland.ddev.site`

### Plesk Production
- Document root: `/var/www/vhosts/yourdomain.com/httpdocs/`
- Database: Created in Plesk Databases
- URL: `https://yourdomain.com`

---

## Migration Checklist Summary

### Pre-Migration
- [ ] Backup local database
- [ ] Document local URLs
- [ ] Create Plesk domain
- [ ] Create Plesk database
- [ ] Note all credentials

### Migration
- [ ] Export local database
- [ ] Replace URLs in database
- [ ] Upload files to Plesk
- [ ] Import database to Plesk
- [ ] Configure wp-config.php
- [ ] Set file permissions
- [ ] Build production assets
- [ ] Configure SSL

### Post-Migration
- [ ] Test all pages
- [ ] Update permalinks
- [ ] Sync ACF fields
- [ ] Update plugins
- [ ] Configure backups
- [ ] Set up security
- [ ] Test forms/functionality

---

## Support Resources

- **Plesk Documentation:** https://docs.plesk.com/
- **WordPress Migration:** https://wordpress.org/support/article/moving-wordpress/
- **WP-CLI:** https://wp-cli.org/
- **Search-Replace-DB:** https://github.com/interconnectit/Search-Replace-DB

---

## Notes

- Always test on a staging subdomain first if possible
- Keep local site running until production is confirmed working
- Document all credentials securely
- Take screenshots of settings before migration
- Test thoroughly before going live
- Have a rollback plan ready

---

**Last Updated:** 2025-01-XX
**Version:** 1.0

