# thumbnail-mosaic

This package has been build for been built and tested to work with Laravel 5+

[![License](https://poser.pugx.org/pir-b/thumbnail-mosaic/license)](https://packagist.org/packages/pir-b/thumbnail-mosaic)
[![Build Status](https://travis-ci.org/PiR-B/thumbnail-mosaic.svg?branch=master)](https://travis-ci.org/PiR-B/thumbnail-mosaic)
[![Latest Stable Version](https://poser.pugx.org/pir-b/thumbnail-mosaic/v/stable)](https://packagist.org/packages/pir-b/thumbnail-mosaic)
[![Total Downloads](https://poser.pugx.org/pir-b/thumbnail-mosaic/downloads)](https://packagist.org/packages/pir-b/thumbnail-mosaic)
[![Latest Unstable Version](https://poser.pugx.org/pir-b/thumbnail-mosaic/v/unstable)](https://packagist.org/packages/pir-b/thumbnail-mosaic)

---

## Installation

With Composer :

```bash
composer require pir-b/thumbnail-mosaic
```

- Add the Service Provider to **providers** array
```php
PIRB\ThumbnailGenerator\ThumbnailGeneratorProvider::class,
```
- Add the Facade to **aliases** array
```php
'ThumbnailGenerator' => PIRB\ThumbnailGenerator\Facade\ThumbnailGenerator::class,
```

---

## Configurations

- Publish the configuration file , this will publish thumbnailgenerator.php file to your application **config** directory.
```bash
    php artisan vendor:publish
```

- Configure the required FFMpeg configurations.
 - FFMpeg will autodetect ffmpeg and ffprobe binaries. If you want to give binary paths explicitly, you can configure them in **.env** file.

