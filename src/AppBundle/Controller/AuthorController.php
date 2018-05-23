<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Entity\Author;

class AuthorController extends Controller
{
  /**
  *@Route("/authors", name="authors_home")
  */
  public function list(){
    if(isset($_GET['sort'])){
    $arr = explode("|",$_GET['sort']);
     if(isset($arr[1])){
        $sort['field'] = "name";
        $sort['order'] = $arr[1];
      }else{
        // Default Sort
        $sort['field']='name';
        $sort['order']=1;
      } 
    }else{
      // Default Sort
      $sort['field']='name';
      $sort['order']=1;
    }
    $authors = $this->getDoctrine()
      ->getRepository('AppBundle:Author')
      ->findBy([], [$sort['field']=>$sort['order'] == 1 ? "ASC" : "DESC"]);
    return $this->render('authors/authors.html.twig',[
      'authors'=>$authors,
      'sort'=>$sort
    ]);
  }
  /**
  *@Route("/authors/create", name="authors_create")
  */
  public function create(Request $request){
    $author = new Author;

    $form = $this->createFormBuilder($author)
      ->add('name', TextType::Class, ['attr'=>['class'=>'input-field', 'placeholder'=>'John Doe', 'minlength'=>'4']])
      ->add('description', TextareaType::Class, ['attr'=>['class'=>'materialize-textarea', 'placeholder'=>'Write few words about the author', 'minlength'=>'4']])
      ->add('create', SubmitType::Class, ['attr'=>['class'=>'waves-effect waves-light btn'], 'label'=>'Create Author'])
      ->getForm();

      $form->handleRequest($request);
        if ($form->isSubmitted()) {
          $form = $form->getData();

          $name = $form->getName();
          $desc = $form->getDescription();

          // Insert
          $em = $this->getDoctrine()->getManager();
          $insert = new Author();
          $insert->setName($name);
          $insert->setDescription($desc);
          $em->persist($insert);
          $em->flush();

          return $this->redirect($this->generateUrl("authors_home"));
        }

      return $this->render('authors/create.html.twig',[
        'form'=> $form->createView()
    ]);
  }

  /**
   * @Route("/authors/update/{id}", name="authors_update")
   */

  public function update($id, Request $request){
    $authorE = new Author();
    $author = $this->getDoctrine()
        ->getRepository('AppBundle:Author')
        ->find($id);

    if (!$author) {
        return $this->redirect($this->generateUrl('author_home'));
    }

    $form = $this->createFormBuilder($authorE)
    ->add('name', TextType::Class, ['data'=>$author->getName(),'attr'=>['class'=>'input-field', 'placeholder'=>'title', 'minlength'=>'4']])
    ->add('description', TextareaType::Class, [ 'data'=>$author->getDescription(),'attr'=>['class'=>'materialize-textarea', 'placeholder'=>'1933', 'min'=>'0','max'=>date("Y")]])
    ->add('create', SubmitType::Class, ['attr'=>['class'=>'waves-effect waves-light btn'], 'label'=>'Update Author'])
    ->getForm();
      
    $form->handleRequest($request);
    if ($form->isSubmitted()) {
      $form = $form->getData();

      $name = $form->getName();
      $desp = $form->getDescription();

      // Update
      $em = $this->getDoctrine()->getManager();
      $author->setName($name);
      $author->setDescription($desp);
        
      $em->flush();

      return $this->redirect($this->generateUrl("authors_home"));
    }

    return $this->render('authors/edit.html.twig',[
        'form'=>$form->createView(),
        'authName'=>$author->getName()
    ]);
   }

  /**
  * @Route("/authors/delete/{id}", name="authors_delete")
  */
  public function delete($id, Request $request){
    $em = $this->getDoctrine()->getManager(); 
    $author = $this->getDoctrine()
      ->getRepository('AppBundle:Author')
      ->find($id);
    if($author){
      $em->remove($author);
      $em->flush();
    }
    return $this->redirect($this->generateUrl("authors_home"));
  }

}
