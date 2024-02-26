<?php
    namespace App\Controller;

use App\Entity\VinylMix;
use App\Form\VinylMixFormType;
use App\Repository\VinylMixRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

    class MixController extends AbstractController{

        #[Route('/mix/new')]
        public function new(EntityManagerInterface $entityManager):Response{
            $mix = new VinylMix();
            $mix->setTitle('Do you remember...Phil Collins');
            $mix->setDescription('Felipe el colines');
            $genres=['pop','rock'];
            $mix->setGenre($genres[array_rand($genres)]);
            $mix->setTrackCount(rand(5,20));
            $mix->setVotes(rand(-50,50));
            $entityManager->persist($mix);
            $entityManager->flush();
            return new Response(
                sprintf('Mix %d is %d tracks of pure 80\'s heaven', $mix->getId(), $mix->getTrackCount()
            ));
        }
    //     #[Route('/mix/{id}', name:'app_mix_show')]
    // public function show($id, VinylMixRepository $mixRepository){
    //     $mix= $mixRepository->find($id);
    //     if (!$mix) {
    //         throw $this->createNotFoundException('Mix not found');
    //     }
    //     return $this->render('mix/show.html.twig',[
    //         'mix'=>$mix
    //     ]);        
    // }
#[Route('/mix/add', name: 'app_mix_add')]
public function add(Request $request, VinylMixRepository $mixRep): Response
{
$mix = new VinylMix();
$form = $this->createForm(VinylMixFormType::class, new VinylMix());
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
$post = $form->getData();
$mixRep->add($post, true);

$this->addFlash('success', 'Your Vinyl mix has been addded.');
return $this->redirectToRoute('app_homepage');

}
return $this->renderForm(
'mix/add.html.twig',
[
'form' => $form
]
);
}
#[Route('/mix/{post}/edit', name: 'app_mix_edit')]
public function edit(VinylMix $mix, Request $request, VinylMixRepository $mixRep): Response
{
$form = $this->createFormBuilder($mix)
->add('title')
->add('description')
->add('trackCount')
->add('genre')
->add('votes')
->getForm();
$form->handleRequest($request);
if ($form->isSubmitted() && $form->isValid()) {
$post = $form->getData();
$post->add($post, true);
$this->addFlash('success', 'Your vinyl mix has been updated.');
return $this->redirectToRoute('app_homepage');
}
return $this->renderForm(
'micro_post/add.html.twig',
[
'form' => $form
]
);
}
    }
    
?>