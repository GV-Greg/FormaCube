<?php

namespace App\Http\Controllers;

use SendinBlue;
use GuzzleHttp;

class SendInBlueController extends Controller
{
    /**
     * @return SendinBlue\Client\Model\GetContacts
     * @throws SendinBlue\Client\ApiException
     */
    public function contacts() {
        // config passerelle vers l'API de Send in Blue
        $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', ENV('SENDINBLUE_KEY'));
        $apiInstance = new SendinBlue\Client\Api\ContactsApi(
            new GuzzleHttp\Client(),
            $config
        );

        $limit = 10;
        // ID 30 = liste d'essai pour l'ERP
        $listId = 30;

        // Récupération des contacts de la liste
        return $apiInstance->getContactsFromList($listId, null, $limit);
    }
}
