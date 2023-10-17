<?php

namespace App\Controller;

use App\Entity\Guilde;
use App\Entity\Personnage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class PersonnageController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager, private SerializerInterface $serializer)
    {
        
    }

    #[Route('/personnages', name: 'app_personnage')]
    public function find(#[MapQueryParameter] ?int $guildeId  = null, #[MapQueryParameter] ?string $term = null): Response
    {   
        $guildeEntity =  null;
        if ($guildeId != null) {
            $guildeEntity = $this->entityManager->getRepository(Guilde::class)->find($guildeId);
            if ($guildeEntity == null) {
                throw new \Exception('Guild '.$guilde.' not found');
            }
        }

        $jsonContent = $this->serializer->serialize($this->entityManager->getRepository(Personnage::class)->findByGuildAndTerm($guildeEntity, $term), 'json');

        return $this->json($jsonContent);

    }

}
