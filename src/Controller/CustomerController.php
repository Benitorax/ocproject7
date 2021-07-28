<?php

namespace App\Controller;

use App\Entity\Customer;
use OpenApi\Annotations as OA;
use App\Form\CreateCustomerType;
use App\Service\CustomerManager;
use App\DTO\Customer\ReadCustomer;
use App\DTO\Customer\CreateCustomer;
use App\Security\Voter\CustomerVoter;
use App\Controller\AppAbstractController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AppAbstractController
{
    /**
     * Show customers.
     *
     * @Route(
     *     "/api/customers",
     *     name="api_customer_index",
     *     methods={"GET"}
     * )
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns a paginated list of customer",
     *     @OA\JsonContent(@OA\Schema(
     *       type="array",
     *       @OA\Items(ref=@Model(type=ReadCustomer::class))
     *     ))
     * )
     * @OA\Tag(name="customers")
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
     *     "/api/customers/{id}",
     *     name="api_customer_show",
     *     methods={"GET"}
     * )
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns detailed informations about a customer",
     *     @OA\JsonContent(
     *       ref=@Model(type=ReadCustomer::class)
     *     )
     * )
     * @OA\Parameter(ref="#/components/parameters/id")
     * @OA\Tag(name="customers")
     */
    public function show(string $id, CustomerManager $manager): Response
    {
        $customer = $manager->getOneByIdAndUser((int) $id);

        return $this->json($customer);
    }

    /**
     * Create a customer.
     *
     * @Route(
     *     "/api/customers",
     *     name="api_customer_create",
     *     methods={"POST"}
     * )
     *
     * @OA\Response(
     *     response=200,
     *     description="Return the created customer",
     *     @OA\JsonContent(
     *         ref=@Model(type=ReadCustomer::class)
     *     )
     * )
     * @OA\RequestBody(@OA\JsonContent(
     *       ref=@Model(type=CreateCustomer::class)
     * ))
     * @OA\Tag(name="customers")
     */
    public function create(Request $request, CustomerManager $manager): Response
    {
        $form = $this->createForm(CreateCustomerType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $manager->addNewCustomer($form->getData());

            return $this->json($customer);
        }

        return $this->json($this->getErrorsFromForm($form));
    }

    /**
     * Delete a customer.
     *
     * @Route(
     *     "/api/customers/{id}",
     *     name="api_customer_delete",
     *     methods={"DELETE"}
     * )
     *
     * @OA\Response(
     *     response=200,
     *     description="Return the delete customer",
     *     @OA\JsonContent(
     *       ref=@Model(type=ReadCustomer::class)
     *     )
     * )
     * @OA\Parameter(ref="#/components/parameters/id")
     * @OA\Tag(name="customers")
     */
    public function delete(Customer $customer, Request $request, CustomerManager $manager): Response
    {
        $this->denyAccessUnlessGranted(CustomerVoter::DELETE, $customer);
        $manager->delete($customer);

        return $this->json($customer);
    }
}
