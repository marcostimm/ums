<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use AppBundle\Entity\User;
use AppBundle\Entity\UMSGroup;

class UserController extends FOSRestController
{
    /**
     * @Route("/user", name="user")
     * @Rest\Get("/api/user")
     */
    public function indexAction(Request $request)
    {

        if($request->isMethod("POST") && $request->get('name')) {
            $user= new User();
            $user->setName($request->get('name'));
            $user->setUsername($this->normalize($request->get('name')));
            $user->setPassword('123');
            $user->setRole("USER");

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash("user-success", "User added");
        }

        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();


        if($request->getRequestFormat() == 'json') {
            return $users;
        } else {
            // replace this example code with whatever you need
            return $this->render('AppBundle:Admin:users.html.twig', [
                'users' => $users
            ]);
        }
    }

    /**
     * @Route("/user/update/{id}", name="user_update")
     * @Rest\Put("/api/user/update/{id}")
     */
    public function updateAction(Request $request, $id)
    {
        if($id) {

            $user = $this->getDoctrine()->getRepository(User::class)->find($id);

            if (empty($user))
            {
                $this->addFlash("user-error", "User not found");
                return $this->redirectToRoute('user');

            } else {
                $sn = $this->getDoctrine()->getManager();
                $user->setName($request->get('name'));

                // Flush all Groups before add selected groups
                $groups = $this->getDoctrine()->getRepository(UMSGroup::class)->findAll();
                foreach($groups AS $group)
                {
                    $user->getGroups()->removeElement($group);
                }

                if($request->get("groups")) {
                    foreach ($request->get("groups") AS $group) {
                        $group = $this->getDoctrine()->getRepository(UMSGroup::class)->find($group);
                        $user->setGroup($group);
                    }
                }

                $sn->persist($user);
                $sn->flush();
            }

            if($request->getRequestFormat() == 'json') {
                return ['success'=>true];
            } else {
                $this->addFlash("user-success", "User updated");
                return $this->redirectToRoute('user');
            }

        }
    }

    /**
     * @Route("/user/{id}", name="user_edit")
     */
    public function editAction($id, Request $request)
    {
        $user   = $this->getDoctrine()->getRepository(User::class)->find($id);
        if($user) {
            $groups = $this->getDoctrine()->getRepository(UMSGroup::class)->findAll();

            return $this->render('AppBundle:Admin:user-edit.html.twig', [
                'user' => $user,
                'userGroupsId' => $user->getGroupsID(),
                'groups' => $groups
            ]);
        } else {
            $this->addFlash("user-error", "User not found");
            return $this->redirectToRoute('user');
        }
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete")
     * @Rest\Delete("/api/user/delete/{id}")
     */
    public function deleteAction(Request $request, $id)
    {
        if($id) {
            $user = $this->getDoctrine()->getRepository(User::class)->find($id);
            if (empty($user)) {
                $this->addFlash("user-error", "User not found");
                return $this->redirectToRoute('user');
            }
            else {

                $sn = $this->getDoctrine()->getManager();
                $sn->remove($user);
                $sn->flush();
            }
            if($request->getRequestFormat() == 'json') {
                return ['success'=>true];
            } else {
                $this->addFlash("user-success", "User deleted");
                return $this->redirectToRoute('user');
            }
        }
    }

    function normalize($string) {
        $string = preg_replace('/\s+/', ' ', $string);
        $string = preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml|caron);~i', '$1', htmlentities($string, ENT_COMPAT, 'UTF-8'));
        $string = strtolower($string);

        return $string;
    }
}
