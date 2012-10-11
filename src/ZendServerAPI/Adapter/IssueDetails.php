<?php
namespace ZendServerAPI\Adapter;

class IssueDetails extends Adapter
{
    /**
     * @see \ZendServerAPI\Adapter\Adapter::parse()
     */
    public function parse ($xml = null)
    {
        if($xml === null)
            $xml = $this->getResponse()->getBody();

        $xml = simplexml_load_string($xml);

        $issueDetails = new \ZendServerAPI\DataTypes\IssueDetails();

        $issue = new \ZendServerAPI\DataTypes\Issue();
        $issue->setId((string) $xml->responseData->issueDetails->issue->id);
        $issue->setRule((string) $xml->responseData->issueDetails->issue->rule);
        $issue->setCount((string) $xml->responseData->issueDetails->issue->count);
        $issue->setLastOccurance((string) $xml->responseData->issueDetails->issue->lastOccurance);
        $issue->setSeverity((string) $xml->responseData->issueDetails->issue->severity);
        $issue->setStatus((string) $xml->responseData->issueDetails->issue->status);

        $generalDetails = new \ZendServerAPI\DataTypes\GeneralDetails();
        $generalDetails->setUrl((string) $xml->responseData->issueDetails->issue->generalDetails->url);
        $generalDetails->setSourceFile((string) $xml->responseData->issueDetails->issue->generalDetails->sourceFile);
        $generalDetails->setSourceLine((string) $xml->responseData->issueDetails->issue->generalDetails->sourceLine);
        $generalDetails->setFunction((string) $xml->responseData->issueDetails->issue->generalDetails->function);
        $generalDetails->setAggregationHint((string) $xml->responseData->issueDetails->issue->generalDetails->aggregationHint);
        $generalDetails->setErrorString((string) $xml->responseData->issueDetails->issue->generalDetails->errorString);
        $generalDetails->setErrorType((string) $xml->responseData->issueDetails->issue->generalDetails->errorType);
        $issue->setGeneralDetails($generalDetails);

        if (isset($xml->responseData->issueDetails->issue->routeDetails->routeDetail)) {
            foreach ($xml->responseData->issueDetails->issue->routeDetails->routeDetail as $xmlRouteDetail) {
                $routeDetails = new \ZendServerAPI\DataTypes\RouteDetail();
                $routeDetails->setKey($xmlRouteDetail->key);
                $routeDetails->setValue($xmlRouteDetail->value);
                $issue->addRouteDetails($routeDetails);
            }
        }
        $issueDetails->setIssue($issue);

        if (isset($xml->responseData->issueDetails->eventsGroups->eventsGroup)) {
            foreach ($xml->responseData->issueDetails->eventsGroups->eventsGroup as $xmlEventsGroup) {
                $eventsGroup = new \ZendServerAPI\DataTypes\EventsGroup();
                $eventsGroup->setEventsGroupId((string) $xmlEventsGroup->eventsGroupId);
                $eventsGroup->setEventsCount((string) $xmlEventsGroup->eventsCount);
                $eventsGroup->setStartTime((string) $xmlEventsGroup->startTime);
                $eventsGroup->setServerId((string) $xmlEventsGroup->serverId);
                $eventsGroup->setClass((string) $xmlEventsGroup->class);
                $eventsGroup->setUserData((string) $xmlEventsGroup->userData);
                $eventsGroup->setJavaBacktrace((string) $xmlEventsGroup->javaBacktrace);
                $eventsGroup->setExecTime((string) $xmlEventsGroup->execTime);
                $eventsGroup->setAvgExecTime((string) $xmlEventsGroup->avgExecTime);
                $eventsGroup->setMemUsage((string) $xmlEventsGroup->memUsage);
                $eventsGroup->setAvgMemUsage((string) $xmlEventsGroup->avgMemUsage);
                $eventsGroup->setAvgOutputSize((string) $xmlEventsGroup->avgOutputSize);
                $eventsGroup->setLoad((string) $xmlEventsGroup->load);

                $issueDetails->addEventsGroup($eventsGroup);
            }
        }

        return $issueDetails;
    }
}