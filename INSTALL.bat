@echo off
echo ========================================
echo   Multivendor Marketplace Setup
echo   By Claude for Firas
echo ========================================
echo.

echo [1/8] Copying environment file...
copy .env.example .env
echo DONE!
echo.

echo [2/8] Generating application key...
php artisan key:generate
echo DONE!
echo.

echo [3/8] Installing PHP dependencies...
composer install --no-interaction
echo DONE!
echo.

echo [4/8] Installing JavaScript dependencies...
call npm install
echo DONE!
echo.

echo [5/8] Please configure your database in .env file
echo Edit .env and set:
echo   DB_DATABASE=multivendor
echo   DB_USERNAME=root
echo   DB_PASSWORD=
echo.
echo Press any key when database is configured...
pause > nul

echo [6/8] Running database migrations...
php artisan migrate:fresh --seed
echo DONE!
echo.

echo [7/8] Creating storage link...
php artisan storage:link
echo DONE!
echo.

echo [8/8] Installing Laravel Breeze...
composer require laravel/breeze --dev
php artisan breeze:install blade --no-interaction
call npm install
echo DONE!
echo.

echo ========================================
echo   Setup Complete!
echo ========================================
echo.
echo Next steps:
echo 1. Run: npm run dev (in one terminal)
echo 2. Visit: http://multivendor.test
echo.
echo Test accounts:
echo   admin@multivendor.tn / password
echo   vendor1@multivendor.tn / password
echo   customer@multivendor.tn / password
echo.
echo Press any key to exit...
pause > nul
