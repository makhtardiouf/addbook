<?php

/**
 * Generated by PHPUnit_SkeletonGenerator 
 */
require_once "model/Group.php";

class GroupTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Group
     */
    protected $g;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->g = new Group;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers Group::Persist
     * @todo   Implement testPersist().
     */
    public function testPersist() {
        //      $g = new Group();
        $this->g->setName("GroupTest" . rand());
        $this->g->setParentGroup("GroupB");
        $this->assertTrue($this->g->Persist(false));
    }

    /**
     * @covers Group::GetGroup
     * @todo   Implement testGetGroup().
     */
    public function testGetGroup() {
        $row = $this->g->GetGroup(1);
        if ($row) {
            var_dump($row);
            $this->assertEquals("GroupD", $row['name']);
        }
    }

    /**
     * @covers Group::GetGroups
     * @todo   Implement testGetGroups().
     */
    public function testGetGroups() {
        $stm = $this->g->GetGroups();
        if ($stm) {
            $group = $stm->fetch();
            var_dump($group);
            $this->assertArrayHasKey("name", $group);
        }
    }

    /**
     * @covers Group::GetContacts
     * @todo   Implement testGetContacts().
     */
    public function testGetContacts() {
        $stms = $this->g->GetContacts(1);
        if ($stms) {
            foreach ($stms as $k => $stm) {
                $group = $stm->fetch();
                var_dump($group);
                $this->assertArrayHasKey("name", $group);
            }
        }
    }

    /**
     * @covers Group::Delete
     * @todo   Implement testDelete().
     */
    public function testDelete() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Group::getId
     * @todo   Implement testGetId().
     */
    public function testGetId() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Group::getName
     * @todo   Implement testGetName().
     */
    public function testGetName() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Group::getParentId
     * @todo   Implement testGetParentId().
     */
    public function testGetParentId() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Group::getParentGroup
     * @todo   Implement testGetParentGroup().
     */
    public function testGetParentGroup() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Group::setName
     * @todo   Implement testSetName().
     */
    public function testSetName() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Group::setParentId
     * @todo   Implement testSetParentId().
     */
    public function testSetParentId() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Group::setParentGroup
     * @todo   Implement testSetParentGroup().
     */
    public function testSetParentGroup() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Group::getFetchMode
     * @todo   Implement testGetFetchMode().
     */
    public function testGetFetchMode() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Group::setFetchMode
     * @todo   Implement testSetFetchMode().
     */
    public function testSetFetchMode() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

}
