<?php


namespace App\Controller;


use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    public function __construct(PropertyRepository $repository)
    {
       $this->repository = $repository;
       // $this->em = $em;
    }

    /**
     * @Route("/biens", name="property.index")
     * @return Response
     */
    public function index(): Response{
        // afficher les biens
        // $repository = $this->getDoctrine()->getRepository(Property::class);
        $property = $this->repository->findAllVisible();
        dump($property);

        return $this->render('/property/index.html.twig', [
            'current_menu'  => 'properties'
        ]);
    }
    /**
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     * @return Response
     */
    public function show(Property $property, string $slug): Response {
        //$property = $this->repository->find($id);
        if($property->getSlug() !== $slug) {
            return $this->redirectToRoute('property.show', [
                'id'     => $property->getId(),
                'slug'  => $property->getSlug()
            ], 301);
        }
        // show single property page
        return $this->render('property/show.html.twig', [
            'current_menu'  => 'properties',
            'property'      => $property
        ]);
    }
}