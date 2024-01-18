ddev start

ddev composer create symfony/skeleton

ddev php bin/console app:crypto-analyze asset="ddfdf" address="sdsdsd"

ddev composer require  symfony/skeleton


add composer packages

webimage_extra_packages: [php7.4-gmp]

ddev composer require doctrine

ddev php bin/console make:migration


ddev php bin/console doctrine:migrations:migrate
