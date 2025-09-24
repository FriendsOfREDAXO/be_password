# REDAXO be_password Addon

The be_password addon is a REDAXO CMS extension that provides password reset functionality for backend users. Users can request password reset emails through a "Forgot Password?" link on the REDAXO login page.

**ALWAYS follow these instructions first and fallback to search or bash commands only when you encounter unexpected information that does not match the info here.**

## Working Effectively

### Prerequisites and Environment Setup
- PHP 8.0+ is required (PHP 8.3+ recommended)
- This is a REDAXO addon - it does NOT require Node.js, npm, composer, or any build tools
- No database setup required for development - the addon handles database tables automatically

### Validation and Development Workflow
- **ALWAYS validate PHP syntax before making changes:**
  ```bash
  find . -name "*.php" -exec php -l {} \;
  ```
  - Takes ~1 second to complete
  - NEVER CANCEL: This validation is fast but critical for catching syntax errors
- **Test individual services:**
  ```bash
  php -r "require 'src/Services/RandomService.php'; \$r = new BePassword\Services\RandomService(); echo \$r->createToken(10);"
  php -r "require 'src/Services/FilterService.php'; \$f = new BePassword\Services\FilterService(); echo \$f->filterEmail('test@example.com');"
  ```

### No Build Process Required
- This addon has NO build, compilation, or bundling step
- Files are deployed directly to REDAXO installations
- JavaScript and CSS files are served as static assets
- **Do NOT look for or try to run:**
  - npm install, npm run build
  - composer install, composer update  
  - make, cmake, or any build tools
  - webpack, gulp, grunt configurations

## Key Architecture

### Project Structure
```
/home/runner/work/be_password/be_password/
├── boot.php                 # Main entry point - autoloader and routing
├── install.php              # Database table creation
├── uninstall.php           # Database table cleanup  
├── package.yml             # REDAXO addon metadata
├── src/
│   ├── Controller/
│   │   └── DefaultController.php    # Main password reset logic
│   └── Services/
│       ├── RenderService.php        # View rendering
│       ├── RandomService.php        # Token generation
│       └── FilterService.php        # Input sanitization
├── views/                   # PHP template files
│   ├── index.php           # "Forgot Password?" link
│   ├── form.php            # Email input form
│   └── reset.php           # New password form
├── assets/
│   ├── be_password.css     # Minimal styling
│   └── javascript/
│       └── be_password.js  # Frontend handlers
└── lang/                   # Internationalization
    ├── de_de.lang          # German translations
    └── en_gb.lang          # English translations
```

### Core Components
- **DefaultController**: Handles all password reset workflow (form display, email sending, password updates)
- **RenderService**: Simple view renderer that processes PHP templates with variable injection
- **RandomService**: Generates secure random tokens for password reset links
- **FilterService**: Input sanitization utilities
- **JavaScript handlers**: BePassHandler object manages frontend form interactions via AJAX

## Development Guidelines

### Making Changes
- **Always validate PHP syntax after ANY change to .php files**
- The addon integrates with REDAXO's existing login page - test UI changes carefully
- Email functionality requires REDAXO's phpmailer addon (specified in package.yml)
- Database operations use REDAXO's rex_sql classes - do not write raw SQL

### Testing Your Changes
- **CRITICAL**: This addon cannot be fully tested without a running REDAXO installation
- **Syntax validation is the primary testing available:**
  ```bash
  cd /home/runner/work/be_password/be_password
  find . -name "*.php" -exec php -l {} \;
  ```
- **Service-level testing:**
  ```bash
  # Test token generation
  php -r "require 'src/Services/RandomService.php'; \$r = new BePassword\Services\RandomService(); echo 'Token: ' . \$r->createToken(32) . PHP_EOL;"
  
  # Test input filtering  
  php -r "require 'src/Services/FilterService.php'; \$f = new BePassword\Services\FilterService(); echo 'Filtered: ' . \$f->filterString('test\nstring') . PHP_EOL;"
  ```

### Manual Validation Scenarios
**Since full functional testing requires REDAXO installation, focus on these validation steps:**

1. **Syntax Validation** (ALWAYS run this):
   ```bash
   find . -name "*.php" -exec php -l {} \;
   ```

2. **Service Testing** (when modifying services):
   ```bash
   php -r "require 'src/Services/RandomService.php'; \$service = new BePassword\Services\RandomService(); var_dump(\$service->createToken(50));"
   ```

3. **View Rendering** (when modifying templates):
   - Views cannot be tested in isolation as they require REDAXO classes (rex_i18n, etc.)
   - Only validate PHP syntax of view files: `php -l views/filename.php`

## Deployment and Release

### GitHub Actions Workflow
- Releases are automatically published to REDAXO installer via `.github/workflows/publish-to-redaxo.yml`
- **No manual deployment steps required**
- Workflow triggers on GitHub release publication
- Uses FriendsOfREDAXO/installer-action to publish to MyREDAXO

### Release Process
1. Update version in `package.yml` 
2. Update `CHANGELOG.md` with new features/fixes
3. Create GitHub release with tag matching package.yml version
4. GitHub Actions automatically publishes to REDAXO installer

## Common Development Tasks

### Adding New Language Keys
1. Add key to `lang/de_de.lang` (German - primary language)
2. Add corresponding English translation to `lang/en_gb.lang`
3. Use `rex_i18n::msg('your_key')` in PHP code
4. **Always validate syntax after language file changes**

### Modifying Email Templates
- Email content is defined in language files using `be_password_mail_text` key
- HTML emails supported via `rex_mailer->Body`
- Plain text version auto-generated via `strip_tags()`

### Frontend JavaScript Changes
- Modify `assets/javascript/be_password.js`
- Uses jQuery (available in REDAXO backend)
- BePassHandler object manages AJAX form submissions
- **Test JavaScript syntax:** Use browser developer tools or online validators

## Troubleshooting

### Common Issues
- **PHP syntax errors**: Run `find . -name "*.php" -exec php -l {} \;` to identify
- **Missing REDAXO classes**: These are normal in isolated testing - classes like `rex_i18n`, `rex_sql` are provided by REDAXO
- **Email not sending**: Requires properly configured REDAXO installation with phpmailer addon

### Debugging Tips
- Check `boot.php` for routing logic if controllers aren't being called
- Verify `package.yml` dependencies if REDAXO classes are missing
- Language keys not working? Check both `de_de.lang` and `en_gb.lang` files

## Repository Commands Reference

### Essential Commands (Run these for any change)
```bash
# ALWAYS run first - validate PHP syntax
find . -name "*.php" -exec php -l {} \;

# Test RandomService 
php -r "require 'src/Services/RandomService.php'; echo (new BePassword\Services\RandomService())->createToken(10);"

# Test FilterService
php -r "require 'src/Services/FilterService.php'; echo (new BePassword\Services\FilterService())->filterEmail('test@domain.com');"
```

### File Structure Reference
```bash
# View repository structure
ls -la /home/runner/work/be_password/be_password/

# Key directories
ls -la src/          # Core PHP classes
ls -la views/        # Template files  
ls -la assets/       # CSS and JavaScript
ls -la lang/         # Translation files
```

Fixes #37.