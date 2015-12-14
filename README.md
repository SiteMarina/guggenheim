# Guggenheim
An algorithm for a perfectly balanced Gallery for Kirby CMS

## Let's kick things off with a demo 😍
**See it live:**

https://sitemarina.github.io/guggenheim/

**Screenshot:**

[![Screenshot of Guggenheim](https://raw.githubusercontent.com/SiteMarina/guggenheim/master/guggenheim/screenshot.jpg)](https://sitemarina.github.io/guggenheim/)

## Idea
Guggenheim is a plugin for [Kirby CMS](http://getkirby.com) which generates beautiful galleries through an algorithm for a perfectly balanced layout, which you might know from [the awesome image grids at medium.com](https://medium.com/the-story/introducing-image-grids-c592e5bc16d8)

Read more into the math and science behind the algorithm here: [The algorithm for a perfectly balanced photo gallery](https://www.crispymtn.com/stories/the-algorithm-for-a-perfectly-balanced-photo-gallery)

In my search for the perfect gallery, which could do this linear partition against the images,
I really wasen't getting excited about all the countless client-side javascript solutions to the problem.
As I care deeply about mobile, i simply didn't wanted to make the devices responsible for this task and wanted a PHP solution for the linear partition.

## Single Domain License
Buy a Single Domain License here https://sitemarina.github.io/guggenheim/ - **I am currently struggling pretty bad 😥**

**Pre-launch Option**
- I'll send you the plugin as a regular ol' .zip file to the email provided at purchase.
- If you for some reason don't like it, send me an email me for an refund.
- I'm working on an easier way to ship updates through Git - I'll send you an email when it's ready.

## Setup
Grab yourself a copy of [Kirby - a awesome NoDB file-based CMS](http://getkirby.com)

- Copy the guggenheim folder into site/plugins

As Guggenheim now ships with PhotoSwipe, there’s two ways to get up and running with Guggenheim and PhotoSwipe.

If you’re using the [Kirby Cachebuster Plugin](https://github.com/getkirby/plugins/tree/master/cachebuster) or your sever policy doesn’t allow loading assets from the plugin folder, you might want to fallback using the next and more preferred setup method and link up the sources in your own main assets/ folder.

**The easy ‘out of the box’ way:**
If you’re in a hurry, prototyping or just quickly want to test out Guggenheim with PhotoSwipe, the easiest way is to just put this in your head
```php
<?= css('plugins/guggenheim/assets/css/guggenheim-❤-photoswipe.min.css') ?>
```
and this in your footer
```php
<?= photoswipe() ?>
<?= js('plugins/guggenheim/assets/js/guggenheim-❤-photoswipe.min.js', true) ?>
```
off you go, you’re now all setup to use Guggenheim with PhotoSwipe.

**The best way:**
To preserve your total hackable and artistic freedom, the preferred and best way is still that you manually implement the stuff from guggenheim/src into your specific design or fronted framework in your assets/ folder. And maybe only outputs the PhotoSwipe DOM in your footer, like so
```php
<?= photoswipe() ?>
```

**Dependency order**

[PhotoSwipe](http://photoswipe.com/)
- PhotoSwipe DOM found in guggenheim/snippets/photoswipe-dom.php and [documented here](http://photoswipe.com/documentation/getting-started.html#init-add-pswp-to-dom)
- `photoswipe.css`
- `default-skin.css` (and its image dependencies)
- `photoswipe.min.js`
- `photoswipe-ui-default.min.js`

Guggenheim
- `guggenheim.css`
- `guggenheim-❤-photoswipe.min.js` (you can leave PhotoSwipe and this binding out entirely, if you just want to use Guggenheim on its own)

## Settings
in your site/config/config.php

```php
// Add aditional classes to the guggenheim gallery element
c::set('guggenheim.classes', 'gallery zoom margin-center');

// Guggenheim is meant to be used with PhotoSwipe
// But if you for some reason don't want to use it, you can remove it additionals with
c::set('guggenheim.photoswipe', false);

// Guggenheim uses some basic srcset and sizes for basic responsiveness and highres support
// if you want to disable it, and make your own
c::set('guggenheim.srcset', false);

// Guggenheim adds a kirbytext tag, the default is 'gallery', but you can change it with
c::set('guggenheim.kirbytext.tagname', 'guggenheim');
```

## Usage


### With kirbytext

**Super simplistic -Generate a Guggenheim gallery with all the page images**
```markdown
(gallery:) or (gallery: all)
```

**More generally, you want to pick out the pictures to make the gallery from, you do it like this**
```markdown
Comma separated:
(gallery: image-1.jpg, image-2.jpg, image-3.jpg)

Pipe separated:
(gallery: image-1.jpg | image-2.jpg | image-3.jpg)

..heck even both:
(gallery: image-1.jpg | image-2.jpg, image-3.jpg)
```

**Options**

Guggenheim lets you set some options for each gallery.

- **width** (Default is 800px) Which is the width of the gallery, all image will be resized through a linear partition to fit this width, and it will also be the max-width of the gallery (to prevent upscaling).
- **height** (Default is floor(width/3.5)) This is a ideal hight for images to fit too, it'll only be used as a guideline for the linear partition to maximum fit into, it's there for you to play around with for each specifik gallery
- **border** (Default is 4px) Sets the space between images in pixels
- **max-width** (Default is width) With this you can overwrite the max-width of the gallary a regular number is in pixels, but you can also do `max-width: 100%` - but do note that the images will still be resized according to the width option, so here you have the power to really screw things up for bigger screensizes if you aren't aware and doesn't overwrites the default srcset and sizes guggenheim ships with
- **class** With this you can add additional classes on a per gallery level

```markdown
(gallery: image-1.jpg, image-2.jpg, image-3.jpg width: 800 height: 350 border: 10 class: mycustomclass)
```

### In your templates
Use the Guggenheim gallery in your templates

```php
$images = $page->images()->sortBy('sort', 'asc');
echo guggenheim($images, array('width' => 800, 'height' => 350, 'border' => 10));
```

## Features
- Perfectly Balanced Gallery generated by a linear partition algorithm server-side, to preventing the usual overhead that JavaScript brings in terms of browser paint, reflows, grid calculations and DOM manipulations.
- If a 'caption' field is present for the image, it'll be added as a visually hidden figcaption, visible when opened in PhotoSwipe.


## Changelog

### Guggenheim 1.0.4
- [x] Fixes an important issue when PHP locale was set to a locale which uses comma as a decimal separator - floats that was outputted in the inline styles got screwed, as decimal separators in CSS has to be periods.
- [x] Ships with PhotoSwipe 4.0.1 and especially the PhotoSwipe DOM which you now easily can either manually copy from the guggenheim/snippets/photoswipe-dom.php or simply include it in your footer with `<?= photoswipe() ?>`

### Guggenheim 1.0.3
- [x] Only output figcaptions if a ‘caption' field is present and filled for the image
- [x] Add a option for setting the max-width of the gallery like (gallery: all width: 1200 max-width: 100%) 
- [x] Add the option for adding per gallery classes like (gallery: all class: customclass anotherclass)
