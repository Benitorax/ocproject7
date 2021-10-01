<?php

namespace App\Controller;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppAbstractController extends AbstractController
{
    protected JsonResponse $response;
    /**
     * Return a JsonResponse object with Etag
     */
    public function isResponseNotModified(string $etag, Request $request): bool
    {
        return (new JsonResponse())->setEtag($etag)->isNotModified($request);
    }

    /**
     * Return a JsonResponse object with Etag
     */
    public function jsonResponseWithEtag(string $content, ?string $etag): JsonResponse
    {
        return (new JsonResponse($content, 200, [], true))
            ->setEtag($etag)
            ->setPublic() // make sure the response is public/cacheable
        ;
    }

    /**
     * Return the etag of an entity.
     */
    public function getEntityEtag(object $entity): string
    {
        return md5(serialize($entity));
    }

    /**
     * Return an array of errors from Form object.
     */
    protected function getErrorsFromForm(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->getErrors() as $error) {
            /** @var FormError $error */
            $message = $error->getMessage();

            if (array_key_exists('{{ extra_fields }}', $error->getMessageParameters())) {
                $message = substr($message, 0, -1);
                $message .= ': ' . $error->getMessageParameters()['{{ extra_fields }}'] . '.';
            }

            $errors[] = $message;
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
