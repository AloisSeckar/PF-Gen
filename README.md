# PF-Gen
 Profile picture Frame GENerator - PHP tool for adding frame around social media profile photos.

 Based on [L-Gen project stub](https://github.com/AloisSeckar/L-Gen)

## Features
Overlays selected PNG image over given input (JPG or PNG). The source image is being cropped to square and shrinked to 1000x1000px if needed. The overlay (which should be partialy transparent) is placed above the source. Processed image is stored as `tmp/output.png` and displayed on the page. Output is not persistent, next run of the script overwrites it.

It is possible to select EN (default) or CS messages using `?lang=cs` or `?lang=en` URL option.

## Usage
Just deploy all the files on any PHP server (needs `PHP >= 5.5.0` because of `imagecrop` a `imagescale`) and display `index.php`.

## Customize
Replace `bg0.png bg1.png bg2.png bg3.png` with any other frames you'd like to offer to your audience.

## Possible development
* persisting output images
* selecting source, location and dimensions (currently hardcoded)
* allowing other input formats (currently only JPG and PNG)

## Last Revision
2022-11-16