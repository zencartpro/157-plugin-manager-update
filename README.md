# 157-plugin-manager-update
Plugin Manager Update für Zen Cart 1.5.7j deutsch

## Hinweis: 
Freigegebene getestete Versionen für den Einsatz in Livesystemen ausschließlich unter Releases herunterladen:
* https://github.com/zencartpro/157-plugin-manager-update/releases

## Sinn und Zweck 
* Viele neuere Module sind als "encapsulated plugin" konzipiert.
* Seit Zen Cart 1.5.7 deutsch wurde ein Plugin-Manager hinzugefügt, um die Unterstützung für zusätzliche Admin Module unter Verwendung der neuen Architektur zu ermöglichen.
* Diese Unterstützung wurde seit Zen Cart 1.5.7j schrittweise verbessert und bietet nun auch Unterstützung für Frontend Plugins.
* Die Plugin-Architektur ermöglicht es, Module in einer hierarchischen Verzeichnisstruktur einzubinden, die die Verzeichnisstruktur von Zen Cart nachahmt. 
* Damit müssen Plugin-Dateien nicht mehr in den verschiedenen Zen Cart-Verzeichnissen abgelegt werden, sondern befinden sich in Verzeichnissen unterhalb eines zc_plugins-Verzeichnisses.
* In der Regel sind bei der Integration solcher Module keinerlei Änderungen bestehender Corefiles mehr nötig.
* Die Module werden dann via Zen Cart Administration unter Module > Plugin-Manager installiert/deinstalliert und aktiviert/deaktiviert.
Dieses Update bringt die Plugin Manager Funktionalität von 1.5.7j deutsch auf den neuesten Stand von Oktober 2025, so dass auch neuere encapsulated plugins in der deutschen Zen Cart Version 1.5.7j genutzt werden können.

## Dieses Update ist Voraussetzung für die Verwendung des neuen PayPal Checkout Moduls (paypalr) ab Version 1.3.0

## INSTALLATION

1)
Shop in den Wartungsmodus setzen

2)
Dateien/Ordner im Ordner NEUE DATEIEN in der vorgegebenen Struktur ins Shopverzeichnis hochladen. Dabei werden keine bestehenden Dateien überschrieben

3)
Im Ordner GEAENDERTE DATEIEN den Ordner DEINADMIN auf den Namen Ihres Adminverzeichnisses umbenennen.
Wenn Sie Zen Cart 1.5.7j gerade frisch installiert haben und noch keinerlei Änderungen an den Dateien vorgenommen haben, können Sie nun alle Dateien/Ordner aus dem Ordner GEAENDERTE DATEIEN in der vorgegebenen Struktur in die Zen Cart Installation hochladen. Dabei werden dann etliche Dateien überschrieben.
Wenn Sie Zen Cart 1.5.7j schon länger im Einsatz haben und schon einmal Änderungen an Dateien vorgenommen oder andere Module eingebaut haben, dann laden Sie die Dateien keinesfalls einfach hoch.
Vergleichen Sie alle Dateien im Ordner GEAENDERTE DATEIEN mit den entsprechenden Dateien in Ihrem Shop und nehmen Sie die Änderungen manuell per WinMerge oder BeyondCompare vor.
Dann spielen Sie die geänderten Dateien in der gezeigten Struktur ein.

4)
Wartungsmodus wieder deaktivieren
