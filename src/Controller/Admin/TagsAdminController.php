<?php

namespace App\Controller\Admin;

use App\Entity\Tags;
use App\Form\TagsType;
use App\Repository\TagsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/tags')]
class TagsAdminController extends AbstractController
{
    #[Route('/', name: 'admin_tags_index', methods: ['GET'])]
    public function index(TagsRepository $tagsRepository): Response
    {
        $tags = $tagsRepository->findAll();

        return $this->render('admin/tags/index.html.twig', [
            'tags' => $tagsRepository->findAll(),
            'sidebar' => 'redac',
        ]);
    }

    #[Route('/new', name: 'admin_tag_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $tag = new Tags();
        $form = $this->createForm(TagsType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tag);
            $entityManager->flush();

            return $this->redirectToRoute('admin_tags_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/tags/new.html.twig', [
            'tag' => $tag,
            'form' => $form,
            'sidebar' => 'redac'
        ]);
    }

    #[Route('/{id}', name: 'admin_tags_show', methods: ['GET'])]
    public function show(Tags $tag): Response
    {
        return $this->render('admin/tags/show.html.twig', [
            'tag' => $tag,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_tags_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tags $tag, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TagsType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_tags_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/tags/edit.html.twig', [
            'tag' => $tag,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_tags_delete', methods: ['POST'])]
    public function delete(Request $request, Tags $tag, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tag->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tag);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_tags_index', [], Response::HTTP_SEE_OTHER);
    }
}