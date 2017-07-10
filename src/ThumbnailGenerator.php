<?php

namespace PIRB\ThumbnailGenerator;

use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;
use Intervention\Image\ImageManagerStatic as Image;

class ThumbnailGenerator
{
    /**
     * [getThumbnailMozaic description]
     *
     * @param  string $videoPath
     * @param  string $mozaicStoragePath
     * @param  string $fileName
     * @param  integer $col
     * @param  integer $row
     * @param  integer $thumbWidth
     * @param  integer $thumbHeight
     * @param  boolean $forceSize (optional)
     * @param  integer $tts (optional)
     *
     * @return boolean - true if success, false otherwise
     */
    public function getThumbnailMozaic(
        $videoPath,
        $mozaicStoragePath,
        $fileName,
        $col,
        $row,
        $thumbWidth,
        $thumbHeight,
        $forceSize = false,
        $tts = 5
    ) {
        if (config('thumbnailgenerator.binaries.enabled')) {
            $ffmpeg = FFMpeg::create([
                'ffmpeg.binaries'  => config('thumbnailgenerator.binaries.path.ffmpeg'),
                'ffprobe.binaries' => config('thumbnailgenerator.binaries.path.ffprobe'),
                'timeout'          => config('thumbnailgenerator.binaries.path.timeout'),
                'ffmpeg.threads'   => config('thumbnailgenerator.binaries.path.threads'),
            ]);
        } else {
            $ffmpeg = FFMpeg::create();
        }

        $video = $ffmpeg->open($videoPath);
        if ($video == false) { // We can open the video
            return false;
        }

        $nbPictures = (int) $col * (int) $row;
        if ($nbPictures <= 0) { // At least 1 picture
            return false;
        }

        $videoDuration = $this->getVideoDuration($videoPath);
        $tts = (int) $tts;

        if ($videoDuration == 0) { // video is at least 1 Second
            return false;
        }

        if ($tts * $nbPictures > $videoDuration) { // Make sure to have valid tts for thumbs
            $tts = (int) ($videoDuration / $nbPictures);
        }

        $arr = [];
        for ($i = 1; $i <= $nbPictures; $i++) {
            $imagePath = $mozaicStoragePath . $nbPictures . '.jpg';
            $video
                ->frame(TimeCode::fromSeconds($tts * $i))
                ->save($imagePath);
            $arr[] = $this->resizeImage($imagePath, $mozaicStoragePath, $i, $thumbWidth, $thumbHeight, $forceSize);
        }
        return $this->generateMozaic($arr, $mozaicStoragePath, $fileName, $col, $row);
    }

    /**
     * Generate & Save the final mozaic
     *
     * @param  array  $arrayPictures
     * @param  string $mozaicStoragePath
     * @param  string $fileName
     * @param  integer $col
     * @param  integer $row
     *
     * @return boolean - true if success, false otherwise
     */
    public function generateMozaic(array $arrayPictures, $mozaicStoragePath, $fileName, $col, $row)
    {
        $cpt     = 0;
        $width   = $arrayPictures[0]['width'] * $col;
        $height  = $arrayPictures[0]['height'] * $row;
        $image_p = imagecreatetruecolor($width, $height);
        
        for ($i=0; $i< $row; $i++) {
            for ($j=0; $j< $col; $j++) {
                $image = imagecreatefromjpeg($arrayPictures[$cpt]['path']); // Existing file
                imagecopyresampled(
                    $image_p, // resource $dst_image
                    $image, // resource $src_image
                    0 + ($j*$arrayPictures[$cpt]['width']), // int $dst_x
                    0 + ($i*$arrayPictures[$cpt]['height']), // int $dst_y
                    0, // int $src_x
                    0, // int $src_y
                    $arrayPictures[$cpt]['width'],  // int $dst_w
                    $arrayPictures[$cpt]['height'], // int $dst_h
                    $arrayPictures[$cpt]['width'],  // int $src_w
                    $arrayPictures[$cpt]['height']  // int $src_h
                );
                $cpt++;
            }
        }
        return imagejpeg($image_p, $mozaicStoragePath . $fileName, 100);
    }

    /**
     * Get the duration of a video.
     *
     * @param  string $videoPath Video resource source path
     *
     * @return string Duration of the video
     */
    public function getVideoDuration($videoPath)
    {
        $ffprobe = FFProbe::create();
        return (int) $ffprobe
                ->format($videoPath) // extracts file informations
                ->get('duration');   // returns the duration property
    }

    /**
     * Resize current image.
     *
     * @param  string $imagePath
     * @param  string $mozaicStoragePath
     * @param  integer $i
     * @param  integer $width
     * @param  integer $height
     * @param  boolean $forceSize
     *
     * @return array
     */
    public function resizeImage($imagePath, $mozaicStoragePath, $i, $width, $height, $forceSize)
    {
        $img = Image::make($imagePath);
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio(); // Keep ratio
        });
        $img->save($mozaicStoragePath . 'thumbmozaic-' . $i . '.jpg', 95); // Instance of Intervention\Image\Image

        if ($forceSize) {
            $image_p = imagecreatetruecolor($width, $height);
            $image = imagecreatefromjpeg($mozaicStoragePath . 'thumbmozaic-' . $i . '.jpg');
            imagecopyresampled(
                $image_p, // resource $dst_image
                $image, // resource $src_image
                $img->width() == $width ? (int) (($height - $img->height()) / 2) : 0, // int $dst_x
                $img->width() == $width ? 0 : (int) (($height - $img->height()) / 2), // int $dst_y
                0, // int $src_x
                0, // int $src_y
                $img->width(),  // int $dst_w
                $img->height(), // int $dst_h
                $img->width(),  // int $src_w
                $img->height()  // int $src_h
            );
            imagejpeg($image_p, $mozaicStoragePath . 'thumbmozaic-' . $i . '.jpg', 100);
        }

        return [
            'path' => $mozaicStoragePath . 'thumbmozaic-' . $i . '.jpg',
            'width' => $forceSize ? $width : $img->width(),
            'height' => $forceSize ? $height : $img->height()
        ];
    }
}
