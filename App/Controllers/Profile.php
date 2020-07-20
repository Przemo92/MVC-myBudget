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
        $idInne = Settings::findInneIncome();
        Settings::assignIncomesToInneCategory($incomeId, $idInne);
        Settings::deleteIncomesThisCategory($incomeId);
        Settings::removeIncome($incomeId);
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
      $incomeName2 = strtolower($_POST['newIncome']);
      $incomeName = ucwords($incomeName2);
      if(Settings::checkCategoryIncomes($incomeName))
      {
          Settings::addIncome($incomeName);
          Flash::addMessage('Dodano nową kategorię');
          $this->redirect('/profile/showIncomes');
        }
        else {
          Flash::addMessage('Podana kategoria już istnieje. Proszę podać inną', Flash::WARNING);
          $this->redirect('/profile/showIncomes');
        }
    }
    public function editIncomeAction()
    {
      $incomeId = $_POST['idIncome2'];
      $incomeNewName2 = strtolower($_POST['editIncome']);
      $incomeNewName = ucwords($incomeNewName2);
      if(Settings::checkCategoryIncomes($incomeNewName))
      {
        Settings::editIncome($incomeNewName, $incomeId);
        Flash::addMessage('Zedytowano kategorię');
        $this->redirect('/profile/showIncomes');
      }
      else
        {
          Flash::addMessage('Podana kategoria już istnieje. Proszę podać inną', Flash::WARNING);
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
      $paymentName2 = strtolower($_POST['newPayment']);
      $paymentName = ucwords($paymentName2);
      if(Settings::checkCategoryPayments($paymentName))
      {
        Settings::addPayment($paymentName);
        Flash::addMessage('Dodano nową kategorię');
        $this->redirect('/profile/showPayments');
      }
      else
        {
          Flash::addMessage('Podana kategoria już istnieje. Proszę podać inną.', Flash::WARNING);
          $this->redirect('/profile/showPayments');
        }
    }
    public function editPaymentAction()
    {
      $paymentId = $_POST['idPayment2'];
      $paymentNewName2 = strtolower($_POST['editPayment']);
      $paymentNewName = ucwords($paymentNewName2);
      if(Settings::checkCategoryPayments($paymentNewName))
      {
        Settings::editPayment($paymentNewName, $paymentId);
        Flash::addMessage('Zedytowano kategorię');
        $this->redirect('/profile/showPayments');
      }
      else
        {
          Flash::addMessage('Podana kategoria już istnieje. Proszę podać inną.', Flash::WARNING);
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
        $idInne = Settings::findInneExpense();
        Settings::assignExpensesToInneCategory($expenseId, $idInne);
        Settings::deleteExpensesThisCategory($expenseId);
        Settings::removeCategoryExpense($expenseId);
        Flash::addMessage('Usunięto kategorię');
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
      $expenseName2 = strtolower($_POST['newExpense']);
      $expenseName = ucwords($expenseName2);
      if(Settings::checkCategoryExpenses($expenseName))
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
      $expenseNewName2 = strtolower($_POST['editExpense']);
      $expenseNewName = ucwords($expenseNewName2);
      $expenseLimit = $_POST['expenseLimit'];
      if($expenseLimit == "" && $expenseNewName == "")
      {
        Flash::addMessage('Próba edycji kategorii nieudana.', Flash::WARNING);
        $this->redirect('/profile/showExpenses');
      }
      else
        {
          if(Settings::checkCategoryExpenses($expenseNewName))
          {
            Settings::editExpense($expenseNewName, $expenseId, $expenseLimit);
            Flash::addMessage('Zedytowano kategorię');
            $this->redirect('/profile/showExpenses');
          }
          else
            {
              Flash::addMessage('Podana kategoria już istnieje.', Flash::WARNING);
              $this->redirect('/profile/showExpenses');
            }
        }
    }
}
