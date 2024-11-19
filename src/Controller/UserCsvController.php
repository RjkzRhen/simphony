<?php

namespace App\Controller; // ���������� ������������ ���� ��� �����������

use App\Entity\UserCsv; // ���������� �������� UserCsv
use App\Form\UserCsvType; // ���������� ����� UserCsvType
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; // ���������� ������� ����� �����������
use Symfony\Component\HttpFoundation\Request; // ���������� ����� Request ��� ��������� HTTP ��������
use Symfony\Component\HttpFoundation\Response; // ���������� ����� Response ��� �������� HTTP �������
use Symfony\Component\Routing\Annotation\Route; // ���������� ��������� Route ��� ����������� ���������

class UserCsvController extends AbstractController // ���������� ����� �����������, ������������� �� AbstractController
{
    // ������� ��� ����������� CSV �����
    #[Route('/user-csv', name: 'user_csv_index')] // ���������� ������� ��� ����������� CSV �����
    public function index(): Response // ���������� ����� index, ������� ���������� Response
    {
        $filePath = __DIR__ . '/../../exports/userCsv.csv'; // ���������� ���� � CSV �����

        // ���������, ���������� �� CSV ����
        if (!file_exists($filePath)) { // ���������, ���������� �� ���� �� ���������� ����
            return new Response('CSV ���� �� ������', 404); // ���������� ����� � ����� 404, ���� ���� �� ������
        }

        // ��������� CSV ����
        $file = fopen($filePath, 'r'); // ��������� ���� ��� ������
        $users = []; // �������������� ������ ��� �������� ������ �� CSV

        // ���������� ��������� (���� ��� ����)
        $headers = fgetcsv($file); // ������ ������ ������ (���������) � ���������� �

        // ������ ��� ������
        while (($data = fgetcsv($file)) !== false) { // ������ ������ �� ����� �� ��� ���, ���� ��� �� ����������
            $users[] = $data; // ��������� ������ � ������ users
        }

        fclose($file); // ��������� ����

        // ���������� ������ � ������ ��� �����������
        return $this->render('user_csv/index.html.twig', [ // �������� ������ user_csv/index.html.twig � �������� � ���� ������ users
            'users' => $users, // �������� ������ ������������� � ������
        ]);
    }

    // ������� ��� �������� ������ ������������ � ���������� ��� � CSV ����
    #[Route('/user-csv/new', name: 'user_csv_new')] // ���������� ������� ��� �������� ������ ������������ � ���������� ��� � CSV ����
    public function new(Request $request): Response // ���������� ����� new, ������� ��������� Request
    {
        $userCsv = new UserCsv(); // ������� ����� ������ UserCsv
        $form = $this->createForm(UserCsvType::class, $userCsv); // ������� ����� ��� ������� UserCsv

        $form->handleRequest($request); // ������������ ������ � ��������� ����� ������� �� �������
        if ($form->isSubmitted() && $form->isValid()) { // ���������, ���� �� ���������� ����� � �������� �� ��� ��������
            // ���������� ������������ � CSV
            $this->writeToCsv($userCsv); // �������� ����� ��� ������ ������ � CSV ����

            // ����������� �� �������� ����������
            $this->addFlash('success', '������������ ������� �������� � CSV!'); // ��������� ����-��������� �� �������� ����������

            // ��������������� �� ������� �������� ����� ��������� ����������
            return $this->redirectToRoute('user_csv_new'); // �������������� ������������ �� ������� user_csv_new
        }

        return $this->render('user_csv/new.html.twig', [ // �������� ������ user_csv/new.html.twig � �������� � ���� �����
            'form' => $form->createView(), // �������� ����� � ������
        ]);
    }

    // ����� ��� ������ ������ ������������ � CSV ����
    private function writeToCsv(UserCsv $userCsv): void // ���������� ��������� ����� writeToCsv, ������� ��������� ������ UserCsv
    {
        $filePath = __DIR__ . '/../../exports/userCsv.csv'; // ���������� ���� � CSV �����

        // ���� ���� ��� �� ����������, ��������� ���������
        $fileExists = file_exists($filePath); // ���������, ���������� �� ���� �� ���������� ����
        $handle = fopen($filePath, 'a'); // ��������� ���� ��� ���������� ������ � �����

        if (!$fileExists) { // ���� ���� �� ����������
            fputcsv($handle, ['Last Name', 'First Name', 'Middle Name', 'Age', 'Username', 'Password']); // ��������� ��������� � ����
        }

        // ��������� ������
        fputcsv($handle, [ // ��������� ������ � ������� ������������ � ����
            $userCsv->lastName,
            $userCsv->firstName,
            $userCsv->middleName,
            $userCsv->age,
            $userCsv->username,
            $userCsv->password,
        ]);

        fclose($handle); // ��������� ����
    }
}