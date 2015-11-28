<?php

function guggenheim($filesArr, $options) {

    $display_width = isset($options['width']) ? $options['width'] : 800;
    $ideal_height = isset($options['height']) ? $options['height'] : floor($display_width/3.5);
    $border = isset($options['border']) ? $options['border'] : 4;
    $total_width = 0;
    $ratio_list = [];
    $picture_by_ratio = [];

    foreach ($filesArr as $key => $file) {
        $ratio = $file->ratio() * 10000;
        $ratio_list[] = $ratio;
        if(!isset($picture_by_ratio[$ratio])) {
                $picture_by_ratio[$ratio] = [];
        }
        $picture_by_ratio[$ratio][] = $file;
        $total_width += $ideal_height * ($ratio / 10000);
        // $total_width += $ideal_height * round($ratio / 10000);
        // $total_width += $ideal_height * floor($ratio / 10000);
    }

    $row = ceil($total_width/$display_width);
    $distribution = linear_partition($ratio_list, $row);

    $elementClass = 'guggenheim';

    $gallery = brick('div');
    $gallery->attr('itemscope');
    $gallery->attr('itemtype', 'http://schema.org/ImageGallery');
    $gallery->attr('class', $elementClass . r(c::get('guggenheim.classes'), ' '.c::get('guggenheim.classes')));
    $gallery->attr('style', 'max-width:' . $display_width . 'px');

    foreach($distribution as $row) {
        $total_ratio = array_sum($row) / 10000;
        // $total_ratio = ceil(array_sum($row) / 10000); // Provides an interesting effect
        $height = round($display_width / $total_ratio);
        // ensure the width sums to $display_width aka largest remainder method
        $width = [];
        $remainder = [];
        $rowCount = count($row);

        $rowElem = brick('div');
        $rowElem->attr('class', $elementClass . '__row');
        $rowElem->attr('style', 'padding-left:'. ($rowCount * $border) . 'px');

        foreach($row as $ratio) {
            $dec = $height * ($ratio / 10000);
            $int = floor($dec);
            $width[] = $int;
            $remainder[] = $dec - $int;
        }

        while(array_sum($width) < $display_width) {
            $k = array_search(max($remainder), $remainder);
            $width[$k]++;
            $remainder[$k] = 0;
        }

        foreach($row as $k => $ratio) {
            $file = array_shift($picture_by_ratio[$ratio]);
            $url = $file->url();
            $alt = '';

            // try to get the title from the image object and use it as alt text
            if($file) {
                if(empty($alt) and $file->alt() != '') {
                    $alt = $file->alt();
                }
            }

            if(empty($alt)) $alt = pathinfo($url, PATHINFO_FILENAME);

            $thumbnail = $file->crop($width[$k], $height);

            $twoXwidth = $width[$k] * 2;

            // $figurePercentageWidth = ($width[$k] / $display_width)*100;
            $figurePercentageWidth = ($width[$k] / $display_width)*99.5;

            $figure = brick('figure');
            $figure->attr('itemprop', 'associatedMedia');
            $figure->attr('itemscope');
            $figure->attr('itemtype', 'http://schema.org/ImageObject');
            $figure->attr('class', $elementClass . '__figure');
            if($k == 0){
                $figure->attr('style', 'width:' . $figurePercentageWidth . '%;margin-left:-' . ($rowCount * $border) . 'px;padding:' . floor($border/2) . 'px');
            } else {
                $figure->attr('style', 'width:' . $figurePercentageWidth . '%;padding:' . floor($border/2) . 'px');
            }

            $link = brick('a');
            $link->attr('itemprop', 'contentUrl');

            if (c::get('guggenheim.photoswipe', true)) {
                $fitTo = $file->isLandscape() ? 'width' : 'height';
                $zoomed = thumb($file, array($fitTo => '1200'));
                if (method_exists('CacheBust','bust')) {
                    $link->attr('href', CacheBust::bust($zoomed->url()) );
                } else {
                    $link->attr('href', $zoomed->url());
                }
                $link->attr('data-size', $zoomed->width() . 'x' . $zoomed->height() );
                $link->attr('data-share-src', $file->url() );
            } else {
                $link->attr('href', $file->url());
            }

            $link->attr('title', $alt);
            $link->attr('class', $elementClass . '__link');

            $image = brick('img');
            $image->attr('itemprop', 'thumbnail');
            $image->attr('alt', $alt);
            $image->attr('src', $thumbnail->url());
            $image->attr('width', $thumbnail->width());
            $image->attr('height', $thumbnail->height());

            if (c::get('guggenheim.srcset', true)) {
                $image->attr('srcset',
                    $thumbnail->url() . ' ' . $width[$k] .'w,' .
                    $file->crop($twoXwidth, ($twoXwidth / $thumbnail->ratio()))->url() . ' ' . $twoXwidth . 'w'
                );
                $image->attr('sizes',
                    '(min-width:64.063em) ' . $width[$k] . 'px,' .
                    '(min-width:40.063em) calc(' . $figurePercentageWidth / 100 . ' * (100vw - 6em)),' .
                    'calc(100vw - 2.5em)'
                );
            }

            $image->attr('class', $elementClass . '__image');

            $aspectContainer = brick('div');
            $aspectContainer->attr('class', $elementClass . '__ratio-container');

            $aspectRatioFill = brick('div');
            $aspectRatioFill->attr('style', 'padding-bottom:' . $thumbnail->height() / $thumbnail->width() * 100 . '%');

            $link->append($image);
            $aspectContainer->append($aspectRatioFill);
            $aspectContainer->append($link);
            $figure->append($aspectContainer);
            $rowElem->append($figure);

        }

        $gallery->append($rowElem);
    }

    return $gallery;
}

