<?php

namespace App\Utils;

use App\Utils\AppMessages;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\DBAL\DBALException;

class DatabaseHandler {

    private $entityManager;
    private $appMessages;

    function __construct(EntityManagerInterface $entityManager, AppMessages $appMessages) {
        $this->entityManager = $entityManager;
        $this->appMessages = $appMessages;
    }

    private function pushDataToDatabase($objectToBePushed, string $successMessage) {
        try {
            if (is_array($objectToBePushed)) {
                foreach ($objectToBePushed as $object) {
                    $this->entityManager->persist($objectToBePushed);
                }
            } else {
                $this->entityManager->persist($objectToBePushed);
            }

            $this->entityManager->flush();

            return $this->appMessages->displaySuccessMessage($successMessage);
        } catch (Exception $ex) {
            return $this->appMessages->displayErrorMessage('Error occured while executing database operation! Contact with administrator!');
        }
    }

    public function insertNewRecord(object $recordEntity) {
        $successMessage = 'New record added!';

        $recordEntity->setCreated(new \DateTime('now'));

        return $this->pushDataToDatabase($recordEntity, $successMessage);
    }

    public function modifyRecord(object $recordEntity) {
        $successMessage = 'Record modified!';

        $recordEntity->setModified(new \DateTime('now'));

        return $this->pushDataToDatabase($recordEntity, $successMessage);
    }

    public function deleteRecord($recordEntity) {
        $successMessage = 'Record deleted!';

        if (empty($recordEntity)) {
            return $this->appMessages->displayWarningMessage('Invalid URL parameter!');
        }

        if (is_array($recordEntity)) {
            $successMessage = 'Records deleted!';

            foreach ($recordEntity as $object) {
                $object->setDeleted(new \DateTime('now'));
            }
        } else {
            $recordEntity->setDeleted(new \DateTime('now'));
        }

        return $this->pushDataToDatabase($recordEntity, $successMessage);
    }

}
