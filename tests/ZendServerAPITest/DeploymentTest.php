<?php
namespace ZendServerAPITest;

use \ZendService\ZendServerAPI\Request;

use \ZendService\ZendServerAPI\Deployment;

class DeploymentTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp ()
    {
        
    }
    
    public function testConstructorInjection()
    {
        $deployment = new Deployment("example62");
        $config = $deployment->getRequest()->getConfig();
        $request = new Request();
        $request->setConfig($config);
        $deployment->setRequest($request);
        
        $this->assertSame($request, $deployment->getRequest());
        $this->assertInstanceOf('\ZendService\ZendServerAPI\Config', $deployment->getRequest()->getConfig());
        $this->assertInstanceOf('\ZendService\ZendServerAPI\ApiKey', $deployment->getRequest()->getConfig()->getApiKey());
    }
    
    public function testApplicationGetStatus()
    {
        $responseStub = $this->getMock('\Zend\Http\Response', array('getBody'));
        require_once 'ZendServerAPITest/Method/ApplicationGetStatusTest.php';
        $responseStub->expects($this->once())->method('getBody')->will($this->returnValue(ApplicationGetStatusTest::getResponse()));
        
        $clientStub = $this->getMock('\Zend\Http\Client', array('send'));
        $clientStub->expects($this->once())->method('send')->will($this->returnValue($responseStub));
        
        $deployment = new \ZendService\ZendServerAPI\Deployment();
        $deployment->setClient($clientStub);
        $result = $deployment->applicationGetStatus(array(1,2));
        $this->assertInstanceOf('\ZendService\ZendServerAPI\DataTypes\ApplicationList', $result);
    }

    public function testApplicationGetStatusAgainstProduction()
    {
        if(DISABLE_REAL_INTERFACE === true)
            $this->markTestSkipped();
        
        try {
            $deployment = new \ZendService\ZendServerAPI\Deployment("example62");
            $deploymentStatus = $deployment->applicationGetStatus();
        } catch(\Exception $e) {
            $this->markTestSkipped();
        }
        
        $this->assertInstanceOf('\ZendService\ZendServerAPI\DataTypes\ApplicationList', $deploymentStatus);
    }
    
    public function testApplicationDeploy()
    {
        if(DISABLE_REAL_INTERFACE === true)
            $this->markTestSkipped();
        $deployment = new \ZendService\ZendServerAPI\Deployment("example62");
        if(!$deployment->canConnect())
            $this->markTestSkipped();
        
        $apps = $deployment->applicationGetStatus();
        foreach($apps->getApplicationInfos() as $applicationInfo)
        {
            $deployment->applicationRemove($applicationInfo->getId());
        }
        
        $deploy = $deployment->applicationDeploy(
                __DIR__.'/../_files/example2.zpk', 
                "http://test2.com", 
                true, 
                false, 
                'Simple test app', 
                false, 
                array(
                    'locale' => 'GMT',
                    'db_host' => 'localhost'        
                )
        );
        $deployment->waitForStableState($deploy->getId());
        $this->assertInstanceOf('\ZendService\ZendServerAPI\DataTypes\ApplicationInfo', $deploy);
        $deployment->applicationRemove($deploy->getId());
        $deployment->waitForRemoved($deploy->getId());
            
    }
    
    public function testWaitForMethods()
    {
        if(DISABLE_REAL_INTERFACE === true)
            $this->markTestSkipped();
        $deployment = new \ZendService\ZendServerAPI\Deployment("example62");
        if(!$deployment->canConnect())
            $this->markTestSkipped();
        
        $deploy = $deployment->applicationDeploy(
                __DIR__.'/../_files/example2.zpk',
                "http://testwait.com",
                true,
                false,
                'Simple test app for waiting function',
                false,
                array(
                        'locale' => 'GMT',
                        'db_host' => 'localhost'
                )
        );
        $result = $deployment->waitForStableState($deploy->getId());
        
        $this->assertEquals("deployed", $result->getStatus());
        $deployment->applicationRemove($result->getId());
        $retVal = $deployment->waitForRemoved($result->getId());
        $this->assertTrue($retVal);
    }
    
    public function testUpdateAndRollback()
    {
        if(DISABLE_REAL_INTERFACE === true)
            $this->markTestSkipped();
        $deployment = new \ZendService\ZendServerAPI\Deployment("example62");
        if(!$deployment->canConnect())
            $this->markTestSkipped();
        
        $deploy = $deployment->applicationDeploy(
                __DIR__.'/../_files/example1.zpk',
                "http://test.com",
                true,
                false,
                'Simple test app',
                false,
                array(
                        'locale' => 'GMT',
                        'db_host' => 'localhost'
                )
        );
        $result = $deployment->waitForStableState($deploy->getId());
        
        $app = $deployment->applicationUpdate($result->getId(), __DIR__.'/../_files/example1-2.zpk');
        $app = $deployment->waitForStableState($app->getId());
        
        $deployedVersions = $app->getDeployedVersions();
        $deployedVersion = new \ZendService\ZendServerAPI\DataTypes\DeployedVersions();
        $deployedVersion->setVersion("0.2");
        $this->assertEquals($deployedVersions[0], $deployedVersion);
        
        $app = $deployment->applicationRollback($app->getId());
        $app = $deployment->waitForStableState($app->getId());
        
        $deployedVersions = $app->getDeployedVersions();
        $deployedVersion = new \ZendService\ZendServerAPI\DataTypes\DeployedVersions();
        $deployedVersion->setVersion("0.1");
        $this->assertEquals($deployedVersions[0], $deployedVersion);
        
    }
    
    public function testSynchronize()
    {
        if(DISABLE_REAL_INTERFACE === true)
            $this->markTestSkipped();
        $deployment = new \ZendService\ZendServerAPI\Deployment("example62");
        if(!$deployment->canConnect())
            $this->markTestSkipped();
        
        $applications = $deployment->applicationGetStatus();
        $applicationList = $applications->getApplicationInfos();
        $applicationInfo = $applicationList[0];
        $applicationInfo = $deployment->applicationSynchronize($applicationInfo->getId(), array(0));
        $this->assertInstanceOf('\ZendService\ZendServerAPI\DataTypes\ApplicationInfo', $applicationInfo);
        $this->assertNotEquals('deployed', $applicationInfo->getStatus());
    }
}

