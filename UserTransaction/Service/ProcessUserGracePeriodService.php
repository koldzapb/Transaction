<?php

namespace UserTransaction\Service;

use DateTime;
use UserTransaction\CommunicationService\UserGracePeriodCommunicationService;

class ProcessUserGracePeriodService
{
    public function processGracePeriod()
    {
        $this->communicate();

        return $this->generateResult();
    }

    protected function communicate()
    {
        $communicationService = new UserGracePeriodCommunicationService();
        $communicationService->userGracePeriodHasStarted();
    }

    protected function generateResult()
    {
        $result = [];
        $result['status'] = 'grace';
        $result['grace_date'] = new DateTime();

        return $result;
    }
}
