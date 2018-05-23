<?php 
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Valid;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Entity\Books;
use AppBundle\Entity\Auhtor;

class BooksController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function list(Request $request)
    {
        if(isset($_GET['sort'])){
            $arr = explode("|",$_GET['sort']);
            if(isset($arr[1])){
                $sort['field'] = $arr[0];
                $sort['order'] = $arr[1];
            }else{
                // Default Sort
                $sort['field']='title';
                $sort['order']=1;
            } 
        }else{
            // Default Sort
            $sort['field']='title';
            $sort['order']=1;
        }

        $query = $this->getDoctrine()->getManager ()
        ->createQueryBuilder('b')
        ->add('select', 'b, a')
        ->from('AppBundle:Books', 'b')
        ->leftJoin('AppBundle:Author', 'a', 'WITH', 'a.id=b.author')
        ->where('b.author=a.id')
        ->groupby('b.id')
        ->getQuery()
        ->getResult();
        
        $books = [];
        foreach($query as $k=>$item){
            // If is book Entity
            if(get_class($item) == "AppBundle\Entity\Books"){
                $book = new Books();
                $book->_id = $item->getId();
                $book->setTitle($item->getTitle());
                $book->setYear($item->getYear());
                $book->setType($item->getType());
                //set Auhtor
                $au = $item->getAuthor();
                foreach($query as $k=>$a){
                    if(get_class($a) == "AppBundle\Entity\Author" && $a->getId() == $au){
                        $book->setAuthor($a->getName());
                    }
                }
                // $book['id'] = $item->getId();
                $books[] = $book;

            } 
        }
        
        return $this->render('books/books.html.twig',[
            'books'=>$books,
            'sort'=>$sort
        ]);
    }

    /**
     * @Route("/books/create", name="books_create")
     */

    public function create(Request $request)
    {   
        $book = new Books;

        $authors = $this->getDoctrine()
            ->getRepository('AppBundle:Author')
            ->findAll();
        $selections['Select Author'] = '0';
        $errors=[];
        foreach($authors as $author){
            $selections[$author->getName()] = $author->getId();
        }

        $form = $this->createFormBuilder($book)
        ->add('title', TextType::Class, ['attr'=>['class'=>'input-field', 'placeholder'=>'title', 'minlength'=>'4']])
        ->add('author', ChoiceType::Class, [
            'attr'=>['class'=>'input-field', 'placeholder'=>'Select Author', 'minlength'=>'5', 'pattern'=>'/0/'],
            'choices'=>$selections
        ])
        ->add('year', NumberType::Class, ['attr'=>['class'=>'input-field', 'placeholder'=>'1933', 'min'=>'0','max'=>date("Y")]])
        ->add('type', TextType::Class, ['attr'=>['class'=>'input-field', 'placeholder'=>'type']])
        ->add('create', SubmitType::Class, ['attr'=>['class'=>'waves-effect waves-light btn'], 'label'=>'Create Book'])
        ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $form = $form->getData();

            $title = $form->getTitle();
            $year = $form->getYear();
            $author = $form->getAuthor();
            $type = $form->getType();

            // Insert
            $em = $this->getDoctrine()->getManager();
            $insert = new Books();
            $insert->setTitle($title);
            $insert->setYear($year);
            $insert->setType($type);
            $insert->setAuthor($author);
            $em->persist($insert);
            $em->flush();

            return $this->redirect($this->generateUrl("home"));
        
        }

        return $this->render('books/create.html.twig',[
            'form'=> $form->createView()
        ]);
    }

    /**
     * @Route("/books/update/{id}", name="book_update")
     */

     public function update($id, Request $request){
        $bookE = new Books;
        $book = $this->getDoctrine()
            ->getRepository('AppBundle:Books')
            ->find($id);

        if (!$book) {
            return $this->redirect($this->generateUrl('home'));
        }
        $authors = $this->getDoctrine()
            ->getRepository('AppBundle:Author')
            ->findAll();
        $selections['Select Author'] = '0';
        $errors=[];
        foreach($authors as $author){
            $selections[$author->getName()] = $author->getId();
        }

        $form = $this->createFormBuilder($book)
        ->add('title', TextType::Class, ['data'=>$book->getTitle(),'attr'=>['class'=>'input-field', 'placeholder'=>'title', 'minlength'=>'4']])
        ->add('author', ChoiceType::Class, [
            'data'=>$book->getAuthor(),
            'attr'=>['class'=>'input-field', 'placeholder'=>'Select Author', 'minlength'=>'5', 'pattern'=>'/0/'],
            'choices'=>$selections
        ])
        ->add('year', NumberType::Class, [ 'data'=>$book->getYear(),'attr'=>['class'=>'input-field', 'placeholder'=>'1933', 'min'=>'0','max'=>date("Y")]])
        ->add('type', TextType::Class, ['data'=>$book->getType(), 'attr'=>['class'=>'input-field', 'placeholder'=>'type']])
        ->add('create', SubmitType::Class, ['attr'=>['class'=>'waves-effect waves-light btn'], 'label'=>'Update Book'])
        ->getForm();
        
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $form = $form->getData();

            $title = $form->getTitle();
            $year = $form->getYear();
            $author = $form->getAuthor();
            $type = $form->getType();

            // Update
            $em = $this->getDoctrine()->getManager();
            // $insert = new Books();
            $book->setTitle($title);
            $book->setYear($year);
            $book->setType($type);
            $book->setAuthor($author);
            
            $em->flush();

            return $this->redirect($this->generateUrl("home"));
        }

        return $this->render('books/edit.html.twig',[
            'form'=>$form->createView(),
            'bookTitle'=>$book->getTitle()
        ]);
     }

    /**
     * @Route("/authors/delete/{id}", name="authors_delete")
    */
    public function delete($id, Request $request){
        $em = $this->getDoctrine()->getManager(); 
        $book = $this->getDoctrine()
        ->getRepository('AppBundle:Books')
        ->find($id);
        if($book){
            $em->remove($book);
            $em->flush();
        }
        return $this->redirect($this->generateUrl("home"));
    }
}