<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\BalanceModel;
use \App\Flash;
/**
 * Items controller (example)
 *
 * PHP version 7.0
 */
//class Items extends \Core\Controller
class Balance extends Authenticated
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
        View::renderTemplate('Balance/show.html');
    }
    public function showIncomesAction()
    {
        $date1 = $_POST['date1'];
        $date2 = $_POST['date2'];
        BalanceModel::getIncomes($date1, $date2);
    }
    public function showExpensesAction()
    {
        $date1 = $_POST['date1'];
        $date2 = $_POST['date2'];
        BalanceModel::getExpenses($date1, $date2);
    }

    public function showChartAction()
    {
        $date1 = $_POST['date1'];
        $date2 = $_POST['date2'];
        BalanceModel::getChartData($date1, $date2);
    }
    public function showIESumAction()
    {
      $date1 = $_POST['date1'];
      $date2 = $_POST['date2'];
      BalanceModel::getIESum($date1, $date2);
    }
    public function removeIncomeAction()
    {
      if($incomeId = $_POST['idIncome'])
      {
        BalanceModel::removeIncome($incomeId);
        Flash::addMessage('Usunięto przychód');
        $this->redirect('/balance/show');
      }
      else
        {
          Flash::addMessage('Próba usunięcia przychodu nieudana.');
          $this->redirect('/balance/show');
        }
    }
    public function removeExpenseAction()
    {
      if($expenseId = $_POST['idExpense'])
      {
        BalanceModel::removeExpense($expenseId);
        Flash::addMessage('Usunięto wydatek');
        $this->redirect('/balance/show');
      }
      else
        {
          Flash::addMessage('Próba usunięcia wydatku nieudana.');
          $this->redirect('/balance/show');
        }
    }
    public function showLimitsAction()
    {
        $date1 = $_POST['date1'];
        $date2 = $_POST['date2'];
        BalanceModel::showLimit($date1, $date2);
    }
    }
