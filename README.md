# Minify
> Minify your site's CSS & JavaScript when they change

This addon minifies your site's CSS & JavaScript when they change. All you need to do is change the code that pulls in your assets. This addon also now lets you minify the HTML served to your users.

![Little example](https://raw.githubusercontent.com/doublethreedigital/minify/master/code-example.png)

## Installation
Please bear in mind, Minify requires PHP 7.4 or higher.

1. Install via Composer - `composer require doublethreedigital/minify`
2. If you haven't already, run the `php artisan storage:link` command to create the required symlinks. (You'll need to run this command in any environment with Minify installed or you will get 404s)
2. Read below documentation on how to implement this addon in your site.

## Usage

Minify only minifies the files, it doesn't handle your build process for you. We'd recommend using [Laravel Mix](https://laravel.com/docs/7.x/mix#introduction) for that.

### CSS
To pull in a stylesheet, use the `minify:css` tag. Remember to include the `src`, the value of which should be the relative path to your CSS file from your project's `public` directory.

```antlers
{{ minify:css src="css/site.css" }}
```

This above example would minify the existing `public/css/site.css` file, save it and serve it to the user.

### JavaScript
To pull in a script, use the `minify:js` tag. Like the CSS tag, remember to include the `src` parameter, the value of which should be the relative path to your JavaScript file from your project's `public` directory.

```antlers
{{ minify:js src="js/site.js" }}
```

> Minify has been known to have issues with some ES6 JavaScript stuff, please verify your site works before shipping to production.

### HTML

Minify can now automatically minify the HTML served to your users in Statamic front-end requests. To enable, just add the `HtmlMinification` middleware to the `web` middleware group in your `App\Http\Kernal` file.

```php
protected $middlewareGroups = [
    'web' => [
        ...

        \DoubleThreeDigital\Minify\Http\Middleware\HtmlMinification::class,
    ],

    ...
];
```

### Inline assets
If you don't want the minified version of your assets to be stored in a file, you can get the contents of the minification inline.

```html
<!-- It works for both... styles -->
<style>{{ minify:css src="css/site.css" inline="true" }}</style>

<!-- And scripts -->
<script>{{ minify:js src="js/site.js" inline="true" }}</script>
```

### Minify inline scripts
Sometimes you want to just add scripts to your HTML without using seperate files. The good news is that this addon can minify those as well! Here's a JavaScript example

```html
{{ minify:js }}
    <script>
        alert('Yo! How you doin');
    </script>
{{ /minify:js }}
```

And of course it will work the same way if you use the `{{ minfify:css }}` tag.


### Caching
This addon makes use of caching so that it doesn't have to re-minify your assets during each page load. Sometimes this can cause issues so we've built a command that clears everything for you.

```
php please minify:clear
```

You may also wish to run `php artisan cache:clear` at the same time.

### Troubleshooting
To do the minification of JavaScript and CSS, we use [`matthiasmullie/minify`](https://github.com/matthiasmullie/minify). It's sometimes a bit problematic when dealing with newer JavaScript standards. For example, you might find that minification doesn't work as expected for lines without a semicolon `;` at the end.

For the moment, it's a bit of a limitation. However, we're planning on moving to another package in the future.

## Resources

* [Official Support](https://doublethree.digital)
* [Unofficial Support (#3rd-party)](https://statamic.com/discord)

<p>
<a href="https://statamic.com"><img src="https://img.shields.io/badge/Statamic-3.0+-FF269E?style=for-the-badge" alt="Compatible with Statamic v3"></a>
<a href="https://packagist.org/packages/doublethreedigital/minify/stats"><img src="https://img.shields.io/packagist/v/doublethreedigital/minify?style=for-the-badge" alt="Minify on Packagist"></a>
</p>
