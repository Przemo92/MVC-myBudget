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
        {

				echo '<div>'.$row2["name"].'</div><div>data: '.$row["date_of_income"].' - kwota: '.$row["amount"].' - komentarz: '.$row["income_comment"].'
        <div style="float: right">
          <button type="button" value="'.$row["id"].'" data-toggle="modal" data-target="#deleteModal" class="button btn btn-sm btn-danger py-0" style="font-size: 0.7em; margin-right: 5px;"><i class="icon-trash"></i></button>
        </div>
        </div>';
        }
			}
      else {
        echo '<div>data: '.$row["date_of_income"].' - kwota: '.$row["amount"].' - komentarz: '.$row["income_comment"].'
        <div style="float: right">
          <button type="button" value="'.$row["id"].'" data-toggle="modal" data-target="#deleteModal" class="button btn btn-sm btn-danger py-0" style="font-size: 0.7em; margin-right: 5px;"><i class="icon-trash"></i></button>
        </div>
        </div>';
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

       echo '<div>'.$name.'</div><div>data: '.$row["date_of_expense"].' - kwota: '.$row["amount"].' - komentarz: '.$row["expense_comment"].'
       <div style="float: right">
         <button type="button" value="'.$row["id"].'" data-toggle="modal" data-target="#deleteModal2" class="button btn btn-sm btn-danger py-0" style="font-size: 0.7em; margin-right: 5px;"><i class="icon-trash"></i></button>
       </div>
       </div>';
     }
     else {
       echo '<div>data: '.$row["date_of_expense"].' - kwota: '.$row["amount"].' - komentarz: '.$row["expense_comment"].'
       <div style="float: right">
           <button type="button" value="'.$row["id"].'" data-toggle="modal" data-target="#deleteModal2" class="button btn btn-sm btn-danger py-0" style="font-size: 0.7em; margin-right: 5px;"><i class="icon-trash"></i></button>
       </div>
       </div>';
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
static public function getIESum($date1, $date2)
{
  $expenseSum = 0;
  $incomeSum = 0;
  $user = Auth::getUser();
  if ($date2 == 0)
  {
    $sql = " SELECT *
            FROM expenses
            WHERE user_id = '$user->id'AND date_of_expense LIKE '$date1%'";
    $sql2 =" SELECT *
            FROM incomes
            WHERE user_id = '$user->id'AND date_of_income LIKE '$date1%'";
  }
  else
  {
    $sql = " SELECT *
            FROM expenses
            WHERE user_id = '$user->id'AND date_of_expense BETWEEN '$date1' AND '$date2'";
    $sql2 =" SELECT *
            FROM incomes
            WHERE user_id = '$user->id'AND date_of_income BETWEEN '$date1' AND '$date2'";
  }
  $fetchData = static::getDataSelect($sql);
  foreach ($fetchData as $row2)
  {
    $expenseSum += $row2['amount'];

  }
  $fetchData2 = static::getDataSelect($sql2);
  foreach ($fetchData2 as $row3)
  {
    $incomeSum += $row3['amount'];
  }
  $balance = $incomeSum - $expenseSum;
  echo number_format($incomeSum, 2,',','').' - '.number_format($expenseSum, 2,',','').' = '.number_format($balance, 2,',','');
  if($balance < 0)
  {
    echo '<div style="color: red;"> Twój bilans finansowy jest ujemny, uważaj, popadasz w długi!</div>';
  }
  else {
    echo '<div> Gratulacje! Twój bilans finansowy jest dodatni! Zaoszczędziłeś '.number_format($balance, 2,',','').'</div>';
  }

}
 static public function getDataSelect($sql)
 {
   $db = static::getDB();
   $stmt = $db->prepare($sql);
   $stmt->execute();
   return $stmt->fetchAll(PDO::FETCH_ASSOC);
 }
 public static function removeIncome($incomeId)
{
  $user = Auth::getUser();

  $sql = "DELETE FROM incomes WHERE id= :id";

  $db = static::getDB();
  $stmt = $db->prepare($sql);

  $stmt->bindValue(':id', $incomeId, PDO::PARAM_INT);
  $stmt->execute();
}
public static function removeExpense($expenseId)
{
 $user = Auth::getUser();

 $sql = "DELETE FROM expenses WHERE id= :id";

 $db = static::getDB();
 $stmt = $db->prepare($sql);

 $stmt->bindValue(':id', $expenseId, PDO::PARAM_INT);
 $stmt->execute();
}
public static function showLimit($date1, $date2)
{
 $user = Auth::getUser();
 $sql = "SELECT *
         FROM expenses_category_assigned_to_users
         WHERE user_id = '$user->id'";

   $fetchData = static::getDataSelect($sql);
   foreach ($fetchData as $row)
   {
     $miniBalance = 0;
     $expenseSum = 0;

     if($limit = $row['expenseLimit'])
     {
       $catName = $row['name'];
       $catId = $row['id'];
       $sql2 = "SELECT *
               FROM expenses
               WHERE user_id = '$user->id'
               AND expense_category_assigned_to_user_id = '$catId'
               AND date_of_expense LIKE '$date1%'";

         $fetchData = static::getDataSelect($sql2);
         foreach ($fetchData as $row2)
         {
           $expenseSum += $row2['amount'];
         }
         $miniBalance = $limit - $expenseSum;
         if($miniBalance >= 0)
         echo '<div style="color: green;">'.$catName.': '.number_format($limit, 2,',','').' - '.number_format($expenseSum, 2,',','').' = '.number_format($miniBalance, 2,',','').'</div>';
         else {
           echo '<div style="color: red;">'.$catName.': '.number_format($limit, 2,',','').' - '.number_format($expenseSum, 2,',','').' = '.number_format($miniBalance, 2,',','').'</div>';
         }
     }
   }
}
}
