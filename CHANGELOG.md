# Changelog

## [3.0.0] – 27.09.2025

### 🚨 Breaking Changes

* **PHP Version:** Minimum requirement updated to PHP >= 8.3
* **Namespace:** Changed from `BePassword\` to `FriendsOfRedaxo\BePassword\` for better organization
* **Directory Structure:** Renamed `src/` to `lib/` following REDAXO conventions

### 🔒 Security Improvements

* **Secure Token Generation:** Replaced insecure `rand()` with cryptographically secure `random_int()` 
* **Input Validation:** Enhanced email validation and sanitization to prevent injection attacks
* **Rate Limiting:** Added protection against brute-force attacks (max 3 attempts per 15 minutes)
* **Controller Security:** Added validation to prevent malicious code execution through URL manipulation
* **Token Security:** Improved password reset token validation (alphanumeric characters only)
* **Timing Attack Protection:** Implemented consistent response times to prevent account enumeration
* **Access to global vars:** use of corresponding `rex_request`-methods instead of direct access to $_GET, $_POST, and $_SERVER 

### 🐛 Bug Fixes

* **Critical Method Error:** Fixed fatal error in FilterService that would crash the addon
* **Password Reset Form:** Corrected password input field displaying email address
* **Error Handling:** Fixed undefined variables that could cause PHP warnings
* **JavaScript Improvements:** Fixed compatibility issues and improved error handling

### ✨ New Features

* **Modern PHP 8.3+ Support:** Optimized code with latest PHP features for better performance
* **Enhanced Error Messages:** Added user-friendly error messages for various scenarios
* **Improved Browser Compatibility:** Better support for different browsers and email clients
* **Cross-Platform Reset Links:** Password reset links now work reliably from any device/browser

### 🔧 Technical Improvements

* **Code Quality:** Modern PHP syntax with proper type declarations
* **Performance:** Optimized database queries and reduced memory usage  
* **Standards Compliance:** Code follows current PHP and REDAXO best practices
* **Maintainability:** Improved code structure and documentation

### 📋 What's Better for Users

* **More Reliable:** Password reset emails work from any browser or email client
* **More Secure:** Protection against common web attacks and brute-force attempts  
* **Better Performance:** Faster loading and processing with PHP 8.3+ optimizations
* **Future-Proof:** Updated codebase ready for future REDAXO versions

### 🔄 Upgrade Notes

* **Automatic Migration:** Most improvements work automatically after update
* **PHP Requirement:** Ensure your server runs PHP 8.3 or higher
* **No Configuration Changes:** All security improvements are enabled by default

### Sonstige Änderungen

* **streamline boot.php**: not nessesary or unused code removed



---

## [2.0.0](https://github.com/FriendsOfREDAXO/be_password/releases/tag/2.0.0) – 12.10.2020

### Breaking Changes

* Konfigurationsseite im Backend entfernt [(#20)](https://github.com/FriendsOfREDAXO/be_password/issues/20)  
Bitte beachten: Eine Anpassung des Textes der Bestätigungs-E-Mail ist damit leider nicht mehr ohne weiteres möglich.

### Features

* Kompatibilität mit neuer REX-Loginseite herstellen [(#21)](https://github.com/FriendsOfREDAXO/be_password/pull/21)
* Datenschutz: Nicht erkennbar machen, dass ein Benutzerkonto existiert [(#14)](https://github.com/FriendsOfREDAXO/be_password/issues/14)
* Ausgabe des Link in Plaintext-E-Mail korrigiert [(#15)](https://github.com/FriendsOfREDAXO/be_password/issues/15)
* Mehrsprachigkeit implementiert [(#10)](https://github.com/FriendsOfREDAXO/be_password/issues/10)
* Datenbank-Anpassungen bei Installation und Deinstallation aktualisiert [(#17)](https://github.com/FriendsOfREDAXO/be_password/issues/17)


## [1.1.0](https://github.com/FriendsOfREDAXO/be_password/releases/tag/1.1.0) – 23.10.2017

### Features

* Passwortregeln beachten (REX 5.4) [(#9)](https://github.com/FriendsOfREDAXO/be_password/issues/9)


## [1.0.0](https://github.com/FriendsOfREDAXO/be_password/releases/tag/1.0.0) – 20.10.2017

Erste Veröffentlichung bei Friends Of REDAXO. Danke an [@akuehnis](https://github.com/akuehnis) für die Entwicklung!
