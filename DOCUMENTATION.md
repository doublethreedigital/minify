## Installation

1. Install from Composer `composer require doublethreedigital/minify`
2. Start using tags in your views.

## Usage

### CSS

In Statamic, you can pull in a theme's default stylesheet like this:

```antlers
{{ theme:css }}
```

Whereas, if you want to minify it, you switch out `theme` for `minify`, like so:

```antlers
{{ minify:css }}
```

If you want to minify a stylesheet that is not the default one, you can use it like this:

```antlers
{{ minify:css src="anotherone" }}
```

Make sure that you don't include `.css` at the end of your filename or it won't work.

### JavaScript

In Statamic, you can pull in a theme's default script like this:

```antlers
{{ theme:js }}
```

Whereas, if you want to minify it, you switch out `theme` for `minify`, like so:

```antlers
{{ minify:js }}
```

If you want to minify a stylesheet that is not the default one, you can use it like this:

```antlers
{{ minify:js src="anotherone" }}
```

Make sure that you don't include `.js` at the end of your filename or it won't work.

## How it works

* You include a tag inside your template
* The addon then minifies the asset
* The minified version gets saved
* The addon returns the URL of the saved & minified asset
