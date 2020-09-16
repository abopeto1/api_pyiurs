<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Type;
use App\Entity\Segment;

final class TypeService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getProductType($postType, $postSegment, $i = 0)
    {
        $segment = $this->entityManager->getRepository(Segment::class)->findOneBy(array("name" => $postSegment));
        if (!$segment) {
            return new Response("Unknow Segment ". $postSegment ." on Line $i", Response::HTTP_BAD_REQUEST);
        }

        $type = $this->entityManager->getRepository(Type::class)->findOneBy(array(
            "name" => $postType, "segment" => $segment->getId()));
        if (!$type) {
            return new Response("Unknow Type ". $postType ."on Line $i", Response::HTTP_BAD_REQUEST);
        }

        return $type;
    }
}