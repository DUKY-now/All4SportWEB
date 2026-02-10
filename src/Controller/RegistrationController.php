<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Client;
use App\Form\RegistrationFormType;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new Client();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // Encoder le mot de passe
            $user->setMotDePasse($userPasswordHasher->hashPassword($user, $plainPassword));

            // Créer l'adresse à partir des données du formulaire
            $adresse = new Adresse();
            $adresse->setRue($form->get('rue')->getData());
            $adresse->setCodePostal($form->get('code_postal')->getData());
            $adresse->setVille($form->get('ville')->getData());
            $adresse->setPays($form->get('pays')->getData());
            $adresse->setComplement($form->get('complement')->getData());

            // Persister l'adresse d'abord
            $entityManager->persist($adresse);

            // Associer l'adresse au client
            $user->setAdresse($adresse);

            // Persister le client
            $entityManager->persist($user);
            $entityManager->flush();

            // Authentifier automatiquement l'utilisateur
            return $security->login($user, LoginAuthenticator::class, 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
