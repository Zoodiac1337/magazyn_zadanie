<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ReceiveType;

class ReceiveGoodsController extends AbstractController
{
    #[Route('/receive-goods', name: 'app_receive_goods')]
    public function receiveGoods(Request $request, EntityManagerInterface $entityManager): Response
    {
        $operation = new \App\Entity\WarehouseOperation();
        $form = $this->createForm(ReceiveType::class, $operation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $files = $form->get('files')->getData();
            $savedFileNames = [];

            if ($files) {
                foreach ($files as $file) {
                    // Create a clean, unique filename
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                    try {
                        // Move the file physically to public/uploads/invoices
                        $file->move(
                            $this->getParameter('invoice_directory'),
                            $newFilename
                        );
                        
                        // Add the successful filename to the tracking list
                        $savedFileNames[] = $newFilename;
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'Could not upload file: ' . $file->getClientOriginalName());
                    }
                }
            }
            $operation->setInvoiceFilenames($savedFileNames);
            $operation->setType('IN'); // Set the operation type to 'IN' for receiving goods
            $operation->setUser($this->getUser()); // Set the user who performed the
            $operation->setCreatedAt(new \DateTime()); // Timestamp of the operation
            $entityManager->persist($operation);
            $entityManager->flush();

            // Display a success message
            $this->addFlash('success', 'Goods received successfully!');
        }
        return $this->render('receivegoods.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}