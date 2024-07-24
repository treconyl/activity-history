# Activity History
## Install
```
composer require treconyl/ActivityHistory

php artisan vendor:publish --provider="Treconyl\ActivityHistory\ActivityHistoryServiceProvider" --tag=config
php artisan vendor:publish --provider="Treconyl\ActivityHistory\ActivityHistoryServiceProvider" --tag=models
or
php artisan vendor:publish --provider="Treconyl\ActivityHistory\ActivityHistoryServiceProvider"

php artisan migrate
```