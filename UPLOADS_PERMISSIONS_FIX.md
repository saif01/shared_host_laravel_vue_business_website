# Fix Image Access Issue on Shared Hosting (cPanel)

## Problem
When you upload images directly via cPanel File Manager, they may not be accessible through the web browser, even though they exist in the `public/uploads/` folder. This is typically caused by:

1. **Incorrect file permissions** - Files uploaded via cPanel may have wrong permissions
2. **Missing .htaccess** - The uploads folder needs proper .htaccess configuration
3. **Web server restrictions** - Apache/Nginx may block access to certain files

## Solution

### Method 1: Automatic Fix (Recommended)

1. **Upload the fix script** to your Laravel root directory:
   - File: `fix-uploads-permissions.php`

2. **Run the script** via browser:
   ```
   https://yourdomain.com/fix-uploads-permissions.php
   ```
   
   Or via SSH/command line:
   ```bash
   php fix-uploads-permissions.php
   ```

3. **Delete the script** after running it for security!

### Method 2: Manual Fix via cPanel

1. **Fix File Permissions:**
   - Go to cPanel → File Manager
   - Navigate to `public/uploads/` folder
   - Select all files and folders
   - Right-click → Change Permissions
   - Set permissions:
     - **Files**: `644` (rw-r--r--)
     - **Folders**: `755` (rwxr-xr-x)

2. **Verify .htaccess exists:**
   - Check if `public/uploads/.htaccess` file exists
   - If not, create it with the content from `public/uploads/.htaccess` in this repository

### Method 3: Via SSH (if available)

```bash
# Navigate to your Laravel project
cd /path/to/your/laravel/project

# Fix permissions for uploads folder
find public/uploads -type d -exec chmod 755 {} \;
find public/uploads -type f -exec chmod 644 {} \;

# Verify .htaccess exists
ls -la public/uploads/.htaccess
```

## Required Permissions

- **Directories**: `755` (drwxr-xr-x)
  - Owner: read, write, execute
  - Group: read, execute
  - Others: read, execute

- **Files**: `644` (rw-r--r--)
  - Owner: read, write
  - Group: read
  - Others: read

## .htaccess Configuration

The `.htaccess` file in `public/uploads/` ensures:
- Direct access to image files
- Proper MIME types for images
- Security (prevents PHP execution)
- CORS support for images

## Prevention

The `UploadController` has been updated to automatically:
- Set correct permissions (644) when files are uploaded via Laravel
- Create `.htaccess` file if it doesn't exist
- Ensure proper folder permissions (755)

## Testing

After fixing permissions, test by accessing an image directly:
```
https://yourdomain.com/uploads/branding/your-image.jpg
```

If you see the image, the fix worked!

## Troubleshooting

### Images still not accessible?

1. **Check file permissions:**
   ```bash
   ls -la public/uploads/branding/
   ```
   Files should show `-rw-r--r--` and folders `drwxr-xr-x`

2. **Check .htaccess:**
   - Verify `public/uploads/.htaccess` exists
   - Check for syntax errors

3. **Check server error logs:**
   - cPanel → Errors → Error Log
   - Look for permission denied errors

4. **Contact hosting support:**
   - Some shared hosts have additional restrictions
   - Ask them to verify file permissions and .htaccess support

## Security Note

- The `.htaccess` file prevents PHP execution in uploads folder
- Only image and document file types are allowed
- Directory browsing is disabled

## Additional Notes

- Files uploaded through Laravel application automatically get correct permissions
- Files uploaded via cPanel File Manager may need manual permission fix
- Always delete `fix-uploads-permissions.php` after use for security

