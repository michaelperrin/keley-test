<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

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
     * )
     *
     * @RequestParam(name="email", nullable=false, description="E-mail address")
     * @RequestParam(name="firstName", nullable=false, description="First name")
     * @RequestParam(name="lastName", nullable=false, description="Last name")
     * @RequestParam(name="enabled", nullable=false, description="Whether user is enabled or not")
     * @RequestParam(name="group", nullable=false, description="User's group")
     */
    public function postUsersAction(ParamFetcher $paramFetcher)
    {
        $om = $this->getDoctrine()->getManager();

        $user = new User();
        $this->updateUserFromParameters($user, $paramFetcher);

        $om->persist($user);
        $om->flush();

        return $user;
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
     * )
     *
     * @RequestParam(name="email", nullable=false, description="E-mail address")
     * @RequestParam(name="firstName", nullable=false, description="First name")
     * @RequestParam(name="lastName", nullable=false, description="Last name")
     * @RequestParam(name="enabled", nullable=false, description="Whether user is enabled or not")
     * @RequestParam(name="group", nullable=false, description="User's group")
     */
    public function putUserAction(ParamFetcher $paramFetcher, int $id)
    {
        $user = $this->getUserById($id);
        $om = $this->getDoctrine()->getManager();

        $this->updateUserFromParameters($user, $paramFetcher);

        $om->persist($user);
        $om->flush();

        return $user;
    }

    private function updateUserFromParameters(User $user, ParamFetcher $paramFetcher) : User
    {
        $group = $this->getGroupByName($paramFetcher->get('group'));

        $user
            ->setEmail($paramFetcher->get('email'))
            ->setFirstName($paramFetcher->get('firstName'))
            ->setLastName($paramFetcher->get('lastName'))
            ->setEnabled($paramFetcher->get('enabled'))
            ->setGroup($group)
        ;

        return $user;
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
