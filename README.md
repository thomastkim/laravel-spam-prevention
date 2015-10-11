# Prevent Spam With This Laravel Package

This package helps you fend off spam bots by using randomized input names and honeypots.

## Introduction

Spam is a huge problem for the Web. Form-filling bots read the form presented to them and automatically fill out the fields. Another type of bot record the POST data and replay it back to the submission URL. This package helps fend off these bots with minimal effort.

The package randomizes the input names so that bots cannot make educated guesses. Example:

```
Before: <input type="text" name="username">

After: <input type="text" name="neeS9dJDeQCbNvN3lgyxIkdQ6R1l2GHEEnmt">
```

In addition to this, this package allows you to add a random number of hidden inputs aka "honeypots" (or bait). Your regular users would never see these, but the bots do. Bots tend to fill out all inputs so you can then easily reject any forms that have the "bait" inputs filled out.

By doing this, bots will struggle to decipher your form, record the POST data, and spam your site.

## Installation

To install this package, just follow these quick and easy steps.

### Composer

Pull this package through composer by opening `composer.json` file and adding this within `require`:

```
"kim/defender": "~1.0"
```

Afterward, run either `composer update` or `composer install`.

### Providers and Aliases

Next, open `config/app.php` and add this to your providers array:

```
Kim\Defender\DefenderServiceProvider::class
```

and this to your aliases array:

```
'Defender' => Kim\Defender\DefenderFacade::class
```

## Usage

### Creating Input Fields

Normally, this is how you would create a username input field:

```html
<input type="text" name="username">
```

However, we want to randomize the name, and to do so, we use the Defender facade:

```html
<input type="text" name="{{ Defender::username() }}">
```

Here are some other common inputs (like email and password) that are all built-in to the package.

```html
<input type="email" name="{{ Defender::email() }}">
<input type="password" name="{{ Defender::password() }}">
```

You can also easily create your own custom type by using the `get` method and passing in the name of your input.

```html
<input type="text" name="{{ Defender::get('custom') }}">
<textarea name="{{ Defender::get('message') }}"></textarea>
<input type="date" name="{{ Defender::get('some_date_field') }}">
```

With that said, I'm sure a lot of you are wondering how you retrieve the values if the names are all randomized. That's easy. In your controller, you can easily retrieve these values by using the same methods.

Example Controller:

```
// Import it at the top
use Defender;


public function example(Request $request)
{
	$email = $request->input( Defender::email() );
	$username = $request->input( Defender::username() );

	$custom = $request->input( Defender::get('custom') );
}
```

### Honeypots

This is all great, but to make it even more bulletproof, we need some honeypots. These are editable fields that are invisible to people. If bots fill them out, then we know to reject the submission. We can create honeypots in multiple ways.

The most basic method is by using the `baitToken` method. This simply creates a randomized token as bait. However, it does not hide the field. I recommend using JavaScript or CSS to hide this input.

```html
<input type="text" name="{{ Defender::baitToken() }}">
<!-- Output: <input type="text" name="some_random_string"> -->
```

The `baitField` method creates a random type of input (text, email, password, radio, checkbox, etc.). A random styling is also applied that hides these inputs.

```
{{ Defender::baitField() }}
<!-- Output: <input type="random_type" name="some_random_string" style="random_styling_that_hides_this"> -->
```

Finally, we have the `baitFields` method. This not only creates a random input field, but it also creates a random number of input fields.

```
{{ Defender::baitFields() }}
<!-- Output x1 to x5: <input type="random_type" name="some_random_string" style="random_styling_that_hides_this"> -->
```

The `baitFields` method also accepts an integer argument. For example, `Defender::baitFields(20)` will output anywhere from 1 to 20 hidden baits.

### Validating & Rejecting

You use the same methods to validate the fields.

```
// Import the Defender facade
use Defender;

// Add to the rules
public function rules()
{
    return [
        Defender::username() => 'required'
    ];
}
```

As for rejecting the honeypots, the simplest way to do this is by adding the packages's `DefendAgainstSpam` middleware. To do this, open `app/Http/Kernel.php`, and add the middleware to either the `$middleware` or `$routeMiddleware` arrays. If you add it to the `$middleware` array, then this package will check the honeypots in every single request. This is the easiest way to handle it. However, if you would like to apply the middleware to specific routes, then you need to add it to your `$routeMiddleware` array. Both examples are demonstrated below.

```
// Add here if you want to automatically check all routes
protected $middleware = [
	...
	\Kim\Defender\Middleware\DefendAgainstSpam::class
];

// Add here if you want to manually check specific routes
protected $routeMiddleware = [
	'defend' => \Kim\Defender\Middleware\DefendAgainstSpam::class
];
```

If you added it to `$routeMiddleware`, you now need to manually check specific routes so change your routes file to reflect that.

```
Route::get('/example', [
	'as' => 'example'
	'middleware' => 'defend',
	'uses' => 'ExampleController@store'
]);
```

If the middleware catches a potential spammer, it will throw an `InvalidFormException`. You can catch this inside your `app/Exceptions/Handler.php` file and do whatever you want.

```
public function render($request, Exception $e)
{
    if ($e instanceof \Kim\Defender\Exceptions\InvalidFormException) {
        // This example just redirect them back home. However, you probably
        // also want to do other things like: log the time, ip, etc.
        return redirect('/');
    }

    return parent::render($request, $e);
}
```

Finally, for those who don't want to use the middleware, you can use the custom validation rule, 'reject'.

```
public function rules()
{
    $rules = [
        // Your rules..
    ];

    // We reject the bait here
    foreach (Defender::bait() as $bait) {
        $rules[$bait] = 'reject';
    }

    return $rules;
}
```

## License

This package is free software distributed under the terms of the MIT license.