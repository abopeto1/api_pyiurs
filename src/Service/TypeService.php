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
        $segment = $this->entityManager->getRepository(Segment::class)->findOneByName($postSegment);
        if (empty($segment)) {
            return new Response("Unknow Segment on Line $i", Response::HTTP_BAD_REQUEST);
        }

        $type = $this->entityManager->getRepository(Type::class)->findByNameAndSegment($postType, $segment->getId());
        if (empty($type)) {
            return new Response("Unknow Type on Line $i", Response::HTTP_BAD_REQUEST);
        }

        return $type;
    }
}