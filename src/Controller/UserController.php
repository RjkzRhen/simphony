<?php

namespace App\Controller; // ���������� ������������ ���� ��� �����������

use App\Entity\User; // ���������� �������� User
use App\Form\UserType; // ���������� ����� UserType
use Doctrine\ORM\EntityManagerInterface; // ���������� ��������� EntityManager
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; // ���������� ������� ����� �����������
use Symfony\Component\HttpFoundation\Request; // ���������� ����� Request ��� ��������� HTTP ��������
use Symfony\Component\HttpFoundation\Response; // ���������� ����� Response ��� �������� HTTP �������
use Symfony\Component\Routing\Annotation\Route; // ���������� ��������� Route ��� ����������� ���������

class UserController extends AbstractController // ���������� ����� �����������, ������������� �� AbstractController
{
    // ������� ��� ����������� ������ ���� �������������
    #[Route('/users', name: 'app_users')] // ���������� ������� ��� ����������� ������ �������������
    public function index(EntityManagerInterface $entityManager): Response // ���������� ����� index, ������� ��������� EntityManager
    {
        // �������� ���� ������������� �� ���� ������
        $users = $entityManager->getRepository(User::class)->findAll(); // �������� ��� ������ �� ����������� User

        // ���������� ������������� � ������
        return $this->render('user/index.html.twig', [ // �������� ������ user/index.html.twig � �������� � ���� ���������� users
            'users' => $users, // �������� ������ ������������� � ������
        ]);
    }

    // ������� ��� �������� ������ ������������
    #[Route('/user/new', name: 'user_new')] // ���������� ������� ��� �������� ������ ������������
    public function new(Request $request, EntityManagerInterface $entityManager): Response // ���������� ����� new, ������� ��������� Request � EntityManager
    {
        $user = new User(); // ������� ����� ������ User
        $form = $this->createForm(UserType::class, $user); // ������� ����� ��� ������� User

        $form->handleRequest($request); // ������������ ������ � ��������� ����� ������� �� �������
        if ($form->isSubmitted() && $form->isValid()) { // ���������, ���� �� ���������� ����� � �������� �� ��� ��������
            // ��������� ������ ������������ � ���� ������
            $entityManager->persist($user); // ������� Doctrine ��������� ������ User
            $entityManager->flush(); // ��������� ������ � ���� ������ ��� ���������� �������

            // �������������� �� �������� ������ ������������� ����� ��������� ����������
            return $this->redirectToRoute('app_users'); // �������������� ������������ �� ������� app_users
        }

        return $this->render('user/new.html.twig', [ // �������� ������ user/new.html.twig � �������� � ���� �����
            'form' => $form->createView(), // �������� ����� � ������
        ]);
    }
}