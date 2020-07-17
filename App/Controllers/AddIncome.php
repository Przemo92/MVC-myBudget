<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Income;
use \App\Flash;
/**
 * Items controller (example)
 *
 * PHP version 7.0
 */
//class Items extends \Core\Controller
class AddIncome extends Authenticated
{

    /**
     * Require the user to be authenticated before giving access to all methods in the controller
     *
     * @return void
     */
    /*
    protected function before()
    {
        $this->requireLogin();
    }
    */

    /**
     * Items index
     *
     * @return void
     */
     public $errors = [];
     public function __construct($data = [])
     {
         foreach ($data as $key => $value) {
             $this->$key = $value;
         };
     }
    public function showAction()
    {
        View::renderTemplate('AddIncome/show.html');
    }
    /**
     * choose a category income
     *
     * @return void
     */
    public function chooseIncomeAction()
    {
        $searchTerm = $_POST['searchTerm'] ?? '';
        Income::getDataIncome($searchTerm);
    }
    /**
     * Add a new item
     *
     * @return void
     */
    public function newAction()
    {
        $income = new Income($_POST);

        if($income->addNewIncome())
        {
          Flash::addMessage('Przychód dodano');
          $this->redirect('/AddIncome/show');
        }
        else
          {
            Flash::addMessage('Próba dodania przychodu nieudana.');
            $this->redirect('/AddIncome/show');
          }
        }
    }
