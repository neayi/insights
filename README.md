[![Build Status](https://travis-ci.org/neayi/insights.svg?branch=master)](https://travis-ci.org/github/neayi/insights) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/neayi/insights/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/neayi/insights/?branch=master) [![Code Intelligence Status](https://scrutinizer-ci.com/g/neayi/insights/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

# Insights
A laravel item that holds the user logic

Pour jouer les tests unitaires, ces tests vérifient que le métier est correctement
 implémenté en simulant l'ensemble des services de l'infrastructure :

`vendor/bin/phpunit tests/Unit`

Avec la stack docker tripleperformance:

```bash
docker-compose run --rm --user="$UID:$GID" insights_php vendor/bin/phpunit tests/Unit
```
ou
```
php artisan test
```

#Test intégration domain + sql
Pour jouer les tests d'intégration entre domaine métier et la couche infrastructure
(Va tester la couche SQLRepository avec le domaine métier)
### ⚠️ Attention les tests effacent la base de données 

`vendor/bin/phpunit tests/Unit/ -c phpunit-ti-domain-sql.xml`



# Fichier de configuration Neayi 

Les variables de configuration Neayi sont dans le fichier config/neayi.php 
Il faut au maximum accèder aux variables d'environnement en passant par le fichier de configuration.
Via le helper config(neayi.) et non env('WIKI_URL')


#Vocabulaire métier : 

Context : Correspond au contexte dans lequel l'utilisateur (chercheur, agriculteur, étudiant...) prend la parole
