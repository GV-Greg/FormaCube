<?php

namespace App\Actions;

use SendinBlue;
use GuzzleHttp;
use SendinBlue\Client\Model\RemoveContactFromList;

class DeleteContactNewsletterAction {

    public function execute(string $email)
    {
        // config passerelle vers l'API de Send in Blue
        $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', ENV('SENDINBLUE_KEY'));
        $apiInstance = new SendinBlue\Client\Api\ContactsApi(
            new GuzzleHttp\Client(),
            $config
        );

        $listId = 30;
        $identifier = $email;
        $contact = $apiInstance->getContactInfo($identifier);
        if(count($contact['listIds']) > 1) {
            $contactIdentifiers = new RemoveContactFromList();
            $contactIdentifiers['emails'] = array($email);
            $apiInstance->removeContactFromList($listId, $contactIdentifiers);
        } else {
            $apiInstance->deleteContact($identifier);
        }
    }
}
