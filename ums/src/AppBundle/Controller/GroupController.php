<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use AppBundle\Entity\UMSGroup;

class GroupController extends Controller
{
    /**
     * @Route("/group", name="group")
     * @Rest\Get("/api/group")
     */
    public function indexAction(Request $request)
    {
        if($request->isMethod("POST") && $request->get('name')) {
            $group = new UMSGroup();
            $group->setName($request->get('name'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($group);
            $em->flush();
            $this->addFlash("group-success", "Group added");
        }

        $repository = $this->getDoctrine()->getRepository(UMSGroup::class);
        $groups = $repository->findAll();

        if($request->getRequestFormat() == 'json') {
            return $groups;
        } else {
            return $this->render('AppBundle:Admin:groups.html.twig', [
                'groups'    => $groups
            ]);
        }
    }

    /**
     * @Route("/group/delete/{id}", name="group_delete")
     * @Rest\Delete("/api/group/delete/{id}")
     */
    public function deleteAction(Request $request, $id)
    {
        if($id) {
            $group = $this->getDoctrine()->getRepository(UMSGroup::class)->find($id);
            if (empty($group)) {
                $this->addFlash("group-error", "Group not found");
                return $this->redirectToRoute('group');
            }
            else {
                if($group->getuserscont()>0) {
                    $this->addFlash("group-error", "This Group has " . $group->getuserscont() . " users. Please remove users from this group first.");
                    return $this->redirectToRoute('group');
                }

                $sn = $this->getDoctrine()->getManager();
                $sn->remove($group);
                $sn->flush();
            }
            if($request->getRequestFormat() == 'json') {
                return ['success'=>true];
            } else {
                $this->addFlash("group-success", "Group deleted");
                return $this->redirectToRoute('group');
            }

        }
    }

}
