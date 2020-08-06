<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Service\CustomerService;

class BdController extends AbstractController
{
    /**
     * @Route("/bd/dump-sql", name="bd-sql")
     */
    public function dump(KernelInterface $kernel)
    {
        $app = new Application($kernel);
        $app->setAutoExit(false);
        $input = new ArrayInput([
            'command' => 'doctrine:schema:update', '--dump-sql'=> true,
        ]);
        
        $output = new BufferedOutput();
        $app->run($input,$output);
        
        $content = $output->fetch();

        return new Response($content);
    }

    /**
     * @Route("/bd/force", name="bd-force")
     */
    public function force(KernelInterface $kernel)
    {
        $app = new Application($kernel);
        $app->setAutoExit(false);
        $input = new ArrayInput([
            'command' => 'doctrine:schema:update', '--force'=> true,
        ]);
        
        $output = new BufferedOutput();
        $app->run($input,$output);
        
        $content = $output->fetch();

        return new Response($content);
    }

    /**
     * @Route("/te", name="bd-rce")
     */
    public function tested(CustomerService $customerService)
    {
        $refreshCustomer = $customerService->refreshCustomerCategory();
        return new Response($refreshCustomer);
    }
}