Installation
------
php.ini doit avoir cette extension activée pour faire fonction phpoffice
: extension=gd

To handle date functions on doctrine, we have installed a bundle doctrineextensions (https://github.com/beberlei/DoctrineExtensions/tree/master)

To handle datepicker on different browser, we have installed bootstrap datepicker, a js bundle (https://bootstrap-datepicker.readthedocs.io/en/latest/markup.html)

To generate codebar, we have use the bundle Picqer (https://github.com/picqer/php-barcode-generator)

To translate date in french in twig, we add the extra bundle for twig @composer require twig/extra-bundle, @composer require twig/intl-extra (https://twig.symfony.com/doc/2.x/filters/format_datetime.html)


Commande à éxecuter pour installer le projet sur une nouvelle instance

Composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

npm install
npm run build