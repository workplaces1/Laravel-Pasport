
## Steps 1
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
//app/Providers/AppServiceProvider.php
use Laravel\Passport\Passport;
    public function boot()
    {
        parent::boot();
        Passport::routes();
    }


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
