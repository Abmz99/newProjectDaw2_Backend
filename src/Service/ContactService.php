<?php
// src/Service/ContactService.php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ContactService
{
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function getContactText()
    {
        $jsonFilePath = $this->parameterBag->get('kernel.project_dir') . '/config/Contact.json';

        if (file_exists($jsonFilePath)) {
            $jsonData = file_get_contents($jsonFilePath);
            $ContactData = json_decode($jsonData, true);

            // Modify the value of the Contact text as needed
            $ContactData['Contact_text'] = 'Overwritten Contact Us Text';

            // Create a new JSON file named "resContact.json" and write the modified content
            $newJsonFilePath = $this->parameterBag->get('kernel.project_dir') . '/config/resContact.json';
            file_put_contents($newJsonFilePath, json_encode($ContactData));

            return $ContactData['Contact_text'];
        }

        return 'The Contact JSON file was not found.';
    }
}
?>