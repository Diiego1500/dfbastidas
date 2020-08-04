<?php

namespace App\Controller;

use App\Entity\Audio;
use App\Entity\Season;
use App\Entity\User;
use App\Form\AudioType;
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

    /**
     * @Route("/admin/podcast/season/{id}", name="admin_podcast_audios")
     */
    public function admin_podcast_audios(Request $request, SluggerInterface $slugger, Season $season){
        $em = $this->getDoctrine()->getManager();
        $audio = new Audio();
        $audios = $em->getRepository(Audio::class)->findBy(['season'=>$season]);
        $form_audio = $this->createForm(AudioType::class, $audio);
        $form_audio->handleRequest($request);
        if($form_audio->isSubmitted() && $form_audio->isValid()){
            $file = $form_audio->get('file')->getData();
            if($file){
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('audios_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Somethings wrong with your audio');
                }
                $audio->setFile($newFilename);
            }
            $audio->setSeason($season);
            $em->persist($audio);
            $em->flush();
            return $this->redirectToRoute('admin_podcast_audios', ['id'=>$season->getId()]);
        }
        return $this->render('admin/audio_podcast.html.twig',[
            'form_audio'=>$form_audio->createView(),
            'audios'=>$audios,
            'season'=>$season
        ]);
    }

    /**
     * @Route("/admin/change/free/audio/{id}", name="admin_change_free_audios")
     */
    public function change_free_audio(Audio $audio){
        $em = $this->getDoctrine()->getManager();
        $is_free = $audio->isFree();
        if($is_free){
            $audio->setIsFree(false);
        }else{
            $audio->setIsFree(true);
        }
        $em->flush();
        return $this->redirectToRoute('admin_podcast_audios',['id'=>$audio->getId()]);
    }
}
