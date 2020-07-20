<?php

namespace App\Models;

use PDO;
use App\Auth;
//use \App\Token;

/**
 * Remembered login model
 *
 * PHP version 7.0
 */
class Expense extends \Core\Model
{
 public $errors = [];

 public function __construct($data = [])
 {
   foreach ($data as $key => $value) {
     $this->$key = $value;
   };
 }
  public static function getDataExpense($selectSearchTerm)
 {
   $user = Auth::getUser();
   if(!isset($selectSearchTerm))
     {
       $fetchData = static::getCategorryName();
     }
   else
     {
       $sql = "SELECT * FROM expenses_category_assigned_to_users WHERE name LIKE '%".$selectSearchTerm."%' AND user_id = '$user->id'";
       $fetchData = static::getUsersSelect($sql);
     }

   $data = array();
   foreach ($fetchData as $row) {
     $data[] = array('id'=>$row['id'], 'text'=>$row['name']);
   };
   echo json_encode($data);
 }
 public static function getDataPayment($selectSearchTerm)
{
  $user = Auth::getUser();
  if(!isset($selectSearchTerm))
    {
      $fetchData = static::getCategorryName();
    }
  else
    {
      $sql = "SELECT * FROM payment_methods_assigned_to_users WHERE name LIKE '%".$selectSearchTerm."%' AND user_id = '$user->id'";
      $fetchData = static::getUsersSelect($sql);
    }

  $data = array();
  foreach ($fetchData as $row) {
    $data[] = array('id'=>$row['id'], 'text'=>$row['name']);
  };
  echo json_encode($data);
}
 static public function getUsersSelect($sql)
 {
   $db = static::getDB();
   $stmt = $db->prepare($sql);
   $stmt->execute();
   return $stmt->fetchAll(PDO::FETCH_ASSOC);
 }

 public function addNewExpense()
 {
     $user = Auth::getUser();
     $this->validate();
       if (empty($this->errors))
       {
         $sql = 'INSERT INTO expenses (user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment)
                VALUES (:user_id, :expense_category_assigned_to_user_id, :payment_method_assigned_to_user_id, :amount, :date_of_expense, :expense_comment)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
        $stmt->bindValue(':expense_category_assigned_to_user_id', $this->expenseCategory, PDO::PARAM_INT);
        $stmt->bindValue(':payment_method_assigned_to_user_id', $this->paymentMethod, PDO::PARAM_INT);
        $stmt->bindValue(':amount', $this->expenseMoney, PDO::PARAM_INT);
        $stmt->bindValue(':date_of_expense', $this->datePicker, PDO::PARAM_STR);
        $stmt->bindValue(':expense_comment', $this->expenseComment, PDO::PARAM_STR);

        return $stmt->execute();
       }
       return false;
 }
 public function validate()
  {
    if($this->expenseMoney == '')
    {
      $this->errors[] = 'Proszę podać kwotę.';
    }
    if($this->datePicker == '')
    {
      $this->errors[] = 'Proszę podać datę.';
    }
    if($this->expenseCategory == 0)
    {
      $this->errors[] = 'Proszę wybrać kategorię.';
    }
    if($this->paymentMethod == 0)
    {
      $this->errors[] = 'Proszę wybrać rodzaj płatności.';
    }
  }
  public static function getLimit($expenseId, $date1)
  {
    $sql = "SELECT * FROM expenses_category_assigned_to_users WHERE id='$expenseId'";

  $fetchData = static::getDataSelect($sql);
  foreach ($fetchData as $row)
  {
    if($row["expenseLimit"] != NULL)
    {
      $expenseSum =0;
      $sql2 = " SELECT * FROM expenses
                WHERE expense_category_assigned_to_user_id='$expenseId'
                AND date_of_expense LIKE '$date1%'";
      $fetchData = static::getDataSelect($sql2);
      foreach ($fetchData as $row2)
      {
        $expenseSum += $row2["amount"];
      }
      $miniBilans = $row["expenseLimit"] - $expenseSum;
      echo 'Limit kategorii z aktualnego miesiąca
      <div>Limit - Wydatki = Mini bilans</div>';  ;
      if($miniBilans < 0)
      {
        echo '<div style="color: red;">'.$row["expenseLimit"].' - '.$expenseSum.' = '.$miniBilans.'</div>
        <div style="color: red;">Przekroczyłeś swój limit</div>';
      }
      else {
        echo '<div style="color: green;">'.$row["expenseLimit"].' - '.$expenseSum.' = '.$miniBilans.'</div>';
      }
    }
  }
  }
  static public function getDataSelect($sql)
  {
    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
