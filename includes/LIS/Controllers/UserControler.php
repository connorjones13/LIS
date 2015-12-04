<?php
   /**
    * Created by PhpStorm.
    * User: Bethany
    * Date: 11/26/2015
    * Time: 5:18 PM
    */

   namespace LIS\Controllers;
   use LIS\User\Admin;
   use LIS\User\Employee;
   use LIS\User\User;

   class UserControler extends BaseController
   {

       // Update User Info    TODO: check if password can be updated in this way!
        public function updateUserInfo(User $user, $email, $phone, $address_line_1, $address_line_2, $address_zip, $address_city,
                                       $address_state, $address_country_code) {
            // TODO: format errors like update item class

            $user->updateAddress($address_line_1, $address_line_2, $address_zip, $address_city,
                $address_state, $address_country_code);
            $user->updatePhoneNumber($phone);
            $user->updateEmail($email);



        }

       // Change User Privilege
       public function changeUserPrivilege(User $user, $new_privilege) {
           //check that session user is an Admin
           if ($this->getSessionUser()->getPrivilegeLevel() != User::PRIVILEGE_ADMIN) {
               // set error
           }
           $privilegeName = "";
           switch($new_privilege) {
               case User::PRIVILEGE_ADMIN:
                   Admin::setToPrivilegeLevel($user);
                   $privilegeName = "Admin";
                   break;
               case User::PRIVILEGE_EMPLOYEE:
                   Employee::setToPrivilegeLevel($user);
                   $privilegeName = "Employee";
                   break;
               case User::PRIVILEGE_USER:
                   User::setToPrivilegeLevel($user);
                   $privilegeName = "User";
                   break;
               default:
                   // error exit out
           }
           // Success message
           $_SESSION["user privilege changed"] = $user->getNameFull() . "'s privilege changed to " . $privilegeName ;

       }

       // Deactivate User
       public function deactivateUser(User $user) {
           //check that session user is an Admin
           if ($this->getSessionUser()->getPrivilegeLevel() != User::PRIVILEGE_ADMIN) {
               // set error
           }
           $user->setInactive();

           // TODO: success message
       }
   }