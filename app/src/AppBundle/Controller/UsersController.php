<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\FOSRestController;
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
}
