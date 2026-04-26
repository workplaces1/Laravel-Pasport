
## Steps 1
composer install
npm install
php artisan install:api --passport
## Step 2
composer require laravel/passport
## Step 3
php artisan migrate:fresh
## Step 4
php artisan passport:install
## Step 5
php artisan passport:client --personal

## Step 6
//user modal
use Laravel\Passport\HasApiTokens;
use HasApiTokens;

## Step 7
/bootstrap/app.php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // prevent redirect for api request
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('api/*')) {
                return null;
            }
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['status' => 0, 'message' => 'Unauthenticated',], 401);
            }
        });

        $exceptions->render(function (NotFoundHttpException $e, $request) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid API endpoint. URL not found.'
            ], 404);
        });

        $exceptions->render(function (MethodNotAllowedHttpException $e, $request) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid request method. Please check HTTP method.'
            ], 405);
        });
    })->create();



## Step 8
//route
Route::middleware('auth:api')->group(function () {
    Route::get("/user/profile", [ApiController::class, "profile"]);
});


## Step 9
            $user = User::create([
                "name" => $req->name,
                "email" => $req->email,
                "password" => Hash::make($req->password)
            ]);

            $token = $user->createToken("APIToken")->accessToken;


## step 10
// Passing Authrization header in postman

Headers
Key	Value
Authorization	Bearer <your-access-token>
