# Activity History
## Install
```
php artisan vendor:publish --provider="Treconyl\ActivityHistory\ActivityHistoryServiceProvider" --tag=config
php artisan vendor:publish --provider="Treconyl\ActivityHistory\ActivityHistoryServiceProvider" --tag=models
or
php artisan vendor:publish --provider="Treconyl\ActivityHistory\ActivityHistoryServiceProvider"

php artisan migrate
or
php artisan migrate --path=/database/migrations/0000_00_00_000000_create_activity_history_table.php
```