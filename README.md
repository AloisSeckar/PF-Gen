# PF-Gen
 Profile picture Frame GENerator - PHP tool for adding frame around social media profile photos.
 Based on [L-Gen project](https://github.com/AloisSeckar/L-Gen)

## Features
Overlays `bg1.png` or `bg2.png` image around given input (JPG or PNG). The source image is being cropped to square and shrinked to 1000x1000 px if needed. The overlay (which should be partialy transparent) is placed over the source. Processed image is stored as `tmp/output.png` and displayed on the page. Output is not persistent, next run of the script overwrites it.

## Usage
Just deploy it on any PHP server and display `index.php`.

## Possible development
* persisting output images
* selecting logo source, location and dimensions (currently hardcoded)
* allowing other input formats (currently only JPG and PNG)
* translations