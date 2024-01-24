<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploaderService
{
    private $kernelUploadDir;
    private SluggerInterface $slugger;

    public function __construct($kernelUploadDir, SluggerInterface $slugger)
    {
        $this->kernelUploadDir = $kernelUploadDir;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $filename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->kernelUploadDir, $filename);
        } catch (FileException $e) {
            return null;
        }

        return $filename;
    }
}