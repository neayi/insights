##Brique central de triple performance : 

Pour jouer les tests unitaires, ces tests vérifient que le métier est correctement
 implémenté en simulant l'ensemble des services de l'infrastructure : 

`vendor/bin/phpunit tests/Unit`

Pour jouer les tests d'intégration entre domaine métier et la couche infrastructure
(Va tester la couche SQLRepository avec le domaine métier)

`vendor/bin/phpunit tests/Unit/ -c phpunit-ti-domain-sql.xml`

