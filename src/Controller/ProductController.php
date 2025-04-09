<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Product;
use App\Entity\QrCodeImage;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product')]
final class ProductController extends AbstractController
{
    private ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    #[Route(name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('admin/product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Builder $builder): Response
    {
        //https://github.com/endroid/qr-code-bundle
        //https://www.binaryboxtuts.com/php-tutorials/symfony-tutorials/symfony-5-qr-code-generator-tutorial/
        //https://github.com/endroid/qr-code
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $writer = new PngWriter();
            $entityManager->persist($product);
            $entityManager->flush();
            $qrCode = new QrCode(data: $product->getId());
            $qrCodeImage = $writer->write($qrCode);

            $fileName = 'product_qrcode_' . $product->getId() . '.png';
            $filePath = $this->getParameter('kernel.project_dir') . '/public/images/qrcodes/' . $fileName;


            if (!file_exists(dirname($filePath))) {
                mkdir(dirname($filePath), 0777, true);
            }
            file_put_contents($filePath, $qrCodeImage->getString());


            $image = new QrCodeImage();
            $image->setImageFile(new File($filePath)); // VichUploader gérera le déplacement
            $image->setImageName($fileName);
            $entityManager->persist($image);
            $product->setQrCodeImage($image);

            $entityManager->persist($product);
            $entityManager->flush();

            if ($product->getImage()) $product->setImageUrl($this->imageService->getImageUrl($product->getImage(), "product"));
            if ($product->getQrCodeImage()) $product->setQrCodeUrl($this->imageService->getImageUrl($product->getQrCodeImage(), "qrcode"));

            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('admin/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->getPayload()->getString('_token'))) {
         try {
             unlink($this->getParameter('kernel.project_dir') . '/public/images/qrcodes/' . $product->getQrCodeImage()->getImageName());
         }catch (e){}
           $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/uploadqrcode', name: 'app_upload_qrcode', methods: ['GET'])]
    public function upload(Product $product, Pdf $knpSnappyPdf): \Symfony\Component\HttpFoundation\RedirectResponse | PdfResponse
    {
        if (!$product->getQrCodeImage()){
            return  $this->redirectToRoute("app_product_show",["id"=>$product->getId()]);
        }
        $html = "<html><body style='margin:0'><img src='" . $product->getQrCodeUrl() . "' style='width:100%;height:auto'/></body></html>";

        return new PdfResponse(
            $knpSnappyPdf->generateFromHtml($html),
            'file.pdf'
        );
    }
}
