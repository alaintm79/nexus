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

    public function upload(UploadedFile $file, string $dir = '/uploads', bool $storeByDate = false, string $name)
    {
        if($storeByDate){
            $date = new \DateTime('now');
            $dir = $dir.'/'.$date->format('Y/m');
        }

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }


        // if('uniqid' === $name){
        //     $file = uniqid($prefix) . '.' . $uploadedFile->guessExtension();
        //     $uploadedFile->move($this->path.$dir, $file);
        // } elseif('original' === $name) {
        //     $file = $uploadedFile->getClientOriginalName();
        //     $uploadedFile->move($this->getPath().$dir, $uploadedFile->getClientOriginalName());
        // } elseif($name){
        //     $file = $name . '.' . $uploadedFile->guessExtension();
        //     $uploadedFile->move($this->path.$dir, $file);
        // }

        if($storeByDate){
            return $dir.'/'.$file;
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
