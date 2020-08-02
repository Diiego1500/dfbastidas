<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\User;
use App\Form\SeasonType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();
        return $this->render('admin/users.html.twig',['users'=>$users]);
    }

    /**
     * @Route("/admin/podcast", name="admin_podcast")
     */
    public function admin_podcast(Request $request, SluggerInterface $slugger){
        $em = $this->getDoctrine()->getManager();
        $season = new Season();
        $seasons = $em->getRepository(Season::class)->findAll();
        $form_season = $this->createForm(SeasonType::class, $season);
        $form_season->handleRequest($request);
        if($form_season->isSubmitted() && $form_season->isValid()){
            $image = $form_season->get('image_name')->getData();
            if($image){
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move(
                        $this->getParameter('seasons_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                   throw new \Exception('Somethings wrong with your image');
                }
                $season->setImageName($newFilename);
            }
            $em->persist($season);
            $em->flush();
            return $this->redirectToRoute('admin_podcast');
        }
        return $this->render('admin/podcast.html.twig',[
            'form_season'=>$form_season->createView(),
            'seasons'=>$seasons
        ]);
    }
}
