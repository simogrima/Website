<?php
/**
 * CommentController
 *
 * @author Suiko6272
 *
 * @version 1.0.0
 *
 */
namespace OmniBlog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CommentController extends AbstractActionController
{
    /**
     * The default action - show the home page
     */
    public function indexAction() {
        return new ViewModel();
    }
        
    public function addAction() {
        return new ViewModel();
    }
    
    public function editAction() {
        return new ViewModel();
    }
    
    public function deleteAction() {
        return new ViewModel();
    }
}