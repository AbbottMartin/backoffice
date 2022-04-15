<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserCreateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController{
    private $hasher;
    private $em;

    public function __construct(UserPasswordHasherInterface $hasher, EntityManagerInterface $em) 
    {
        $this->hasher = $hasher;
        $this->em = $em;
    }
    
    /**
     * @Route("/admin/create", name="create_admin")
     */
    public function create(): Response
    {

        $user = new User();
        $plaintextPassword = 'lkm8u...';

        $hashedPassword = $this->hasher->hashPassword($user, $plaintextPassword);
        $user->setPassword($hashedPassword);
        $user->setUsername('admin');
        $user->setRoles(['ROLE_ADMIN']);
        
        $this->em->persist($user);
        $this->em->flush();
        return new Response('admin creado');
    }

    /**
     * @Route("/user/create", name="user_create")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserCreateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user->setRoles([$form->get('role')->getData()]);
            $user->setEnabled(true);
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/create.html.twig', [
            'userCreateForm' => $form->createView(),
            'title' => 'Create User',
        ]);
    }
    /**
     * @Route("/user/edit/{id}", name="user_edit")
     */
    public function edit(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($request->get('id'));
        if(empty($user)){
            $this->addFlash('danger', 'User does not exist.');
            return $this->redirectToRoute('user_index');
        }

        $form = $this->createForm(UserCreateType::class, $user, ['is_edit' => true, 'selected_role' => $user->getRoles()[0]]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            if ($form->get('password')->getData()) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
            }
            

            $user->setRoles([$form->get('role')->getData()]);
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/create.html.twig', [
            'userCreateForm' => $form->createView(),
            'title' => 'Edit User'
        ]);
    }
    /**
     * @Route("/user/update_status/{id}", name="user_update_status")
     */
    public function updateStatus(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($request->get('id'));
        if(empty($user)){
            $this->addFlash('danger', 'User does not exist.');
            return $this->redirectToRoute('user_index');
        }
        $user->setEnabled(!$user->getEnabled());
        $entityManager->persist($user);
        $entityManager->flush();
        // do anything else you need here, like send an email

        return $this->redirectToRoute('user_index');
    }
    /**
     * @Route("/user", name="user_index")
     */
    public function FunctionName(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();
        return $this->render('user/index.html.twig', ['users' => $users, 'title' => 'User List',]);
    }
}