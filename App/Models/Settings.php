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
class Settings extends \Core\Model
{
    public static function getCategoryIncomes()
   {
     $user = Auth::getUser();

       $sql = "SELECT * FROM incomes_category_assigned_to_users WHERE user_id='$user->id'";

     $fetchData = static::getDataSelect($sql);
     foreach ($fetchData as $row)
     {
       $name = $row["name"];
       $incomeId = $row["id"];

       echo '<div class="edit-settings">'.$name.'
         <div style="float: right">
           <button type="button" value="'.$incomeId.'" data-toggle="modal" data-target="#deleteModal" class="button btn btn-danger btn-sm"><i class="icon-trash"></i></button>
           <button type="button" value="'.$incomeId.'" data-toggle="modal" data-target="#editModal" class="button btn btn-info btn-sm" style="margin-right: 5px;"><i class="icon-pencil-alt"></i></button>
         </div>
       </div>';
  			}
   }
   public static function removeIncome($incomeId)
  {
    $user = Auth::getUser();

    $sql = "DELETE FROM incomes_category_assigned_to_users WHERE id= :id";

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':id', $incomeId, PDO::PARAM_INT);
    $stmt->execute();
  }
  public static function addIncome($newIncome)
 {
   $user = Auth::getUser();

   $sql = 'INSERT INTO incomes_category_assigned_to_users (user_id, name) VALUES (:id, :name)';

   $db = static::getDB();
   $stmt = $db->prepare($sql);

   $stmt->bindValue(':id', $user->id, PDO::PARAM_INT);
   $stmt->bindValue(':name', $newIncome, PDO::PARAM_STR);
   $stmt->execute();
 }
 public static function editIncome($incomeNewName, $incomeId)
{
  $user = Auth::getUser();

  $sql = 'UPDATE incomes_category_assigned_to_users
          SET name = :name
          WHERE id = :incomeId';
  $db = static::getDB();
  $stmt = $db->prepare($sql);

  $stmt->bindValue(':incomeId', $incomeId, PDO::PARAM_INT);
  $stmt->bindValue(':name', $incomeNewName, PDO::PARAM_STR);
  $stmt->execute();
}
   public static function getCategoryPayments()
  {
    $user = Auth::getUser();

      $sql = "SELECT * FROM payment_methods_assigned_to_users WHERE user_id='$user->id'";

    $fetchData = static::getDataSelect($sql);
    foreach ($fetchData as $row)
    {
      $name = $row["name"];
      $paymentId = $row["id"];

      echo '<div class="edit-settings">'.$name.'
        <div style="float: right">
          <button type="button" value="'.$paymentId.'" data-toggle="modal" data-target="#deleteModal" class="button btn btn-danger btn-sm"><i class="icon-trash"></i></button>
          <button type="button" value="'.$paymentId.'" data-toggle="modal" data-target="#editModal" class="button btn btn-info btn-sm" style="margin-right: 5px;"><i class="icon-pencil-alt"></i></button>
        </div>
      </div>';
       }
  }
  public static function removePayment($paymentId)
 {
   $user = Auth::getUser();

   $sql = "DELETE FROM payment_methods_assigned_to_users WHERE id= :id";

   $db = static::getDB();
   $stmt = $db->prepare($sql);

   $stmt->bindValue(':id', $paymentId, PDO::PARAM_INT);
   $stmt->execute();
 }
  public static function addPayment($newPayment)
 {
   $user = Auth::getUser();

   $sql = 'INSERT INTO payment_methods_assigned_to_users (user_id, name) VALUES (:id, :name)';

   $db = static::getDB();
   $stmt = $db->prepare($sql);

   $stmt->bindValue(':id', $user->id, PDO::PARAM_INT);
   $stmt->bindValue(':name', $newPayment, PDO::PARAM_STR);
   $stmt->execute();
 }
public static function editPayment($paymentNewName, $paymentId)
{
 $user = Auth::getUser();

 $sql = 'UPDATE payment_methods_assigned_to_users
         SET name = :name
         WHERE id = :paymentId';
 $db = static::getDB();
 $stmt = $db->prepare($sql);

 $stmt->bindValue(':paymentId', $paymentId, PDO::PARAM_INT);
 $stmt->bindValue(':name', $paymentNewName, PDO::PARAM_STR);
 $stmt->execute();
}

public static function getCategoryExpenses()
{
 $user = Auth::getUser();

   $sql = "SELECT * FROM expenses_category_assigned_to_users WHERE user_id='$user->id'";

 $fetchData = static::getDataSelect($sql);
 foreach ($fetchData as $row)
 {
   $name = $row["name"];
   $expenseId = $row["id"];

   echo '<div class="edit-settings">'.$name.'
     <div style="float: right">
       <button type="button" value="'.$expenseId.'" data-toggle="modal" data-target="#deleteModal" class="button btn btn-danger btn-sm"><i class="icon-trash"></i></button>
       <button type="button" value="'.$expenseId.'" data-toggle="modal" data-target="#editModal" class="button btn btn-info btn-sm" style="margin-right: 5px;"><i class="icon-pencil-alt"></i></button>
     </div>
   </div>';
    }
}
public static function removeExpense($expenseId)
{
$user = Auth::getUser();

$sql = "DELETE FROM expenses_category_assigned_to_users WHERE id= :id";

$db = static::getDB();
$stmt = $db->prepare($sql);

$stmt->bindValue(':id', $expenseId, PDO::PARAM_INT);
$stmt->execute();
}
public static function addExpense($newExpense)
{
$user = Auth::getUser();

$sql = 'INSERT INTO expenses_category_assigned_to_users (user_id, name) VALUES (:id, :name)';

$db = static::getDB();
$stmt = $db->prepare($sql);

$stmt->bindValue(':id', $user->id, PDO::PARAM_INT);
$stmt->bindValue(':name', $newExpense, PDO::PARAM_STR);
$stmt->execute();
}
public static function editExpense($expenseNewName, $expenseId)
{
$user = Auth::getUser();

$sql = 'UPDATE expenses_category_assigned_to_users
      SET name = :name
      WHERE id = :expenseId';
$db = static::getDB();
$stmt = $db->prepare($sql);

$stmt->bindValue(':expenseId', $expenseId, PDO::PARAM_INT);
$stmt->bindValue(':name', $expenseNewName, PDO::PARAM_STR);
$stmt->execute();
}
   static public function getDataSelect($sql)
   {
     $db = static::getDB();
     $stmt = $db->prepare($sql);
     $stmt->execute();
     return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }
}
