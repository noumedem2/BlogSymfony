<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/account")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("", name="app_account",methods="GET")
     */
    public function show(): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('error', 'You need to log in first!');
            return $this->redirectToRoute('post_index');
        }
        return $this->render('account/show.html.twig', []);
    }
    /**
     * @Route("/edit", name="app_account_edit",methods={"GET","POST"})
     */
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('error', 'You need to log in first!');
            return $this->redirectToRoute('post_index');
        }
        $user = $this->getUser();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Account Updated succefully!');
            return $this->redirectToRoute('app_account');
        }
        $form = $form->createView();
        return $this->render('account/edit.html.twig', compact('form'));
    }
    /**
     * @Route("/change-password", name="app_account_change_password",methods={"GET","POST"})
     */
    public function changePassword(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response {
        if (!$this->getUser()) {
            $this->addFlash('error', 'You need to log in first!');
            return $this->redirectToRoute('post_index');
        }
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordFormType::class, null, [
            'current_password_is_required' => true,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('plainPassword')->getData();
            $password = $passwordEncoder->encodePassword($user, $password);
            $user->setPassword($password);
            $em->flush();
            $this->addFlash('success', 'Password updated successfully');
            return $this->redirectToRoute('app_account');
        }
        $form = $form->createView();
        return $this->render(
            'account/change_password.html.twig',
            compact('form')
        );
    }
}
