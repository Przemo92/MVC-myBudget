<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Expense;
use \App\Flash;
/**
 * Items controller (example)
 *
 * PHP version 7.0
 */
//class Items extends \Core\Controller
class AddExpense extends Authenticated
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
        View::renderTemplate('AddExpense/show.html');
    }
    /**
     * choose a category expense
     *
     * @return void
     */
    public function chooseExpenseAction()
    {
        $searchTerm = $_POST['searchTerm'] ?? '';
        Expense::getDataExpense($searchTerm);
    }
    public function choosePaymentMethodAction()
    {
        $searchTerm = $_POST['searchTerm'] ?? '';
        Expense::getDataPayment($searchTerm);
    }
    public function showLimitAction()
    {
        $expenseId = $_POST['expenseId'];
        $date1 = $_POST['date1'];
        Expense::getLimit($expenseId, $date1);
    }
    /**
     * Add a new item
     *
     * @return void
     */
    public function newAction()
    {
        $expense = new Expense($_POST);

        if($expense->addNewExpense())
        {
          Flash::addMessage('Dodano wydatek');
          $this->redirect('/AddExpense/show');
        }
        else
          {
            Flash::addMessage('Próba dodania wydatku nieudana.');
            $this->redirect('/AddExpense/show');
          }
        }
    }
