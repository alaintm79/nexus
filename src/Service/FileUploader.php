<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /** @var string $path */
    private $path;

    public function __construct()
    {
        $this->path = __DIR__.'/../../public';
    }

    public function upload(UploadedFile $file, string $dir = '/uploads', string $name = 'default', bool $storeByDate = false)
    {
        if($storeByDate){
            $date = new \DateTime('now');
            $dir = $dir.'/'.$date->format('Y/m');
        }

        $originalFilename = $name === 'default' ? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) : $name;
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_-] remove; Lower()', $originalFilename);
        $fileName = $safeFilename.'.'.$file->guessExtension();

        if('default' === $name){
            $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        }

        try {
            $file->move($this->path.$dir, $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        if($storeByDate){
            return $dir.'/'.$fileName;
        }

        return $fileName;
    }

    public function delete($file){

        $fs = new Filesystem();

        if (is_file($this->getPath().$file)){
            $fs->remove($this->getPath().$file);
        }
    }

    public function getPath()
    {
        return $this->path;
    }

}
