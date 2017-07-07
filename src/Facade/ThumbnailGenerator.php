<?php

namespace PIRB\ThumbnailGenerator\Facade;

use Illuminate\Support\Facades\Facade;

class ThumbnailGenerator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'thumbnailgenerator';
    }
}
