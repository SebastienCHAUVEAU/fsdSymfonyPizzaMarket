<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Form\SearchProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product2')]
class Product2Controller extends AbstractController
{

    #[Route('/debug', name: 'app_product2_debug', methods: ['GET'])]
    public function debug(ProductRepository $productRepository): Response
    {
        var_dump(php_ini_loaded_file(), php_ini_scanned_files());
        phpinfo();

        
        return $this->render('base.html.twig', [
        //     'products' => $productRepository->findAll(),
        //     'minPrice' => $productRepository->findBy([],["price" => "ASC"],1),
        //     'maxPrice' => $productRepository->findBy([],["price" => "DESC"],1),
         ]);
    }

    #[Route('/', name: 'app_product2_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
       
        $form=$this->createForm(SearchProductType::class);
        
        return $this->render('product2/index.html.twig', [
            'products' => $productRepository->findAll(),
            'minPrice' => $productRepository->findBy([],["price" => "ASC"],1),
            'maxPrice' => $productRepository->findBy([],["price" => "DESC"],1),
            'form' => $form
        ]);
    }

    #[Route('/filter', name: 'app_product2_index_sorting', methods: ['GET'])]
    public function indexASC(ProductRepository $productRepository, Request $request): Response
    {
      $sortingType = $request->get("productSortingSelect");
        //dd($sortingType);
        if($sortingType == "ascName"){
            return $this->render('product2/index.html.twig', [
                'products' => $productRepository->findBy([],["name"=>"ASC"]),
                'minPrice' => $productRepository->findBy([],["price" => "ASC"],1),
            'maxPrice' => $productRepository->findBy([],["price" => "DESC"],1),
            ]);
        }

        if($sortingType == "descName"){
            return $this->render('product2/index.html.twig', [
                'products' => $productRepository->findBy([],["name"=>"DESC"]),
                'minPrice' => $productRepository->findBy([],["price" => "ASC"],1),
            'maxPrice' => $productRepository->findBy([],["price" => "DESC"],1),
            ]);
        }

        if($sortingType == "ascPrice"){
            return $this->render('product2/index.html.twig', [
                'products' => $productRepository->findBy([],["price"=>"ASC"]),
                'minPrice' => $productRepository->findBy([],["price" => "ASC"],1),
            'maxPrice' => $productRepository->findBy([],["price" => "DESC"],1),
            ]);
        }

        if($sortingType == "descPrice"){
            return $this->render('product2/index.html.twig', [
                'products' => $productRepository->findBy([],["price"=>"DESC"]),
                'minPrice' => $productRepository->findBy([],["price" => "ASC"],1),
            'maxPrice' => $productRepository->findBy([],["price" => "DESC"],1),
            ]);
        }

    

        
    }

    #[Route('/new', name: 'app_product2_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->save($product, true);

            return $this->redirectToRoute('app_product2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product2/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product2_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product2/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product2_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->save($product, true);

            return $this->redirectToRoute('app_product2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product2/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product2_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('app_product2_index', [], Response::HTTP_SEE_OTHER);
    }
}
