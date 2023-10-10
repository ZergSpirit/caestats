<?php

namespace App\Controller;

use App\Entity\Guilde;
use App\Entity\Personnage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class PersonnageController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager, private SerializerInterface $serializer)
    {
        
    }

    #[Route('/personnages/{guilde}', name: 'app_personnage')]
    public function list(int $guilde): Response
    {
        $guildeEntity = $this->entityManager->getRepository(Guilde::class)->find($guilde);
        if($guildeEntity == null){
            throw new \Exception('Guild '.$guilde.' not found');
        }
        $jsonContent = $this->serializer->serialize($this->entityManager->getRepository(Personnage::class)->findByGuilde($guildeEntity), 'json');

        return $this->json($jsonContent);

    }
}
