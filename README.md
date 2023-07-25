# origin-data-project

$ docker exec -it origin-data-php-container bash

`# php composer install`

`# php bin/console doctrine:database:create`

`# php bin/console doctrine:migrations:migrate`

`# php bin/console app:generate-users`
