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
class Balance extends \Core\Controller
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
        View::renderTemplate('balance/show.html');
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
    }
