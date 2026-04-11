web: sh ./start.sh
worker: php artisan queue:work --sleep=3 --tries=3 --max-jobs=1000
