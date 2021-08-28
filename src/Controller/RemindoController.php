<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Controller;

use App\Entity\Remindo;
use App\Exception\ProcessorException;
use App\Form\Data\UploadData;
use App\Form\Type\UploadType;
use App\Processor\RemindoImportProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Remindo Controller.
 *
 * @author wicliff <wwolda@gmail.com>
 */
final class RemindoController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(path="/", name="app_index")
     */
    public function redirectToRemindo(): Response
    {
        return $this->redirectToRoute('app_remindo_index');
    }

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \UnexpectedValueException
     *
     * @Route(path="/remindo", name="app_remindo_index")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        return $this->render('remindo/index.html.twig', [
            'tests' => $entityManager->getRepository(Remindo::class)->findBy([], ['created' => 'asc']),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request          $request
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     * @param \App\Processor\RemindoImportProcessor              $processor
     * @param \Doctrine\ORM\EntityManagerInterface               $em
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(path="/remindo/new", name="app_remindo_new")
     */
    public function new(Request $request, TranslatorInterface $translator, RemindoImportProcessor $processor, EntityManagerInterface $em): Response
    {
        $upload = new UploadData();
        $form = $this->createForm(UploadType::class, $upload, [
            'action' => $this->generateUrl('app_remindo_new'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /* @var UploadData $data */
            try {
                $test = $processor->process($upload);

                $em->persist($test);
                $em->flush();

                $this->addFlash('success', $translator->trans('remindo.new.success', ['%file_name%' => $upload->getName()]));

                return $this->redirectToRoute('app_remindo_index');
            } catch (ProcessorException $e) {
                $this->addFlash('error', $translator->trans('remindo.new.error', [
                    '%file_name%' => $upload->getName(),
                    '%message%' => $e->getMessage(),
                ]));
            }
        }

        return $this->renderForm('remindo/upload.html.twig', [
            'form' => $form,
            'formTarget' => $request->headers->get('Turbo-Frame', '_top'),
        ]);
    }

    /**
     * @param \App\Entity\Remindo $remindo
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(path="/remindo/{id}", name="app_remindo_detail", methods={"GET"})
     */
    public function detail(Remindo $remindo): Response
    {
        return $this->render('remindo/detail.html.twig', [
            'remindo' => $remindo,
        ]);
    }

    /**
     * @param \Doctrine\ORM\EntityManagerInterface               $entityManager
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     * @param \App\Entity\Remindo                                $remindo
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(path="/remindo/{id}/delete", name="app_remindo_delete")
     */
    public function delete(EntityManagerInterface $entityManager, TranslatorInterface $translator, Remindo $remindo): Response
    {
        $entityManager->remove($remindo);
        $entityManager->flush();

        $this->addFlash('success', $translator->trans('remindo.delete.success'));

        return $this->redirectToRoute('app_remindo_index');
    }
}
