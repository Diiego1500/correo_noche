<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('category_index');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request,UserPasswordEncoderInterface $passwordEncoder, SluggerInterface $slugger){
        $newUser = new User();
        $registerForm = $this->createForm(UserType::class, $newUser);
        $registerForm->handleRequest($request);
        if($registerForm->isSubmitted() && $registerForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $password = $registerForm->get('password')->getData();
            $newUser->setPassword($passwordEncoder->encodePassword($newUser, $password));
            $photo = $registerForm->get('photo')->getData();
            if($photo){
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Hubo un error con tu foto');
                }
                $newUser->setPhoto($newFilename);
            }


            $em->persist($newUser);
            $em->flush();
            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/register.html.twig', ['registerForm'=>$registerForm->createView()]);
    }


    /**
     * @Route("/register/admin/666", name="app_register_admin")
     */
    public function regiter_admin(Request $request, UserPasswordEncoderInterface $passwordEncoder, SluggerInterface $slugger){
        $newUser = new User();
        $registerForm = $this->createForm(UserType::class, $newUser);
        $registerForm->handleRequest($request);
        if($registerForm->isSubmitted() && $registerForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $password = $registerForm->get('password')->getData();
            $newUser->setPassword($passwordEncoder->encodePassword($newUser, $password));
            $photo = $registerForm->get('photo')->getData();
            if($photo){
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Hubo un error con tu foto');
                }
                $newUser->setPhoto($newFilename);
            }
            $newUser->setRoles(['ROLE_ADMIN']);
            $em->persist($newUser);
            $em->flush();
            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/register.html.twig', ['registerForm'=>$registerForm->createView()]);
    }
}
