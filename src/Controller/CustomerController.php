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
use Symfony\Component\Form\FormInterface;
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

        return $this->json($customers);
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
     * @OA\Response(response=403, description="Access denied.")
     * @OA\Response(response=404, description="Customer not found.")
     * @OA\Parameter(ref="#/components/parameters/id")
     * @OA\Tag(name="customers")
     */
    public function show(Customer $customer, CustomerManager $manager): Response
    {
        $this->denyAccessUnlessGranted(CustomerVoter::VIEW, $customer);

        $customer = $manager->getReadCustomer($customer);

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
     * @OA\Response(response=403, description="Access denied.")
     * @OA\Response(response=404, description="Customer not found.")
     * @OA\Response(response=422, description="Return error message for each field")
     * @OA\RequestBody(@OA\JsonContent(
     *       ref=@Model(type=CreateCustomer::class)
     * ))
     * @OA\Tag(name="customers")
     */
    public function create(Request $request, CustomerManager $manager): Response
    {
        $form = $this->createForm(CreateCustomerType::class);
        $data = json_decode((string) $request->getContent(), true);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $manager->addNewCustomer($form->getData());

            return $this->json($customer);
        }

        return $this->json($this->getErrorsFromForm($form), 422);
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
     *     description="Return the deleted customer",
     *     @OA\JsonContent(
     *       ref=@Model(type=ReadCustomer::class)
     *     )
     * )
     * @OA\Response(response=403, description="Access denied.")
     * @OA\Response(response=404, description="Customer not found.")
     * @OA\Parameter(ref="#/components/parameters/id")
     * @OA\Tag(name="customers")
     */
    public function delete(Customer $customer, CustomerManager $manager): Response
    {
        $this->denyAccessUnlessGranted(CustomerVoter::DELETE, $customer);
        $customer = $manager->delete($customer);

        return $this->json($customer);
    }
}
