<?php

namespace App\Controller;

use OpenApi\Annotations as OA;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppAbstractController extends AbstractController
{
    /**
     * Return an array of errors from Form object.
     */
    protected function getErrorsFromForm(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->getErrors() as $error) {
            /** @var FormError $error */
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }
}