/**
 * PHP implementation of the linear partition algorithm.
 *
 * @see http://stackoverflow.com/a/21259094
 *
 * @param $seq array List of values to distribute.
 * @param $k int Number of rows.
 */
function linear_partition($seq, $k) {
        if ($k <= 0) {
                return [];
        }

        $n = count($seq);

        if ($k > $n-1) {
                foreach ($seq as &$x) {
                        $x=[$x];
                }
                return $seq;
        }

        $table = array_fill(0, $n, array_fill(0, $k, 0));
        $solution = array_fill(0, $n-1, array_fill(0, $k-1, 0));

        for ($i = 0; $i < $n; $i++) {
                $table[$i][0] = $seq[$i] + ($i ? $table[$i-1][0] : 0);
        }

        for ($j = 0; $j < $k; $j++) {
                $table[0][$j] = $seq[0];
        }

        for ($i = 1; $i < $n; $i++) {
                for ($j = 1; $j < $k; $j++) {
                        $current_min = null;
                        $minx = PHP_INT_MAX;

                        for ($x = 0; $x < $i; $x++) {
                                $cost = max($table[$x][$j - 1], $table[$i][0] - $table[$x][0]);
                                if ($current_min === null || $cost < $current_min) {
                                        $current_min = $cost;
                                        $minx = $x;
                                }
                        }

                        $table[$i][$j] = $current_min;
                        $solution[$i-1][$j-1] = $minx;
                }
        }

        $k--;
        $n--;
        $ans = [];

        while ($k > 0) {
                array_unshift($ans, array_slice($seq,
                    $solution[$n-1][$k-1] + 1, $n - $solution[$n-1][$k-1]));
                $n = $solution[$n-1][$k-1];
                $k--;
        }

        array_unshift($ans, array_slice($seq, 0, $n+1));
        return $ans;
}

// gallery tag
kirbytext::$tags[c::get('guggenheim.kirbytext.tagname','gallery')] = array(
        'attr' => array(
                'width',
                'height',
                'border',
        ),
        'html' => function($tag) {

                $urls     = $tag->attr('gallery');
                $urlsArr  = str_replace(' ', '', preg_split( "/(\||\,)/", $urls)); // filenames can be comma or pipe seprated ..heck even both
                $filesArr = [];

                // tomt eller all ?
                if ($urlsArr[0] == 'all' or empty($urlsArr[0])) {
                    $images = page()->images()->sortBy('sort');
                    foreach ($images as $image) {
                        $urlsArr[] = $image->filename();
                    }
                }

                foreach ($urlsArr as $key => $url) {
                        $file = $tag->file($url);
                        if(!f::exists($file)) continue;
                        $filesArr[] = $file;
                }

                return guggenheim($filesArr, array(
                        'width'   => $tag->attr('width'),
                        'height'  => $tag->attr('height'),
                        'border'  => $tag->attr('border'),
                ));

        }
);