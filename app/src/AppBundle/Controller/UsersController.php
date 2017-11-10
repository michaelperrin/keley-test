<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use AppBundle\Form\Type\UserType;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

class UsersController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  section="User",
     *  description="Get users",
     * )
     */
    public function getUsersAction()
    {
        $om = $this->getDoctrine()->getManager();

        $users = $om->getRepository(User::class)->findAll();

        return $users;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  section="User",
     *  description="Create user",
     *  input="AppBundle\Form\Type\UserType"
     * )
     */
    public function postUsersAction(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $om = $this->getDoctrine()->getManager();

            $om->persist($user);
            $om->flush();

            return $user;
        }

        return $form;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  section="User",
     *  description="Get user",
     * )
     */
    public function getUserAction(int $id)
    {
        $user = $this->getUserById($id);

        return $user;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  section="User",
     *  description="Update user",
     *  input="AppBundle\Form\Type\UserType"
     * )
     */
    public function putUserAction(Request $request, User $user)
    {
        // $user = $this->getUserById($id);

        $form = $this->createForm(UserType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $om = $this->getDoctrine()->getManager();

            $om->persist($user);
            $om->flush();

            return $user;
        }

        return $form;
    }

    private function getUserById(int $id) : User
    {
        $om = $this->getDoctrine()->getManager();
        $user = $om->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        return $user;
    }

    private function getGroupByName(string $name) : Group
    {
        $om = $this->getDoctrine()->getManager();
        $group = $om->getRepository(Group::class)->findOneBy(['name' => $name]);

        if (!$group) {
            throw $this->createNotFoundException('Group not found.');
        }

        return $group;
    }
}
