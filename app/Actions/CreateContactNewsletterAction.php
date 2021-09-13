<?php

namespace App\Actions;

use SendinBlue;
use GuzzleHttp;
use Illuminate\Http\Request;
use SendinBlue\Client\Model\AddContactToList;
use SendinBlue\Client\Model\CreateContact;
use SendinBlue\Client\Model\RemoveContactFromList;
use SendinBlue\Client\Model\UpdateContact;

class CreateContactNewsletterAction {

    /**
     * @throws SendinBlue\Client\ApiException
     */
    public function execute(Request $request): void
    {
        // config passerelle vers l'API de Send in Blue
        $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', ENV('SENDINBLUE_KEY'));
        $apiInstance = new SendinBlue\Client\Api\ContactsApi(
            new GuzzleHttp\Client(),
            $config
        );

        // ID 30 = liste d'essai pour l'ERP
        $listId = 30;

        // Récupération de tous les contacts
        $contacts = $apiInstance->getContacts();
        $verifContact = false;
        $verifList = false;

        for($i=0; $i < count($contacts['contacts']) ; $i++) {
            // Vérification si l'email de la personne est dans les contacts
            if($contacts['contacts'][$i]['email'] === $request->email) {
                // Si c'est le cas, vérification si l'email de la personne est dans la liste
                if(in_array($listId, $contacts['contacts'][$i]['listIds'])) {
                    $verifList = true;
                }
                $verifContact = true;
            }
        }

        if($verifContact === true && $verifList === false) {
            // s'il y a pas de contact avec l'email, vérification si le contact est dans la liste de diffusion
            $contactIdentifiers = new AddContactToList();
            $contactIdentifiers['emails'] = array($request->email);
            $apiInstance->addContactToList($listId, $contactIdentifiers);
        } elseif($verifContact === false && $verifList === false) {
            // s'il y a pas de contact avec l'email, création d'un nouveau contact + ajout dans la liste de diffusion
            $createContact = new CreateContact();
            $createContact['email'] = $request->email;
            $createContact['attributes'] = array("PRENOM"=>$request->prenom, "NOM"=>$request->nom);
            $createContact['listIds'] = [$listId];
            $apiInstance->createContact($createContact);
        }
    }
}
