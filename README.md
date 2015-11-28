# Guggenheim - Gallery for Kirby CMS

## Let's kick things off with a demo \o/
**See it live:**

https://sitemarina.github.io/guggenheim/

**Screenshot:**

![Screenshot of Guggenheim](https://raw.githubusercontent.com/SiteMarina/guggenheim/master/guggenheim/screenshot.jpg)

## Idea behind it
Guggenheim is a plugin for Kirby which generates beautiful galleries through an algorithm for a perfectly balanced layout, which you might know from medium's image grids.

Read more into the math behind it here: [The algorithm for a perfectly balanced photo gallery](https://www.crispymtn.com/stories/the-algorithm-for-a-perfectly-balanced-photo-gallery)

In my search for the perfect gallery, which could do this linear partition against the images,
I really wasen't getting excited about the countless client-side javascript solutions to the problem.
As I care deeply about mobile, i simply didn't wanted to make the devices responsible for this task and wanted a PHP solution for the linear partition.

## Moral License
Please please (please), feel free to test out the gallery - if you like it, please consider buying a pr. domain Moral License / Donation - I am currently struggling pretty bad :'‑(

The code doesn't phone home, it doens't check for any license, I'm never going to DDoS your ass for anything.

If you don't have any money, I'm not expecting anything from you, and you can use it for free to get back on your feet.
But I might have to take it down if this doesn't work out, on the other hand I will love to make it completely free as soon as possible.

## Setup
1. Copy the guggenheim folder into site/plugins
2. Spice it up with PhotoSwipe, [from this gist!](https://gist.github.com/JimmyRittenborg/e44fde3c6caf2430610e)

## Settings
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

## How to use it


### With kirbytext

**Super simplistic -Generate a Guggenheim gallery with all the page images**
```markdown
(gallery:) and (gallery: all)
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

Guggenheim let you set some options for each gallery.

- **width** Which is the width of the gallery, all image will be resized through a linear partition to fit this width, and it will also be the max-width of the gallery (to prevent upscaling).

- **height** This is a ideal hight for images to fit too, it'll only be used as a guideline for the linear partition to maximum fit into, it's there for you to play around with for each specifik gallery

- **border** Sets the space between images in pixels

```markdown
(gallery: image-1.jpg, image-2.jpg, image-3.jpg width: 800 height: 350 border: 10)
```

### In your templates
Use the Guggenheim gallery in your templates

```php
$images = $page->images()->sortBy('sort', 'asc');
echo guggenheim($images, array('width' => 800, 'height' => 350, 'border' => 10));
```

## Legal License

The MIT License

Copyright (c) 2010-2015 SiteMarina. https://sitemarina.com

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.