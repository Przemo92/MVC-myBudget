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
class BalanceModel extends \Core\Model
{
  public static function getIncomes($date1, $date2)
 {
   $user = Auth::getUser();
   if ($date2 == 0)
   {
     $sql = "SELECT *
             FROM incomes
             WHERE user_id = '$user->id'AND date_of_income LIKE '$date1%'
             ORDER BY income_category_assigned_to_user_id, date_of_income";
   }
   else
   {
     $sql = "SELECT * FROM incomes
             WHERE user_id='$user->id' AND date_of_income BETWEEN '$date1' AND '$date2'
             ORDER BY income_category_assigned_to_user_id, date_of_income";
   }
   $fetchData = static::getDataSelect($sql);
   $temp_cat =0;
   foreach ($fetchData as $row)
   {
			if($temp_cat!=$row["income_category_assigned_to_user_id"])
			{
        $temp_cat = $row["income_category_assigned_to_user_id"];
        $sql2 = "SELECT name FROM incomes_category_assigned_to_users WHERE user_id='$user->id' AND id='$temp_cat'";
        $fetchData2 = static::getDataSelect($sql2);

        foreach ($fetchData2 as $row2)
        $name = $row2["name"];

				echo "<div>$name</div><div>data: ".$row["date_of_income"]." - kwota: ".$row["amount"]." - komentarz: ".$row["income_comment"]."</div>";
			}
      else {
        echo "<div>data: ".$row["date_of_income"]." - kwota: ".$row["amount"]." - komentarz: ".$row["income_comment"]."</div>";
      }
   }
 }
 public static function getExpenses($date1, $date2)
 {
  $user = Auth::getUser();
  if ($date2 == 0)
  {
    $sql = "SELECT *
            FROM expenses
            WHERE user_id = '$user->id'AND date_of_expense LIKE '$date1%'
            ORDER BY expense_category_assigned_to_user_id, date_of_expense";
  }
  else
  {
    $sql = "SELECT * FROM expenses
            WHERE user_id='$user->id' AND date_of_expense BETWEEN '$date1' AND '$date2'
            ORDER BY expense_category_assigned_to_user_id, date_of_expense";
  }
  $fetchData = static::getDataSelect($sql);

  $temp_cat =0;
  foreach ($fetchData as $row)
  {
     if($temp_cat!=$row["expense_category_assigned_to_user_id"])
     {
       $temp_cat = $row["expense_category_assigned_to_user_id"];
       $sql2 = "SELECT name FROM expenses_category_assigned_to_users WHERE user_id='$user->id' AND id='$temp_cat'";
       $fetchData2 = static::getDataSelect($sql2);

       foreach ($fetchData2 as $row2)
       $name = $row2["name"];

       echo "<div>$name</div><div>data: ".$row["date_of_expense"]." - kwota: ".$row["amount"]." - komentarz: ".$row["expense_comment"]."</div>";
     }
     else {
       echo "<div>data: ".$row["date_of_expense"]." - kwota: ".$row["amount"]." - komentarz: ".$row["expense_comment"]."</div>";
     }
  }
 }
  static public function getChartData($date1, $date2)
  {
    $user = Auth::getUser();
    if ($date2 == 0)
    {
      $sql = "SELECT *
              FROM expenses
              WHERE user_id = '$user->id'AND date_of_expense LIKE '$date1%'
              ORDER BY expense_category_assigned_to_user_id, date_of_expense";
    }
    else
    {
      $sql = "SELECT * FROM expenses
              WHERE user_id='$user->id' AND date_of_expense BETWEEN '$date1' AND '$date2'
              ORDER BY expense_category_assigned_to_user_id, date_of_expense";
    }
    $fetchData = static::getDataSelect($sql);

    $data[] = array();
    $temp_cat =0;
    foreach ($fetchData as $row)
    {
       if($temp_cat!=$row["expense_category_assigned_to_user_id"])
       {
         $temp_cat = $row["expense_category_assigned_to_user_id"];
         if ($date2 == 0)
         {
           $sql2 = "SELECT
  									SUM(expenses.amount) AS sum, expenses_category_assigned_to_users.name
  									FROM
  									expenses_category_assigned_to_users, expenses
  									WHERE expenses_category_assigned_to_users.user_id = '$user->id'
                    AND expenses_category_assigned_to_users.user_id = expenses.user_id
                    AND expenses_category_assigned_to_users.id = $temp_cat
                    AND expenses_category_assigned_to_users.id = expenses.expense_category_assigned_to_user_id
                    AND expenses.date_of_expense LIKE '$date1%'";
         }
         else
         {
           $sql2 = "SELECT
                    SUM(expenses.amount) AS sum, expenses_category_assigned_to_users.name
                    FROM
                    expenses_category_assigned_to_users, expenses
                    WHERE expenses_category_assigned_to_users.user_id = '$user->id'
                    AND expenses_category_assigned_to_users.user_id = expenses.user_id
                    AND expenses_category_assigned_to_users.id = $temp_cat
                    AND expenses_category_assigned_to_users.id = expenses.expense_category_assigned_to_user_id
                    AND expenses.date_of_expense BETWEEN '$date1' AND '$date2'";
         }
         $fetchData2 = static::getDataSelect($sql2);

         foreach ($fetchData2 as $row2)
          {
            $data[] = array('name'=>$row2["name"], 'expense_sum'=>$row2['sum']);
           }
       }
    }
    echo json_encode($data);
  }

 static public function getDataSelect($sql)
 {
   $db = static::getDB();
   $stmt = $db->prepare($sql);
   $stmt->execute();
   return $stmt->fetchAll(PDO::FETCH_ASSOC);
 }
}
