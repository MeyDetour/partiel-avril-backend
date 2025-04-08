<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class UserController extends AbstractController
{
    #[Route('/users', name: 'admin_users')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            "users" => $userRepository->findAll()
        ]);
    }

    #[Route('/grant/admin/{id}', name: 'grant_user_to_admin')]
    public function grantUserToAdmin(User $user, EntityManagerInterface $entityManager): Response
    {
        $owUser = $this->getUser();
        if ($user->getId() == $owUser->getId()) return $this->redirectToRoute('admin_users', [], Response::HTTP_SEE_OTHER);
        $user->setRoles(["ROLE_ADMIN"]);
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('admin_users', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/revoke/admin/{id}', name: 'revoke_user_to_admin')]
    public function revokeUserToAdmin(User $user, EntityManagerInterface $entityManager): Response
    {
        $owUser = $this->getUser();
        if ($user->getId() == $owUser->getId()) return $this->redirectToRoute('admin_users', [], Response::HTTP_SEE_OTHER);

        $user->setRoles(["ROLE_USER"]);

        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('admin_users', [], Response::HTTP_SEE_OTHER);
    }
}
