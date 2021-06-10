<?php

/**
 * MessageRepository.php
 *
 * Doctrine Class for entity management
 *
 * @author Joe
 */


namespace Coursework;


use Doctrine\ORM\EntityManager;

class MessageRepository
{
    /**
     * @var EntityManager
     */
    private $em;

    function __construct($em) {
        $this->em = $em;
    }
    /**
     * @return EntityManager
     */
    public function getEm(): EntityManager
    {
        return $this->em;
    }

    function store($message) {
        $this->em->persist($message);
        $this->em->flush();
    }

    function getAll() {
        $qb = $this->em->createQueryBuilder();
        $qb->select('m')->from('message', 'm');
        $query = $qb->getQuery();

        $output = $query->getResult();


        var_dump($output);
    }
}
