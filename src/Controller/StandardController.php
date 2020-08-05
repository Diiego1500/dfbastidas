<?php

namespace App\Controller;

use App\Entity\Audio;
use App\Entity\Comments;
use App\Entity\Post;
use App\Entity\PostComment;
use App\Entity\Purchasedservices;
use App\Entity\Season;
use App\Entity\User;
use App\Form\CommentsType;
use App\Form\PostCommentType;
use App\Form\PostType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class StandardController extends AbstractController
{
    /**
     * @Route("/register/user", options={"expose"=true}, name="register_user")
     */
    public function register_user(Request $request, UserPasswordEncoderInterface $passwordEncoder){
        if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $user_name = $request->request->get('user_name');
            $user_last_name = $request->request->get('user_last_name');
            $user_birthdate = $request->request->get('user_birthdate');
            $user = $em->getRepository(User::class)->findOneBy(['email'=>$email]);
            if($user == null){
                $user = new User($email, User::ROLE_USER, $user_name, $user_last_name, new \DateTime($user_birthdate));
                $user->setPassword($passwordEncoder->encodePassword($user,$password));
                $purchased_service = new Purchasedservices(false,null,$user);
                $em->persist($user);
                $em->persist($purchased_service);
                $em->flush();
                return new JsonResponse(['success'=>true]);
            }else{
                return new JsonResponse(['success'=>false]);
            }
        }
        throw new \Exception('This is not an ajax call');
    }

    /**
     * @Route("/podcast/", name="podcast")
     */
    public function podcast(Request $request){
        $em = $this->getDoctrine()->getManager();
        $seasons = $em->getRepository(Season::class)->findAll();
        $user = $this->getUser();
        $comments = $em->getRepository(Comments::class)->findBy(['comment_for'=>Comments::COMMENT_FOR_PODCAST]);
        $comment = new Comments(new \DateTime(),Comments::COMMENT_FOR_PODCAST,$user);
        $form_comment = $this->createForm(CommentsType::class, $comment);
        $form_comment->handleRequest($request);
        if($form_comment->isSubmitted() && $form_comment->isValid()){
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('podcast');
        }
        return $this->render('standard/podcast.html.twig',
        [
            'seasons'=>$seasons,
            'form_comment'=>$form_comment->createView(),
            'comments'=>$comments
        ]);
    }

    /**
     * @Route("/podcast/season/{id}", name="podcast_audio")
     */
    public function podcast_audio(Season $season){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $purchased_service = $user->getPurchasedservices();
        $audios = $em->getRepository(Audio::class)->findBy(['season'=>$season]);
        return $this->render('standard/audios.html.twig',[
            'audios'=>$audios,
            'season'=>$season,
            'purchasedservice'=>$purchased_service
        ]);
    }

    /**
     * @Route("/create/post/", name="create_post")
     */
    public function create_post(Request $request){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $post = new Post($user);
        $form_post = $this->createForm(PostType::class, $post);
        $form_post->handleRequest($request);
        if($form_post->isSubmitted() && $form_post->isValid()){
            $em->persist($post);
            $em->flush();
            return  $this->redirectToRoute('view_post',['id'=>$post->getId()]);
        }
        return $this->render('standard/create_post.html.twig',[
            'form_post'=>$form_post->createView()
        ]);
    }

    /**
     * @Route("/view/posts/", name="view_posts")
     */
    public function view_all_posts(PaginatorInterface $paginator, Request $request){
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Post::class)->findAllPosts();
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('standard/view_all_posts.html.twig',[
            'pagination'=>$pagination
        ]);
    }

    /**
     * @Route("/view/post/{id}", name="view_post")
     */
    public function view_post(Post $post, Request $request){
        $em = $this->getDoctrine()->getManager();
        $comments_for_this_post = $em->getRepository(PostComment::class)->findBy(['post'=>$post]);
        $comment = new PostComment($post);
        $form_comment = $this->createForm(PostCommentType::class,$comment);
        $form_comment->handleRequest($request);
        if($form_comment->isSubmitted() && $form_comment->isValid()){
            $user = $this->getUser();
            $post->setUser($user->getName().' '.$user->getLastName());
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('view_post',['id'=>$post->getId()]);
        }
        return $this->render('standard/view_post.html.twig',[
            'post'=>$post,
            'comments' => $comments_for_this_post,
            'form_comment'=>$form_comment->createView()
        ]);
    }
}
