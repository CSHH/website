<?php

namespace AppTests;

use Mockery;
use Mockery as m;

trait UnitMocks
{
    protected $dao;
    protected $em;
    protected $qb;
    protected $query;
    protected $translator;

    protected function getEntityDaoMock()
    {
        return m::mock('Kdyby\Doctrine\EntityDao');
    }

    protected function getEntityManagerMock()
    {
        return m::mock('Kdyby\Doctrine\EntityManager');
    }

    protected function getQueryBuilderMock()
    {
        return m::mock('Kdyby\Doctrine\QueryBuilder');
    }

    protected function getQueryMock()
    {
        return m::mock('Doctrine\ORM\AbstractQuery');
    }

    protected function getTranslatorMock()
    {
        return m::mock('Nette\Localization\ITranslator');
    }

    /**
     * @param Mockery\MockInterface $mockObject
     * @param string                $methodName
     * @param string                $callTimes
     * @param string                $returnValue
     */
    protected function mock(Mockery\MockInterface $mockObject, $methodName, $callTimes = 1, $returnValue = null)
    {
        $mockObject->shouldReceive($methodName)
            ->times($callTimes)
            ->andReturn($returnValue);
    }

    /**
     * @param Mockery\MockInterface $mockObject
     * @param string                $methodName
     * @param string                $callTimes
     */
    protected function mockAndReturnSelf(Mockery\MockInterface $mockObject, $methodName, $callTimes = 1)
    {
        $mockObject->shouldReceive($methodName)
            ->times($callTimes)
            ->andReturnSelf();
    }

    protected function setUp()
    {
        $this->dao        = $this->getEntityDaoMock();
        $this->em         = $this->getEntityManagerMock();
        $this->qb         = $this->getQueryBuilderMock();
        $this->query      = $this->getQueryMock();
        $this->translator = $this->getTranslatorMock();
    }

    protected function tearDown()
    {
        m::close();
    }
}
