<?php

namespace App\Service;

use JetBrains\PhpStorm\NoReturn;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImportAgent
{
    private UploadedFile $fileUpload;
    private string $uploadpath;
    private string $filename;
    private string $fileWithPath;

    private Xlsx $reader;
    private Filesystem $fileSystem;
    public function __construct($file, ContainerInterface $container, Xlsx $reader, Filesystem $filesystem){
        $this->fileUpload = $file;
        $this->uploadpath = $container->getParameter('uploadfile');
        $this->filename = md5(uniqid()) . '.' . $file->guessClientExtension();
        $this->fileWithPath =  $this->uploadpath .'/' . $this->filename;

        $this->reader = $reader;
        $this->fileSystem = $filesystem;
    }
    private function moveFile(): void
    {
        $this->fileUpload->move(
            $this->uploadpath,
            $this->filename
        );
    }

    /**
     * @throws Exception
     */
    public function load() : void
    {
        $this->moveFile();

        $sheet =$this->reader->load($this->fileWithPath)->getActiveSheet();

        $data[] = $sheet->toArray();

        $this->fileSystem->remove($this->fileWithPath);

        dd($data);
    }
}