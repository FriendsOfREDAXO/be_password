# Passwort zurücksetzen

Dieses AddOn ermöglicht es Redakteuren und Administratoren, ihr Passwort zurückzusetzen, wenn sie den Zugang zu ihrem REDAXO-Backend vergessen haben oder ein neues Passwort benötigen.

![Screenshot](https://raw.githubusercontent.com/FriendsOfREDAXO/be_password/assets/be_password_01.png)

## Szenario

Das AddOn ist besonders nützlich in folgenden Situationen:

- **Redakteure** haben ihr Passwort vergessen und können sich nicht mehr am Backend anmelden
- **Administratoren** haben ihr Passwort vergessen und benötigen eine Möglichkeit zur Wiederherstellung des Zugangs
- **Passwort-Neuvergabe** ist erforderlich, beispielsweise aus Sicherheitsgründen oder bei Personalwechsel
- **Selbstständige Passwort-Wiederherstellung** ohne Eingriff eines Systemadministrators

Das AddOn ergänzt einen Link »Passwort vergessen?« auf der Login-Seite. Nutzer können im zugehörigen Formular ihre E-Mail-Adresse angeben und erhalten eine Nachricht mit einem sicheren Link, über den sie ihr Passwort eigenständig zurücksetzen können.

## Voraussetzungen

Für das ordnungsgemäße Funktionieren dieses AddOns sind folgende Voraussetzungen erforderlich:

- **PHPMailer-AddOn**: Das AddOn benötigt das PHPMailer-AddOn (Version 2 oder höher) für den E-Mail-Versand. Stellen Sie sicher, dass dieses installiert und korrekt konfiguriert ist.
- **Funktionierender E-Mail-Versand**: Der Server muss für den E-Mail-Versand konfiguriert sein, damit die Passwort-Reset-Links verschickt werden können.
- **Gültige E-Mail-Adressen**: Benutzerkonten müssen über gültige E-Mail-Adressen verfügen, da die Passwort-Reset-Links an diese Adressen gesendet werden.

## Alternative Methoden

Sollte das AddOn nicht verfügbar sein oder der E-Mail-Versand nicht funktionieren, können Administratoren das Passwort auch über die REDAXO-Konsole zurücksetzen. Weitere Informationen dazu finden Sie in der [offiziellen REDAXO-Dokumentation](https://redaxo.org/doku/master/konsole).

## Lizenz

MIT Lizenz, siehe [LICENSE.md](https://github.com/alexplusde/neues/blob/master/LICENSE)  

## Autor

**Friends of REDAXO**

## Credits

Ursprünglich entwickelt von [@akuehnis](https://github.com/akuehnis).

