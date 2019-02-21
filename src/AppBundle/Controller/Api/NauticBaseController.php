<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\NauticBase;
use AppBundle\Form\NauticBaseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class NauticBaseController extends Controller
{

    /**
     * @Route("/nauticbases", methods={"GET"}, name="api_nauticbase_list")
     */
    public function listAction(SerializerInterface $serializer)
    {
        $nauticBases = $this->getDoctrine()
            ->getRepository(NauticBase::class)
            ->findAll();
        $data = ['nauticBases' => []];
        foreach ($nauticBases as $nauticBase) {
            $data['nauticBases'][] = $serializer->serialize($nauticBase, 'json');
        }

        $response = new JsonResponse(json_encode($data), 200);
        return $response;
    }

    /**
     * @Route("/nauticbases/{id}", methods={"GET"}, name="api_nauticbase_show")
     */
    public function showAction(int $id, SerializerInterface $serializer)
    {
        /** @var NauticBase $nauticBase */
        $nauticBase = $this->getDoctrine()
            ->getRepository(NauticBase::class)
            ->find($id);

        if (!$nauticBase) {
            throw $this->createNotFoundException(sprintf(
                'No nautic base found with id "%s"',
                $id
            ));
        }

        $data = $serializer->serialize($nauticBase, 'json');

        $response = new JsonResponse(json_encode($data), 200);
        return $response;
    }

    /**
     * @Route("/nauticbases", methods={"POST"}, name="api_nauticbase_create")
     */
    public function createAction(Request $request, SerializerInterface $serializer)
    {
        $nauticBase = new NauticBase();
        $form = $this->createForm(NauticBaseType::class, $nauticBase);
        $this->processForm($request, $form);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($nauticBase);
            $em->flush();
        }

        $data = $serializer->serialize($nauticBase, 'json');
        $response = new JsonResponse(json_encode($data), 201);
        $redirectUrl = $this->generateUrl(
            'api_nauticbase_show',
            ['id' => $nauticBase->getId()]
        );
        $response->headers->set('Location', $redirectUrl);
        return $response;
    }

    /**
     * @Route("/nauticbases/{id}", methods={"PUT", "PATCH"}, name="api_nauticbase_update")
     */
    public function updateAction(int $id, Request $request, SerializerInterface $serializer)
    {
        /** @var NauticBase $nauticBase */
        $nauticBase = $this->getDoctrine()
            ->getRepository(NauticBase::class)
            ->find($id);

        if (!$nauticBase) {
            throw $this->createNotFoundException(sprintf(
                'No nautic base found with id "%s"',
                $id
            ));
        }

        $form = $this->createForm(NauticBaseType::class, $nauticBase);
        $this->processForm($request, $form);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($nauticBase);
            $em->flush();
        }

        $data = $serializer->serialize($nauticBase, 'json');
        $response = new JsonResponse(json_encode($data), 200);
        $redirectUrl = $this->generateUrl(
            'api_nauticbase_show',
            ['id' => $nauticBase->getId()]
        );
        $response->headers->set('Location', $redirectUrl);
        return $response;
    }

    /**
     * @Route("/nauticbases/{id}", methods={"DELETE"}, name="api_nauticbase_delete")
     *
     * Return a 204 even if the resource is not found.
     */
    public function deleteAction(int $id)
    {
        /** @var NauticBase $nauticBase */
        $nauticBase = $this->getDoctrine()
            ->getRepository(NauticBase::class)
            ->find($id);

        if ($nauticBase) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($nauticBase);
            $em->flush();
        }

        return new JsonResponse(null, 204);
    }

    /**
     * @param Request $request
     * @param FormInterface $form
     */
    private function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);
        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($data, $clearMissing);
    }
}
