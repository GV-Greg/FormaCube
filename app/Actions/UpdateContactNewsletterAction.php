<?php

namespace App\Actions;

use App\Model\Inscrit;
use Illuminate\Http\Request;
use SendinBlue;
use GuzzleHttp;
use SendinBlue\Client\Model\AddContactToList;
use SendinBlue\Client\Model\CreateContact;
use SendinBlue\Client\Model\RemoveContactFromList;
use SendinBlue\Client\Model\UpdateContact;

class UpdateContactNewsletterAction
{
    /**
     * @throws SendinBlue\Client\ApiException
     */
    public function execute(Inscrit $inscrit, Request $request): void
    {
        // config passerelle vers l'API de Send in Blue
        $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', ENV('SENDINBLUE_KEY'));
        $apiInstance = new SendinBlue\Client\Api\ContactsApi(
            new GuzzleHttp\Client(),
            $config
        );
        // ID 30 = liste d'essai pour l'ERP
        $listId = 30;
        $identifier = $inscrit->email;

        $list = $apiInstance->getContactsFromList($listId);
        $verif = false;
        $modifInfos = false;
        for($i=0; $i < count($list['contacts']) ; $i++) {
            if($list['contacts'][$i]['email'] === $identifier) {
                $verif = true;
                // contact avec l'email trouvé, si newsletter est à non, il est supprimé de la liste de diffusion
                if($request->newsletter == false) {
                    $contact = $apiInstance->getContactInfo($identifier);
                    if(count($contact['listIds']) > 1) {
                        $contactIdentifiers = new RemoveContactFromList();
                        $contactIdentifiers['emails'] = array($identifier);
                        $apiInstance->removeContactFromList($listId, $contactIdentifiers);
                    } else {
                        $apiInstance->deleteContact($identifier);
                    }
                }
                // Si changement d'email/prénom/nom
                if($inscrit->email != $request->email || $inscrit->prenom != $request->prenom || $inscrit->nom != $request->nom) {
                    $modifInfos = true;
                }
            }
        }

        // s'il y a pas de contact avec l'email, il est créé dans la liste de diffusion si newsletter est à oui.
        if($verif === false) {
            if($request->newsletter == true) {
                // Récupération de tous les contacts
                $contacts = $apiInstance->getContacts();
                $verifContact = false;

                for($i=0; $i < count($contacts['contacts']) ; $i++) {
                    // Vérification si l'email de la personne est dans les contacts
                    if($contacts['contacts'][$i]['email'] === $identifier) {
                        $verifContact = true;
                    }
                }
                if($verifContact == false) {
                    // Création d'un nouveau contact dans la liste d'envoi de la newsletter
                    $createContact = new CreateContact();
                    $createContact['email'] = $request->email;
                    $createContact['attributes'] = array("PRENOM"=>$request->prenom, "NOM"=>$request->nom);

                    // ID 30 = liste d'essai pour l'ERP
                    $createContact['listIds'] = [$listId];

                    $apiInstance->createContact($createContact);
                } else {
                    $contactIdentifiers = new AddContactToList();
                    $contactIdentifiers['emails'] = array($identifier);
                    $apiInstance->addContactToList($listId, $contactIdentifiers);

                    if($inscrit->email != $request->email || $inscrit->prenom != $request->prenom || $inscrit->nom != $request->nom) {
                        $modifInfos = true;
                    }
                }
            }
        }

        // Si changement d'email/prénom/nom
        if($modifInfos === true) {
            $updateContact = new UpdateContact();
            $updateContact['attributes'] = array('EMAIL' => $request->email, 'PRENOM'=>$request->prenom, 'NOM'=>$request->nom);
            $apiInstance->updateContact($identifier, $updateContact);
        }
    }
}
