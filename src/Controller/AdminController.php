<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Post;
use App\Form\PostType;

/**
 *
 * @Route("/admin")
 */
class AdminController extends AbstractController
{

    /**
     *
     * @Route("", name="app_admin_index")
     */
    public function index(PostRepository $repo): Response
    {
        $posts = $repo->findAll();
        return $this->render('admin/index.html.twig', [
            'posts' => count($posts)
        ]);
    }

    /**
     *
     * @Route("/post", name="app_admin_posts_index")
     */
    public function postIndex(): Response
    {
        $posts = $this->getUser()->getPosts();
        return $this->render('admin/post_index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     *
     * @Route("/post/new", name="app_admin_post_new", methods={"GET","POST"})
     * @Security("is_granted('POST_CREATE')")
     */
    public function new(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_posts_index');
        }

        return $this->render('admin/post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    /**
     *
     * @Route("/post/{id}/edit", name="app_admin_post_edit", methods={"GET","POST"})
     * @Security("is_granted('POST_MANAGE',post)")
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser());
            $this->getDoctrine()
                ->getManager()
                ->flush();

            return $this->redirectToRoute('app_admin_posts_index');
        }

        return $this->render('admin/post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }


    /**
     *
     * @Route("/post/{id}", name="app_admin_post_delete", methods={"DELETE"})
     * @Security("is_granted('POST_MANAGE',post)")
     */
    public function delete(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_posts_index');
    }
}
