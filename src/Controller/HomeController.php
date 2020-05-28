<?php
namespace App\Controller;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * Home Controller
 */
class HomeController extends AbstractController
{
	
	private $twig;

	public function __construct(Environment $twig, PropertyRepository $repository) {
		$this->twig = $twig;
		$this->repository = $repository;
	}

    /**
     * @Route("/", name="home")
     * @param PropertyRepository $repository
     * @return Response
     */
	public function index(): Response {
	    // Méthode ancienne
		// return new Response($this->twig->render('pages/home.html.twig'));
        // afficher tous les biens
        $properties = $this->repository->findLatest();
	    return $this->render('pages/home.html.twig', [
	        'current_menu'  => 'homepage',
            'properties'    => $properties
        ]);
	}
}