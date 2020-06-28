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
class Income extends \Core\Model
{
 public $errors = [];
 
 public function __construct($data = [])
 {
   foreach ($data as $key => $value) {
     $this->$key = $value;
   };
 }
  public static function getDataIncome($selectSearchTerm)
 {
   $user = Auth::getUser();
   if(!isset($selectSearchTerm))
     {
       $fetchData = static::getCategorryName();
     }
   else
     {
       $sql = "SELECT * FROM incomes_category_assigned_to_users WHERE name LIKE '%".$selectSearchTerm."%' AND user_id = '$user->id'";
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

 public function addNewIncome()
 {
     $user = Auth::getUser();
     $this->validate();
       if (empty($this->errors))
       {
         $sql = 'INSERT INTO incomes (user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment)
                 VALUES (:user_id, :income, :money, :date, :comment)';

         $db = static::getDB();
         $stmt = $db->prepare($sql);

         $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
         $stmt->bindValue(':income', $this->incomeCategory, PDO::PARAM_INT);
         $stmt->bindValue(':money', $this->money, PDO::PARAM_INT);
         $stmt->bindValue(':date', $this->datePicker, PDO::PARAM_STR);
         $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);

         return $stmt->execute();
       }
       return false;
 }
 public function validate()
  {
    if($this->money == '')
    {
      $this->errors[] = 'Proszę podać kwotę.';
    }
    if($this->datePicker == '')
    {
      $this->errors[] = 'Proszę podać datę.';
    }
    if($this->incomeCategory == 0)
    {
      $this->errors[] = 'Proszę wybrać kategorię.';
    }
  }

}
