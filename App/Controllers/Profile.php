<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\Settings;

/**
 * Profile controller
 *
 * PHP version 7.0
 */
class Profile extends Authenticated
{

    /**
     * Before filter - called before each action method
     *
     * @return void
     */
    protected function before()
    {
        parent::before();

        $this->user = Auth::getUser();
    }

    /**
     * Show the profile
     *
     * @return void
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
        View::renderTemplate('Profile/show.html', [
            'user' => $this->user
        ]);
    }

    /**
     * Show the form for editing the profile
     *
     * @return void
     */
    public function editAction()
    {
        View::renderTemplate('Profile/edit.html', [
            'user' => $this->user
        ]);
    }

    /**
     * Update the profile
     *
     * @return void
     */
    public function updateAction()
    {
        if ($this->user->updateProfile($_POST)) {

            Flash::addMessage('Zmiany zapisano');

            $this->redirect('/profile/show');

        } else {

            View::renderTemplate('Profile/edit.html', [
                'user' => $this->user
            ]);

        }
    }
    public function showIncomesAction()
    {
      View::renderTemplate('Profile/showIncomes.html');
    }
    public function getCategoryIncomesAction()
    {
      Settings::getCategoryIncomes();
    }
    public function removeCategoryIncomeAction()
    {
      if($incomeId = $_POST['idIncome'])
      {
        Settings::removeIncome($incomeId);
        Settings::deleteIncomesThisCategory($incomeId);
        Flash::addMessage('Usunięto kategorię');
        $this->redirect('/profile/showIncomes');
      }
      else
        {
          Flash::addMessage('Próba usunięcia kategorii nieudana.', Flash::WARNING);
          $this->redirect('/profile/showIncomes');
        }
    }
    public function addNewIncomeAction()
    {
      if($incomeName = $_POST['newIncome'])
      {
        Settings::addIncome($incomeName);
        Flash::addMessage('Dodano nową kategorię');
        $this->redirect('/profile/showIncomes');
      }
      else
        {
          Flash::addMessage('Próba dodania nowej kategorii nieudana.', Flash::WARNING);
          $this->redirect('/profile/showIncomes');
        }
    }
    public function editIncomeAction()
    {
      $incomeId = $_POST['idIncome2'];
      $incomeNewName = $_POST['editIncome'];
      if($incomeNewName!= "")
      {
        $incomeNewName = $_POST['editIncome'];
        Settings::editIncome($incomeNewName, $incomeId);
        Flash::addMessage('Zedytowano kategorię');
        $this->redirect('/profile/showIncomes');
      }
      else
        {
          Flash::addMessage('Próba edycji kategorii nieudana.', Flash::WARNING);
          $this->redirect('/profile/showIncomes');
        }
    }

    public function showPaymentsAction()
    {
      View::renderTemplate('Profile/showPayments.html');
    }
    public function getCategoryPaymentsAction()
    {
      Settings::getCategoryPayments();
    }
    public function removeCategoryPaymentAction()
    {
      if($paymentId = $_POST['idPayment'])
      {
        Settings::removePayment($paymentId);
        Flash::addMessage('Usunięto kategorię');
        $this->redirect('/profile/showPayments');
      }
      else
        {
          Flash::addMessage('Próba usunięcia kategorii nieudana.', Flash::WARNING);
          $this->redirect('/profile/showPayments');
        }
    }
    public function addNewPaymentAction()
    {
      if($paymentName = $_POST['newPayment'])
      {
        Settings::addPayment($paymentName);
        Flash::addMessage('Dodano nową kategorię');
        $this->redirect('/profile/showPayments');
      }
      else
        {
          Flash::addMessage('Próba dodania nowej kategorii nieudana.', Flash::WARNING);
          $this->redirect('/profile/showPayments');
        }
    }
    public function editPaymentAction()
    {
      $paymentId = $_POST['idPayment2'];
      $paymentNewName = $_POST['editPayment'];
      if($paymentNewName!= "")
      {
        $paymentNewName = $_POST['editPayment'];
        Settings::editPayment($paymentNewName, $paymentId);
        Flash::addMessage('Zedytowano kategorię');
        $this->redirect('/profile/showPayments');
      }
      else
        {
          Flash::addMessage('Próba edycji kategorii nieudana.', Flash::WARNING);
          $this->redirect('/profile/showPayments');
        }
    }

    public function showExpensesAction()
    {
      View::renderTemplate('Profile/showExpenses.html');
    }
    public function getCategoryExpensesAction()
    {
      Settings::getCategoryExpenses();
    }
    public function removeCategoryExpenseAction()
    {
      if($expenseId = $_POST['idExpense'])
      {
        Settings::removeCategoryExpense($expenseId);
        Flash::addMessage('Usunięto kategorię');
        Settings::deleteExpensesThisCategory($expenseId);
        $this->redirect('/profile/showExpenses');
      }
      else
        {
          Flash::addMessage('Próba usunięcia kategorii nieudana.', Flash::WARNING);
          $this->redirect('/profile/showExpenses');
        }
    }
    public function addNewExpenseAction()
    {
      if($expenseName = $_POST['newExpense'])
      {
        $expenseLimit = $_POST['expenseLimit'];
        Settings::addExpense($expenseName, $expenseLimit);
        Flash::addMessage('Dodano nową kategorię');
        $this->redirect('/profile/showExpenses');
      }
      else
        {
          Flash::addMessage('Próba dodania nowej kategorii nieudana.', Flash::WARNING);
          $this->redirect('/profile/showExpenses');
        }
    }
    public function editExpenseAction()
    {
      $expenseId = $_POST['idExpense2'];
      $expenseNewName = $_POST['editExpense'];
      $expenseLimit = $_POST['expenseLimit'];
      if($expenseLimit == "" && $expenseNewName == "")
      {
        Flash::addMessage('Próba edycji kategorii nieudana.', Flash::WARNING);
        $this->redirect('/profile/showExpenses');
      }
      else
        {
          Settings::editExpense($expenseNewName, $expenseId, $expenseLimit);
          Flash::addMessage('Zedytowano kategorię');
          $this->redirect('/profile/showExpenses');
        }
    }
}
