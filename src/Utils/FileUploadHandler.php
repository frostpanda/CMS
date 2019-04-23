<?php

namespace App\Utils;

class FileUploadHandler {

    private $targetDirectory;
    private $uploadingFile;

    public function setTargetDirectory(string $targetDirectory) {
        $this->targetDirectory = $targetDirectory;

        return $this;
    }

    public function setFile(object $file) {
        $this->uploadingFile = $file;

        return $this;
    }

    private function generateUniqueFileName() {
        return md5(uniqid()) . '.' . $this->uploadingFile->guessExtension();
    }

    private function checkIfFileNameExist(string $fileName) {
        clearstatcache();
        $fileToCheck = $this->targetDirectory . $fileName;

        return file_exists($fileToCheck);
    }

    private function generateFileName() {
        do {
            $fileName = $this->generateUniqueFileName();
        } while ($this->checkIfFileNameExist($fileName) === true);

        return $fileName;
    }

    private function moveFileToDirectory(string $fileName) {
        try {
            $this->uploadingFile->move($this->targetDirectory, $fileName);
        } catch (FileException $ex) {
            
        }

        return $fileName;
    }

    public function uploadFile() {
        $fileName = $this->generateFileName();

        return $this->moveFileToDirectory($fileName);
    }

}
