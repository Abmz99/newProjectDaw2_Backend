<?php
// src/Service/AboutService.php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AboutService
{
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function getAboutText()
    {
        $jsonFilePath = $this->parameterBag->get('kernel.project_dir') . '/config/about.json';

        if (file_exists($jsonFilePath)) {
            $jsonData = file_get_contents($jsonFilePath);
            $aboutData = json_decode($jsonData, true);

            // Modify the value of the About text as needed
            $aboutData['about_text'] = 'Overwritten About Us Text';

            // Create a new JSON file named "resAbout.json" and write the modified content
            $newJsonFilePath = $this->parameterBag->get('kernel.project_dir') . '/config/resAbout.json';
            file_put_contents($newJsonFilePath, json_encode($aboutData));

            return $aboutData['about_text'];
        }

        return 'The About JSON file was not found.';
    }
}
?>