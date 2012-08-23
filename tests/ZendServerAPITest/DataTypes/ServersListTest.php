<?php

use ZendServerAPI\DataTypes\ServerInfo;

use ZendServerAPI\DataTypes\ServersList;

/**
 *  test case.
 */
class ServersListTest extends PHPUnit_Framework_TestCase
{

    public function testGettersAndSetters ()
    {
        $serversList = new ServersList();
        $this->assertEquals(array(), $serversList->getServerInfos());
        
        $serverInfo = new ServerInfo();
        $serversList->addServerInfo($serverInfo);
        $this->assertEquals(array($serverInfo), $serversList->getServerInfos());
        
        $serversList->setServerInfos(array($serverInfo, $serverInfo));
        $this->assertEquals($serversList->getServerInfos(), array($serverInfo, $serverInfo));
        
    }
    
    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testSetterError()
    {
        $serversList = new ServersList();
        $serversList->setServerInfos(new stdclass);
    }
}
