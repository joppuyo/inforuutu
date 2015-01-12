# Inforuutu

## Features

Inforuutu is a in-event information screen optimized to be run on a Raspberry Pi. It has following features:

* HTML formatted slides
* Twitter support
* Instagram support
* Emoji Support for Twitter

## Requirements

* Apache
* MySQL
* PHP 5.4 or greater
* ImageMagick
* cURL
* Composer

## Installation

1. Create new MySQL database named `inforuutu`
2. Import `inforuutu.sql` into the database
3. Copy the application code into your Apache `www` folder
3. Install dependencies by running `php composer.phar install` in the application folder
4. Grab your own [Instagram API key](http://instagram.com/developer/) and [Twitter API key](https://apps.twitter.com/)
5. Add API keys in `config.php` and change the settings to suit your needs

## Notes

Inforuutu only runs in landscape 720p mode and works best in Firefox (Iceweasel in Debian). This ensures best possible performance. Inforuutu is meant to be run during an event so there is currently no mechanism to purge old images. Be careful so you don't run out to disk space!

## License

Copyright 2015 Johannes Siipola & Henna Kiiveri. Licensed under the GNU GPL 3.0 license. Twitter Emoji by Twitter is licensed under CC-BY 4.0. Other libraries are licensed under their respective licenses.