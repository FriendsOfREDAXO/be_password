# Changelog

## [3.0.0] – 25.09.2025

### 🚨 Breaking Changes

* **PHP Version:** Minimum requirement updated to PHP >= 8.3
* **Namespace:** Changed from `BePassword\` to `FriendsOfRedaxo\BePassword\`
* **Directory Structure:** Renamed `src/` to `lib/` for better REDAXO compliance

### 🔒 Critical Security Fixes

* **Cryptographic Security:** Replaced deprecated `rand()` with cryptographically secure `random_int()` for token generation
* **CSRF Protection:** Implemented comprehensive CSRF token validation using REDAXO's `rex_csrf_token`
* **Input Validation:** Added proper email validation and sanitization with `filter_var()`
* **Dynamic Class Instantiation:** Added regex validation to prevent code injection vulnerabilities
* **Rate Limiting:** Implemented IP-based rate limiting (3 requests per 15 minutes)
* **Timing Attack Prevention:** Added consistent response delays to prevent account enumeration
* **Token Validation:** Enhanced token format validation (alphanumeric only)

### 🐛 Bug Fixes

* **Method Call Error:** Fixed missing `$this->` prefix in `FilterService::filterTextarea()`
* **Undefined Variables:** Fixed potential undefined `$user_id` variable in error scenarios
* **Password Field:** Corrected password input field in reset form that was using email variable
* **Exception Handling:** Added proper `\Exception` namespace references

### ✨ Features & Improvements

* **PHP 8.3+ Optimization:** 
  - Modern type declarations for all methods
  - Arrow functions for array filtering
  - Null coalescing assignment operators (`??=`)
  - Numeric separators for better readability (`100_000`)
  - Modern array syntax throughout
* **Error Handling:** Comprehensive try-catch blocks for database operations and token generation
* **Language Support:** Added missing error messages for CSRF and rate limiting
* **Code Quality:** Improved JavaScript with strict equality operators and proper variable declarations
* **Security Headers:** Enhanced URL encoding for tokens in JavaScript

### 🔧 Technical Improvements

* **Autoloader:** Updated class loading mechanism for new namespace structure
* **Type Safety:** Added proper return type declarations for PHP 8.3+
* **Performance:** Optimized array operations and reduced function calls
* **Standards Compliance:** All code follows modern PHP 8.3+ best practices

### 📖 Documentation

* Enhanced inline documentation with proper type hints
* Updated README for new security features
* Comprehensive changelog with migration notes

### 🔄 Migration Guide

When upgrading from 2.x to 3.0:

1. **PHP Version:** Ensure your server runs PHP >= 8.3
2. **Custom Integrations:** Update any custom code referencing the old `BePassword\` namespace to `FriendsOfRedaxo\BePassword\`
3. **File Paths:** If you have custom code referencing `src/` directory, update to `lib/`
4. **Security:** No action needed - all security improvements are automatic

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
