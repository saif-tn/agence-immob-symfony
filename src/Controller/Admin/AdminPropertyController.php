<?php
namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
// ObjectManager is not longer supported i used EntityManagerInterface
use Doctrine\ORM\EntityManagerInterface;

class AdminPropertyController extends AbstractController {

    private $repository;
    private $em; // object manager
    public function __construct(PropertyRepository $repository, EntityManagerInterface $em) {
        $this->repository = $repository;
        $this->em = $em;
    }
    /**
     * @Route("/admin", name="admin.property.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    // get all properties
    public function index() {
        $properties = $this->repository->findAll();
        return $this->render('admin/property/index.html.twig',[
                'current_menu'  => 'admin',
                'properties'    => $properties
            ]
        );
    }

    /**
     * @param $id
     * @Route("/admin/edit/{id}", name="admin.property.edit", methods="GET|POST")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Property $property, Request $request) {

        // utiliser form builder
        $form = $this->createForm(PropertyType::class, $property);
        // gérer la requette
        $form->handleRequest($request);
        // vérifer l'envoi de formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec succéss');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/edit.html.twig', [
            'current_menu'  => 'admin',
            'property'      => $property,
            'form'          => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/property/add", name="admin.property.new")
     * fonction pour ajouter property avec un formulaire vide
     */
    public function new(Request $request) {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($property);
            $this->em->flush();
            $this->addFlash('success', 'Bien ajouté avec succéss');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/new.html.twig', [
                'current_menu'  => 'admin',
                'property'      => $property,
                'form'          => $form->createView()
            ]
        );
    }

    /**
     * @param Property $property
     * @Route("admin/property/{id}", name="admin.property.delete", methods="DELETE")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Property $property, Request $request) {
        if($this->isCsrfTokenValid('delete'.$property->getId(),
            $request->get('_token')) ) {
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec succéss');
        }

        return $this->redirectToRoute("admin.property.index");
    }

}