Étape 1 : Être sûr d'avoir tous les fichiers (si ça a été téléchargé normalement, aucun problème).
Étape 2 : Avoir un serveur local de type Wamp ou Xampp avec une base de données PhpMyAdmin et le lancer.
Étape 3 : Vérifier que l'accès root n'a pas de mot de passe (identifiant : root // mot de passe : ).
Étape 4 : Importer dans PhpMyAdmin, sur le serveur, le fichier db.sql trouvable dans le dossier. Il crééra la base de données, 
		et insérera les données dedans. Cela peut prendre un peu (voire beaucoup) de temps.
Étape 5 : Aller dans le dossier téléchargé, et y ouvrir un CMD. Ici, on pourra exécuter "symfony.exe server:start". 
		L'utilitaire est inclus dans le dossier et ne devrait pas poser de problème.
Étape 6 : Le serveur Symfony devrait s'être lancé, et sera donc disponible à l'adresse http://localhost:8000. 
		Si tout a fonctionné, il n'y a plus rien à faire.