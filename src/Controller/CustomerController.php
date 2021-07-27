<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Customer;
use App\Form\CustomerType;
use App\Service\CustomerManager;
use App\Security\Voter\CustomerVoter;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomerController extends AbstractController
{
    /**
     * Show a paginated list of customers.
     *
     * @Route(
     *     "/api/customer",
     *     name="api_customer_index",
     *     methods={"GET"}
     * )
     */
    public function index(Request $request, CustomerManager $manager): Response
    {
        $customers = $manager->getPaginatedCustomers(
            (int) $request->query->get('page') ?: 1
        );

        return $this->json([
            $customers,
            '_links' => 'Welcome to your new controller!'
        ]);
    }

    /**
     * Show a customer.
     *
     * @Route(
     *     "/api/customer/{id}",
     *     name="api_customer_show",
     *     methods={"GET"}
     * )
     */
    public function show(string $id, Request $request, CustomerManager $manager): Response
    {
        $customer = $manager->getOneByIdAndUser((int) $id);

        return $this->json($customer);
    }

    /**
     * Create a customer.
     *
     * @Route(
     *     "/api/customer",
     *     name="api_customer_create",
     *     methods={"POST"}
     * )
     */
    public function create(Request $request, CustomerManager $manager): Response
    {
        $form = $this->createForm(CustomerType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $manager->addNewCustomer($form->getData());

            return $this->json($customer);
        }
        dd($form);
        return $this->json($this->getErrorsFromForm($form));
    }

    /**
     * Delete a customer.
     *
     * @Route(
     *     "/api/customer/{id}",
     *     name="api_customer_delete",
     *     methods={"DELETE"}
     * )
     */
    public function delete(Customer $customer, Request $request, CustomerManager $manager): Response
    {
        $this->denyAccessUnlessGranted(CustomerVoter::DELETE, $customer);
        $manager->delete($customer);

        return $this->json([]);
    }

    /**
     * Return an array of errors from Form object.
     */
    private function getErrorsFromForm(FormInterface $form): array
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
