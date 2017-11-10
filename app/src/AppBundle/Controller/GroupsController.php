<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class GroupsController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  section="User group",
     *  description="Get groups",
     * )
     */
    public function getGroupsAction()
    {
        $om = $this->getDoctrine()->getManager();

        $groups = $om->getRepository(Group::class)->findAll();

        return $groups;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  section="User group",
     *  description="Create group",
     * )
     *
     * @RequestParam(name="name", nullable=false, description="Group name")
     */
    public function postGroupsAction(ParamFetcher $paramFetcher)
    {
        $om = $this->getDoctrine()->getManager();

        $group = new Group();
        $this->updateGroupFromParameters($group, $paramFetcher);

        $om->persist($group);
        $om->flush();

        return $group;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  section="User group",
     *  description="Get group",
     * )
     */
    public function getGroupAction(int $id)
    {
        $group = $this->getGroupById($id);

        return $group;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  section="User group",
     *  description="Update group",
     * )
     *
     * @RequestParam(name="name", nullable=false, description="Group name")
     */
    public function putGroupAction(ParamFetcher $paramFetcher, int $id)
    {
        $group = $this->getGroupById($id);
        $om = $this->getDoctrine()->getManager();

        $this->updateGroupFromParameters($group, $paramFetcher);

        $om->persist($group);
        $om->flush();

        return $group;
    }

    private function updateGroupFromParameters(Group $group, ParamFetcher $paramFetcher) : Group
    {
        $group->setName($paramFetcher->get('name'));

        return $group;
    }

    private function getGroupById(int $id) : Group
    {
        $om = $this->getDoctrine()->getManager();
        $group = $om->getRepository(Group::class)->find($id);

        if (!$group) {
            throw $this->createNotFoundException('Group not found.');
        }

        return $group;
    }
}
