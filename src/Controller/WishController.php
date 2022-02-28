<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Entity\WishType;
use App\Form\WishType as FormWishType;
use App\Repository\WishRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class WishController extends AbstractController
{
    /**
     * @Route("/", name="list")
     */
    public function list(WishRepository $repo): Response
    {
        $wishes = $repo->findBy([], ["dateCreated" => "ASC"]);

        return $this->render('wish/list.html.twig', [
            'wishes' => $wishes,
        ]);
    }

    // /**AR GYFER Y FFORM AR Y DUDALEN EI HUN DRWY HTML A BA
    //  * @Route("/add/", name="add")
    //  */
    // public function add(Request $req, EntityManagerInterface $em): Response
    // {
    //     $now = new \DateTime();
    //     $now->setTimezone(new \DateTimeZone('+0100')); //GMT+1

    //     $wish = new Wish();
    //     $wish->setTitle($req->get('title'));
    //     $wish->setAuthor($req->get('author'));
    //     $wish->setDescription($req->get('description'));
    //     $wish->setDateCreated($now);
    //     $wish->setIsPublished(true);

    //     // tell Doctrine you want to (eventually) save the Wish (no queries yet)
    //     $em->persist($wish);

    //     // actually executes the queries (i.e. the INSERT query)
    //     $em->flush();
    //     //rediriger vers home

    //     //($wish->getId());
    //     return $this->redirectToRoute('list');
    // }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(EntityManagerInterface $em, Wish $wish): Response
    {

        $em->remove($wish);
        $em->flush();

        return $this->redirectToRoute('list');
    }
    
    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Wish $wish, Request $req, EntityManagerInterface $em): Response
    {
        // $now = new \DateTime();
        // $now->setTimezone(new \DateTimeZone('+0100')); //GMT+1

        $form = $this->createForm(FormWishType::class,$wish);
        $form->handleRequest($req);

        if ($form->isSubmitted()){
                $em->flush();
        return $this->redirectToRoute('list');
    }

    return $this->render('wish/edit.html.twig',
    ['formulaire'=> $form-> createView()]);

    }

    /**
     * @Route("/details/{id}", name="details")
     */
    public function details(Wish $wish): Response
    {

        return $this->render('wish/details.html.twig', [
            'wish' => $wish
        ]);
    }

      /**
     * @Route("/addclassic/", name="addclassic")
     */
    public function addClassic(Request $req,EntityManagerInterface $em): Response
    {
        
        $now = new \DateTime();
        $now->setTimezone(new \DateTimeZone('+0100')); //GMT+1
      $wish = new Wish(); // je crée un objet
      // creation du form avec asso. avec $wish
      $wish->setDateCreated($now);

      $form =  $this->createForm(FormWishType::class,$wish);
      // auto hydration
      $form->handleRequest($req);
      if ($form->isSubmitted() && $form->isValid())
      {
        $em->persist($wish);
        $em->flush();
        return $this->redirectToRoute('list');
      }
      // on envoie le formulaire à twig
      return $this->render('wish/add.html.twig', [
      'formulaire' => $form->createView(),
    ]);
       
    }

}
