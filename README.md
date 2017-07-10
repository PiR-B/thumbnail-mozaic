# thumbnail-mozaic

This package has been build for been built and tested to work with Laravel 5+

[![License](https://poser.pugx.org/pir-b/thumbnail-mozaic/license)](https://packagist.org/packages/pir-b/thumbnail-mozaic)
[![Build Status](https://travis-ci.org/PiR-B/thumbnail-mozaic.svg?branch=master)](https://travis-ci.org/PiR-B/thumbnail-mozaic)
[![Latest Stable Version](https://poser.pugx.org/pir-b/thumbnail-mozaic/v/stable)](https://packagist.org/packages/pir-b/thumbnail-mozaic)
[![Total Downloads](https://poser.pugx.org/pir-b/thumbnail-mozaic/downloads)](https://packagist.org/packages/pir-b/thumbnail-mozaic)
[![Latest Unstable Version](https://poser.pugx.org/pir-b/thumbnail-mozaic/v/unstable)](https://packagist.org/packages/pir-b/thumbnail-mozaic)

---

## Installation

With Composer :

```bash
composer require pir-b/thumbnail-mozaic
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
