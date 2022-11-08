<?php

namespace App\Helpers;
use Aws\S3\S3Client;

class Aws
{

    /**
     * 
     * @return object
     */
    public static function createS3Client():object
    {
        $s3Client = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_REGION'),
            'credentials' => [
            'key'    => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY')
            ]
        ]);

        return $s3Client;
    }

    /**
     * @return object
     */
    public static function uploadFile($filename, $file)
    {
        $s3Client = self::createS3Client();
                    
        $result = $s3Client->putObject([
             'Bucket' => env('AWS_BUCKET'),
             'Key'    => $filename,
             'Body'   => fopen($file, 'r'),
         ]);

        return $result;
    }

}
