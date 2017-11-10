<?php

namespace AppBundle\Controller;

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
     */
    public function postUsersAction(ParamFetcher $paramFetcher)
    {
        $om = $this->getDoctrine()->getManager();

        $user = new User();

        $user
            ->setEmail($paramFetcher->get('email'))
            ->setFirstName($paramFetcher->get('firstName'))
            ->setLastName($paramFetcher->get('lastName'))
            ->setEnabled($paramFetcher->get('enabled'))
        ;

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
        $om = $this->getDoctrine()->getManager();

        $user = $om->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        return $user;
    }
}
